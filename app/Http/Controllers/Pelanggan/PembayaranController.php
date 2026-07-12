<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Proses pengajuan pembayaran (upload bukti) oleh pelanggan.
     * Alokasi dilakukan otomatis secara proporsional per item.
     */
    public function store(Request $request, $pesananId)
    {
        // Pastikan pesanan milik pelanggan yang login
        $pesanan = Pesanan::with('details.produk')
            ->where('user_id', Auth::id())
            ->findOrFail($pesananId);

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,qris',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'catatan_pelanggan' => 'nullable|string|max:500',
            'termin_ke' => 'required|in:1,2',
        ]);

        // --- Cegah submit termin yang sudah dibayar ---
        $terminKe = (int) $request->termin_ke;
        $sudahAdaTermin = $pesanan->pembayarans()->where('termin_ke', $terminKe)->exists();
        if ($sudahAdaTermin) {
            return back()->with('error', 'Termin ' . $terminKe . ' sudah diajukan sebelumnya. Tunggu verifikasi admin.');
        }

        // --- Cegah bayar Termin 2 sebelum Termin 1 verified ---
        if ($terminKe === 2) {
            $termin1 = $pesanan->pembayarans()->where('termin_ke', 1)->where('status', 'verified')->first();
            if (!$termin1) {
                return back()->with('error', 'Termin 1 (DP) harus sudah diverifikasi admin sebelum membayar Termin 2.');
            }
        }

        // --- Hitung nominal termin ---
        $nominalTermin = $pesanan->total_harga / 2; // 50%

        // --- Alokasi proporsional per item ---
        // Dana dibagi proporsional sesuai subtotal tiap detail
        $details = $pesanan->details;
        $totalSubtotal = $details->sum('subtotal');
        $alokasi = []; // [detail_id => jumlah_cover]

        foreach ($details as $detail) {
            if ($totalSubtotal <= 0 || $detail->total_item <= 0) {
                $alokasi[$detail->id] = 0;
                continue;
            }
            // Proporsi dana yang dialokasikan ke item ini
            $proporsi = $detail->subtotal / $totalSubtotal;
            $nominalItem = $nominalTermin * $proporsi;
            // Konversi ke jumlah pcs (rounded down, minimal 0)
            $jumlahCover = (int) floor($nominalItem / $detail->harga_satuan);
            // Sisa maksimal yang belum terbayar
            $sisaItem = $detail->total_item - $detail->jumlah_terbayar;
            $alokasi[$detail->id] = min($jumlahCover, $sisaItem);
        }

        // --- Koreksi rounding: distribusikan sisa pcs ke item pertama yang masih ada sisa ---
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
            // Simpan bukti bayar
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

            // Buat record pembayaran (status pending — menunggu verifikasi admin)
            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'termin_ke' => $terminKe,
                'jumlah_bayar' => $nominalTermin,
                'tanggal_bayar' => now()->toDateString(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_bayar' => $path,
                'catatan_pelanggan' => $request->catatan_pelanggan,
                'status' => 'pending',
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

            return back()->with(
                'success',
                'Bukti pembayaran Termin ' . $terminKe . ' berhasil dikirim! ' .
                'Pembayaran Anda sedang menunggu verifikasi admin.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim pembayaran: ' . $e->getMessage());
        }
    }
}
