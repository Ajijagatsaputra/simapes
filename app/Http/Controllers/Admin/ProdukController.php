<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->paginate(10);
        $totalProduk = Produk::count();

        return view('admin.produk.index', compact('produk', 'totalProduk'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_seragam' => 'required|in:TK,SD,SMP,SMA/SMK,Umum,Atribut',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/produk');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            @chmod($destinationPath . '/' . $filename, 0777);
            $validated['gambar'] = 'uploads/produk/' . $filename;
        }

        $produk = Produk::create($validated);
        ActivityLog::log('Menambahkan produk baru: ' . $produk->nama_produk, 'Produk', $produk->id);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'jenis_seragam' => 'required|in:TK,SD,SMP,SMA/SMK,Umum,Atribut',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $produk = Produk::findOrFail($id);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/produk');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            @chmod($destinationPath . '/' . $filename, 0777);

            // Hapus gambar lama jika ada
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                @unlink(public_path($produk->gambar));
            }

            $validated['gambar'] = 'uploads/produk/' . $filename;
        }

        $produk->update($validated);
        ActivityLog::log('Memperbarui data produk: ' . $produk->nama_produk, 'Produk', $produk->id);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $name = $produk->nama_produk;

        // Hapus gambar jika ada
        if ($produk->gambar && file_exists(public_path($produk->gambar))) {
            @unlink(public_path($produk->gambar));
        }

        $produk->delete();
        ActivityLog::log('Menghapus produk: ' . $name, 'Produk', $id);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
