<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Tampilkan daftar pelanggan.
     */
    public function index()
    {
        $pelanggan = User::where('email', '!=', 'admin@gmail.com')->orderBy('name')->paginate(10);
        $totalPelanggan = User::where('email', '!=', 'admin@gmail.com')->count();

        return view('pelanggan.index', compact('pelanggan', 'totalPelanggan'));
    }

    /**
     * Simpan pelanggan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['name'] . '_' . time() . '@pelanggan.local',
            'password' => bcrypt('password'),
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'nama_sekolah' => $validated['nama_sekolah'] ?? null,
        ]);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Perbarui data pelanggan.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
        ]);

        $pelanggan = User::findOrFail($id);
        $pelanggan->update([
            'name' => $validated['name'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'nama_sekolah' => $validated['nama_sekolah'] ?? null,
        ]);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus pelanggan.
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
