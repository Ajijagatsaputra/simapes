<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\ActivityLog;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::query();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pesanan', $request->tanggal);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_pesanan', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        $totalPesanan = (clone $query)->count();
        $totalPending = (clone $query)->where('status', 'pending')->count();
        $totalDiproses = (clone $query)->where('status', 'diproses')->count();
        $totalDikerjakan = (clone $query)->where('status', 'dikerjakan')->count();
        $totalSelesai = (clone $query)->where('status', 'selesai')->count();
        $totalBatal = (clone $query)->where('status', 'batal')->count();

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pesanan = $query->with(['user', 'details.produk', 'progresProduksis'])->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact(
            'pesanan',
            'totalPesanan',
            'totalPending',
            'totalDiproses',
            'totalDikerjakan',
            'totalSelesai',
            'totalBatal'
        ));
    }



    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,diproses,dikerjakan,selesai,batal']);
        $pesanan = Pesanan::findOrFail($id);

        // Validasi: Pembayaran DP & Akses Pengerjaan (diproses, dikerjakan, selesai)
        if (in_array($request->status, ['diproses', 'dikerjakan', 'selesai']) && ($pesanan->status_pembayaran ?? 'belum_bayar') === 'belum_bayar') {
            $msg = 'Status pengerjaan tidak dapat diubah ke ' . ucfirst($request->status) . ' karena pelanggan belum membayar DP.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $msg,
                    'current_status' => $pesanan->status,
                ], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $pesanan->update(['status' => $request->status]);

        // ── Simpan log status dengan timestamp ──
        StatusLog::create([
            'pesanan_id' => $pesanan->id,
            'status' => $request->status,
            'label' => StatusLog::LABELS[$request->status] ?? ucfirst($request->status),
            'catatan' => $request->input('catatan'),
            'created_at' => now(),
        ]);

        if ($request->status === 'batal') {
            ActivityLog::log('Membatalkan/menolak pesanan: ' . $pesanan->no_pesanan, 'Pesanan', $pesanan->id);
        } else {
            ActivityLog::log('Mengubah status pesanan ' . $pesanan->no_pesanan . ' menjadi ' . $request->status, 'Pesanan', $pesanan->id);
        }

        // Return JSON jika AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pesanan ' . $pesanan->no_pesanan . ' berhasil diubah ke ' . $request->status . '.',
                'status' => $request->status,
            ]);
        }

        return redirect()->route('admin.pesanan.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function nota($id)
    {
        $pesanan = Pesanan::with(['user', 'details.produk'])->findOrFail($id);
        return view('admin.pesanan.nota', compact('pesanan'));
    }
}
