<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        // Load pesanan beserta detail item dan relasi produknya
        $query = Pesanan::with(['user', 'details.produk'])->latest();

        // Filter status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

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

        $pesanan = $query->paginate(10)->withQueryString();
        $totalPesanan = Pesanan::count();
        $pelanggan = User::orderBy('name')->get();
        $produks = Produk::orderBy('nama_produk')->get();

        return view('pesanan.index', compact('pesanan', 'totalPesanan', 'pelanggan', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pesanan' => 'required|date',
            'status' => 'required|in:diproses,dikerjakan,selesai',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.ukuran' => 'required|in:S,M,L,XL,XXL',
            'items.*.total_item' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat Header Pesanan
            $pesanan = Pesanan::create([
                'no_pesanan' => Pesanan::generateNoPesanan(),
                'user_id' => $request->user_id,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'status' => $request->status,
                'total_harga' => 0, // di-update setelah subtotal terhitung
            ]);

            $totalHarga = 0;

            // 2. Simpan Detail Pesanan
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

            // 3. Update Total Harga di Header
            $pesanan->update(['total_harga' => $totalHarga]);

            DB::commit();
            return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pesanan' => 'required|date',
            'status' => 'required|in:diproses,dikerjakan,selesai',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.ukuran' => 'required|in:S,M,L,XL,XXL',
            'items.*.total_item' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($id);

            // 1. Update Header Info
            $pesanan->update([
                'user_id' => $request->user_id,
                'tanggal_pesanan' => $request->tanggal_pesanan,
                'status' => $request->status,
            ]);

            // 2. Hapus detail lama untuk diganti dengan detail baru
            $pesanan->details()->delete();

            $totalHarga = 0;

            // 3. Masukkan detail yang diperbarui
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

            // 4. Update Total Harga akhir
            $pesanan->update(['total_harga' => $totalHarga]);

            DB::commit();
            return redirect()->route('pesanan.index')->with('success', 'Pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:diproses,dikerjakan,selesai']);
        Pesanan::findOrFail($id)->update(['status' => $request->status]);

        return redirect()->route('pesanan.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $no = $pesanan->no_pesanan;
        $pesanan->delete();

        return redirect()->route('pesanan.index')->with('success', "Pesanan {$no} berhasil dihapus.");
    }
}
