<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    /**
     * Proses pengajuan pembayaran oleh pelanggan menggunakan Xendit.
     * Alokasi dilakukan otomatis secara proporsional per item.
     */
    public function store(Request $request, $pesananId)
    {
        // Pastikan pesanan milik pelanggan yang login
        $pesanan = Pesanan::with('details.produk')
            ->where('user_id', Auth::id())
            ->findOrFail($pesananId);

        $request->validate([
            'catatan_pelanggan' => 'nullable|string|max:500',
            'termin_ke' => 'required|in:1,2',
        ]);

        $terminKe = (int) $request->termin_ke;

        // --- Cegah submit termin yang sudah terverifikasi ---
        $pembayaranYangAda = $pesanan->pembayarans()->where('termin_ke', $terminKe)->first();
        if ($pembayaranYangAda) {
            if ($pembayaranYangAda->status === 'verified') {
                return back()->with('error', 'Termin ' . $terminKe . ' sudah dilunasi.');
            }

            // Jika statusnya pending dan memiliki xendit_invoice_url, kita cek apakah masih aktif
            if ($pembayaranYangAda->status === 'pending' && $pembayaranYangAda->xendit_invoice_id) {
                try {
                    $res = Http::withBasicAuth(env('XENDIT_API_KEY'), '')
                        ->get('https://api.xendit.co/v2/invoices/' . $pembayaranYangAda->xendit_invoice_id);

                    if ($res->successful()) {
                        $xenditStatus = $res->json('status');
                        if (in_array($xenditStatus, ['PENDING'])) {
                            // Invoice masih aktif di Xendit, langsung redirect kesana
                            return redirect($pembayaranYangAda->xendit_invoice_url);
                        } elseif (in_array($xenditStatus, ['PAID', 'SETTLED'])) {
                            // Ternyata sudah paid, update status lokal
                            DB::beginTransaction();
                            $pembayaranYangAda->update([
                                'status' => 'verified',
                                'verified_at' => now(),
                            ]);
                            $pesanan->recalculatePembayaran();
                            $pesanan->recalculateItemCoverage();
                            DB::commit();
                            return redirect()->route('pelanggan.pesanan.show', $pesanan->id)->with('success', 'Pembayaran Termin ' . $terminKe . ' berhasil diverifikasi!');
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal mengecek status Xendit Invoice yang sudah ada: ' . $e->getMessage());
                }

                // Jika EXPIRED atau bermasalah, kita hapus pembayaran pending yang lama agar bisa membuat yang baru
                DB::beginTransaction();
                $pembayaranYangAda->delete();
                DB::commit();
            }
        }

        // --- Cegah bayar Termin 2 sebelum Termin 1 verified ---
        if ($terminKe === 2) {
            $termin1 = $pesanan->pembayarans()->where('termin_ke', 1)->where('status', 'verified')->first();
            if (!$termin1) {
                return back()->with('error', 'Termin 1 (DP) harus sudah lunas sebelum membayar Termin 2.');
            }
        }

        // --- Hitung nominal termin ---
        $nominalTermin = $pesanan->total_harga / 2; // 50%

        // --- Alokasi proporsional per item ---
        $details = $pesanan->details;
        $totalSubtotal = $details->sum('subtotal');
        $alokasi = []; // [detail_id => jumlah_cover]

        foreach ($details as $detail) {
            if ($totalSubtotal <= 0 || $detail->total_item <= 0) {
                $alokasi[$detail->id] = 0;
                continue;
            }
            $proporsi = $detail->subtotal / $totalSubtotal;
            $nominalItem = $nominalTermin * $proporsi;
            $jumlahCover = (int) floor($nominalItem / $detail->harga_satuan);
            $sisaItem = $detail->total_item - $detail->jumlah_terbayar;
            $alokasi[$detail->id] = min($jumlahCover, $sisaItem);
        }

        // --- Koreksi rounding ---
        $totalNominalAlokasi = 0;
        foreach ($details as $detail) {
            $totalNominalAlokasi += $alokasi[$detail->id] * $detail->harga_satuan;
        }
        $selisih = $nominalTermin - $totalNominalAlokasi;
        if ($selisih > 0) {
            foreach ($details as $detail) {
                $sisaItem = $detail->total_item - $detail->jumlah_terbayar - $alokasi[$detail->id];
                if ($sisaItem > 0 && $selisih >= $detail->harga_satuan) {
                    $tambah = (int) floor($selisih / $detail->harga_satuan);
                    $tambah = min($tambah, $sisaItem);
                    $alokasi[$detail->id] += $tambah;
                    $selisih -= $tambah * $detail->harga_satuan;
                }
            }
        }

        DB::beginTransaction();
        try {
            // Buat record pembayaran awal
            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'termin_ke' => $terminKe,
                'jumlah_bayar' => $nominalTermin,
                'tanggal_bayar' => now()->toDateString(),
                'metode_pembayaran' => 'xendit',
                'bukti_bayar' => null,
                'catatan_pelanggan' => $request->catatan_pelanggan,
                'status' => 'pending',
            ]);

            // Request ke Xendit Invoice
            $response = Http::withBasicAuth(env('XENDIT_API_KEY'), '')
                ->post('https://api.xendit.co/v2/invoices', [
                    'external_id' => 'simapes-payment-' . $pembayaran->id,
                    'amount' => (int) $nominalTermin,
                    'description' => "Pembayaran Termin {$terminKe} untuk Pesanan {$pesanan->no_pesanan}",
                    'invoice_duration' => 86400,
                    'payer_email' => Auth::user()->email,
                    'customer' => [
                        'given_names' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                    'success_redirect_url' => route('pelanggan.pesanan.show', $pesanan->id) . '?xendit_status=success',
                    'failure_redirect_url' => route('pelanggan.pesanan.show', $pesanan->id) . '?xendit_status=failure',
                ]);

            if (!$response->successful()) {
                throw new \Exception('Xendit API returned error: ' . $response->body());
            }

            $xenditId = $response->json('id');
            $xenditUrl = $response->json('invoice_url');

            // Update pembayaran dengan Xendit details
            $pembayaran->update([
                'xendit_invoice_id' => $xenditId,
                'xendit_invoice_url' => $xenditUrl,
            ]);

            // Simpan alokasi proporsional per item
            foreach ($details as $detail) {
                $jmlCover = $alokasi[$detail->id] ?? 0;
                if ($jmlCover > 0) {
                    PembayaranDetail::create([
                        'pembayaran_id' => $pembayaran->id,
                        'detail_pesanan_id' => $detail->id,
                        'jumlah_cover' => $jmlCover,
                        'nominal_cover' => $jmlCover * $detail->harga_satuan,
                    ]);
                }
            }

            DB::commit();

            return redirect($xenditUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Xendit Invoice: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran ke gateway: ' . $e->getMessage());
        }
    }
}
