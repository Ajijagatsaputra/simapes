<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // Load pesanan
        $query = Pesanan::query();

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pesanan', $request->tanggal);
        }

        // Filter search
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_pesanan', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        // Hitung total status berdasarkan filter pencarian & tanggal (sebelum filter status)
        $totalPesanan = (clone $query)->count();
        $totalDiproses = (clone $query)->where('status', 'diproses')->count();
        $totalDikerjakan = (clone $query)->where('status', 'dikerjakan')->count();
        $totalSelesai = (clone $query)->where('status', 'selesai')->count();

        // Filter status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pesanan = $query->with(['user', 'details.produk'])->latest()->paginate(10)->withQueryString();

        return view('pesanan.index', compact(
            'pesanan',
            'totalPesanan',
            'totalDiproses',
            'totalDikerjakan',
            'totalSelesai'
        ));
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,diproses,dikerjakan,selesai,batal']);
        $pesanan = Pesanan::findOrFail($id);

        if (in_array($request->status, ['diproses', 'dikerjakan', 'selesai']) && ($pesanan->status_pembayaran ?? 'belum_bayar') === 'belum_bayar') {
            return redirect()->back()->with('error', 'Status pengerjaan tidak dapat diubah ke ' . ucfirst($request->status) . ' karena pelanggan belum membayar DP.');
        }

        $pesanan->update(['status' => $request->status]);

        if ($request->status === 'batal') {
            ActivityLog::log('Membatalkan/menolak pesanan: ' . $pesanan->no_pesanan, 'Pesanan', $pesanan->id);
        } else {
            ActivityLog::log('Mengubah status pesanan ' . $pesanan->no_pesanan . ' menjadi ' . $request->status, 'Pesanan', $pesanan->id);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function nota($id)
    {
        $pesanan = Pesanan::with(['user', 'details.produk'])->findOrFail($id);
        return view('pesanan.nota', compact('pesanan'));
    }
}
