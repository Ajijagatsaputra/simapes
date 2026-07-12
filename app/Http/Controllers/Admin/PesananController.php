<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\ActivityLog;
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

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $pesanan = $query->with(['user', 'details.produk'])->latest()->paginate(10)->withQueryString();
        $pelanggan = User::where('role', 'pelanggan')->orderBy('name')->get();
        $produks = Produk::orderBy('nama_produk')->get();

        return view('admin.pesanan.index', compact(
            'pesanan',
            'totalPesanan',
            'totalPending',
            'totalDiproses',
            'totalDikerjakan',
            'totalSelesai',
            'pelanggan',
            'produks'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pesanan' => 'required|date',
            'status' => 'required|in:pending,diproses,dikerjakan,selesai',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.ukuran' => 'required|in:S,M,L,XL,XXL,3XL,4XL,5XL',
            'items.*.total_item' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::create([
                'no_pesanan' => Pesanan::generateNoPesanan(),
                'user_id' => $request->user_id,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'status' => $request->status,
                'total_harga' => 0,
            ]);

            $totalHarga = 0;
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subtotal = $produk->harga * $item['total_item'];
                $totalHarga += $subtotal;

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk_id'],
                    'ukuran' => $item['ukuran'],
                    'harga_satuan' => $produk->harga,
                    'total_item' => $item['total_item'],
                    'subtotal' => $subtotal,
                ]);
            }

            $pesanan->update(['total_harga' => $totalHarga, 'sisa_tagihan' => max(0, $totalHarga - $pesanan->total_terbayar)]);
            ActivityLog::log('Membuat pesanan baru: ' . $pesanan->no_pesanan, 'Pesanan', $pesanan->id);

            DB::commit();
            return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,diproses,dikerjakan,selesai']);
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => $request->status]);
        ActivityLog::log('Mengubah status pesanan ' . $pesanan->no_pesanan . ' menjadi ' . $request->status, 'Pesanan', $pesanan->id);

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

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $no = $pesanan->no_pesanan;
        $pesanan->delete();
        ActivityLog::log('Menghapus pesanan: ' . $no, 'Pesanan', $id);

        return redirect()->route('admin.pesanan.index')->with('success', "Pesanan {$no} berhasil dihapus.");
    }

    public function nota($id)
    {
        $pesanan = Pesanan::with(['user', 'details.produk'])->findOrFail($id);
        return view('admin.pesanan.nota', compact('pesanan'));
    }
}
