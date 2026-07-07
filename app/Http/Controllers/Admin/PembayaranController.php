<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /** Halaman detail pesanan + pembayaran */
    public function show($pesananId)
    {
        $pesanan = Pesanan::with([
            'user',
            'details.produk',
            'pembayarans' => fn($q) => $q->orderBy('termin_ke'),
            'pembayarans.details.detailPesanan.produk',
            'pembayarans.verifier',
        ])->findOrFail($pesananId);

        return view('admin.pesanan.pembayaran', compact('pesanan'));
    }

    /** Catat pembayaran baru (DP / termin) */
    public function store(Request $request, $pesananId)
    {
        $pesanan = Pesanan::with('details.produk')->findOrFail($pesananId);

        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|in:transfer,tunai,qris',
            'catatan' => 'nullable|string|max:500',
            'alokasi' => 'required|array',
            'alokasi.*' => 'required|integer|min:0',
        ]);

        // Validasi: jumlah bayar tidak boleh melebihi sisa tagihan
        $sisaTagihan = $pesanan->total_harga - $pesanan->total_terbayar;
        if ($request->jumlah_bayar > $sisaTagihan) {
            return redirect()->back()->with('error', 'Jumlah bayar melebihi sisa tagihan (Rp ' . number_format($sisaTagihan, 0, ',', '.') . ').');
        }

        // Validasi: total nominal alokasi harus = jumlah_bayar
        $totalAlokasi = 0;
        foreach ($request->alokasi as $detailId => $jumlahCover) {
            if ($jumlahCover > 0) {
                $detail = $pesanan->details->firstWhere('id', $detailId);
                if ($detail) {
                    $totalAlokasi += $jumlahCover * $detail->harga_satuan;
                }
            }
        }

        // Toleransi rounding
        if (abs($totalAlokasi - $request->jumlah_bayar) > 1) {
            return redirect()->back()->with('error', 'Total alokasi item (Rp ' . number_format($totalAlokasi, 0, ',', '.') . ') tidak sesuai dengan jumlah pembayaran (Rp ' . number_format($request->jumlah_bayar, 0, ',', '.') . ').');
        }

        DB::beginTransaction();
        try {
            // Hitung termin ke berapa
            $terminKe = $pesanan->pembayarans()->count() + 1;

            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'termin_ke' => $terminKe,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tanggal_bayar' => $request->tanggal_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan,
                'status' => 'verified', // Admin langsung verified
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Simpan alokasi per item
            foreach ($request->alokasi as $detailId => $jumlahCover) {
                if ($jumlahCover > 0) {
                    $detail = $pesanan->details->firstWhere('id', $detailId);
                    if ($detail) {
                        PembayaranDetail::create([
                            'pembayaran_id' => $pembayaran->id,
                            'detail_pesanan_id' => $detailId,
                            'jumlah_cover' => $jumlahCover,
                            'nominal_cover' => $jumlahCover * $detail->harga_satuan,
                        ]);
                    }
                }
            }

            // Recalculate
            $pesanan->recalculatePembayaran();
            $pesanan->recalculateItemCoverage();

            ActivityLog::log(
                'Mencatat pembayaran termin ' . $terminKe . ' sebesar Rp ' . number_format($request->jumlah_bayar, 0, ',', '.') . ' untuk pesanan ' . $pesanan->no_pesanan,
                'Pembayaran',
                $pesanan->id
            );

            DB::commit();
            return redirect()->route('admin.pesanan.pembayaran', $pesanan->id)
                ->with('success', 'Pembayaran termin ' . $terminKe . ' berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    /** Hapus pembayaran */
    public function destroy($pesananId, $pembayaranId)
    {
        $pesanan = Pesanan::with('details')->findOrFail($pesananId);
        $pembayaran = Pembayaran::where('pesanan_id', $pesananId)->findOrFail($pembayaranId);

        DB::beginTransaction();
        try {
            $terminKe = $pembayaran->termin_ke;
            $pembayaran->delete();

            // Re-number termin
            $pesanan->pembayarans()->orderBy('termin_ke')->get()->each(function ($p, $index) {
                $p->update(['termin_ke' => $index + 1]);
            });

            $pesanan->recalculatePembayaran();
            $pesanan->recalculateItemCoverage();

            ActivityLog::log(
                'Menghapus pembayaran termin ' . $terminKe . ' pesanan ' . $pesanan->no_pesanan,
                'Pembayaran',
                $pesanan->id
            );

            DB::commit();
            return redirect()->route('admin.pesanan.pembayaran', $pesanan->id)
                ->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
