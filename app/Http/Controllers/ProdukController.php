<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->paginate(10);
        $totalProduk = Produk::count();

        return view('produk.index', compact('produk', 'totalProduk'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_seragam' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
        ]);

        $produk = Produk::create($validated);

        ActivityLog::log('Menambahkan produk baru: ' . $produk->nama_produk, 'Produk', $produk->id);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Perbarui data produk.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_seragam' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($validated);

        ActivityLog::log('Memperbarui data produk: ' . $produk->nama_produk, 'Produk', $produk->id);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $name = $produk->nama_produk;
        $produk->delete();

        ActivityLog::log('Menghapus produk: ' . $name, 'Produk', $id);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
