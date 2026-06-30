<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama_supplier')->paginate(10);
        $totalSupplier = Supplier::count();

        return view('admin.supplier.index', compact('supplier', 'totalSupplier'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kategori_bahan' => 'required|array',
            'kategori_bahan.*' => 'string|in:kain,kancing,benang,resleting',
            'deskripsi' => 'nullable|string',
        ]);

        $supplier = Supplier::create([
            'nama_supplier' => $validated['nama_supplier'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kategori_bahan' => $validated['kategori_bahan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        ActivityLog::log('Menambahkan supplier baru: ' . $supplier->nama_supplier, 'Supplier', $supplier->id);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kategori_bahan' => 'required|array',
            'kategori_bahan.*' => 'string|in:kain,kancing,benang,resleting',
            'deskripsi' => 'nullable|string',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'nama_supplier' => $validated['nama_supplier'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kategori_bahan' => $validated['kategori_bahan'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        ActivityLog::log('Memperbarui data supplier: ' . $supplier->nama_supplier, 'Supplier', $supplier->id);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $name = $supplier->nama_supplier;
        $supplier->delete();
        ActivityLog::log('Menghapus data supplier: ' . $name, 'Supplier', $id);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
