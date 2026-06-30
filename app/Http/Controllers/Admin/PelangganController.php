<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    /**
     * Tampilkan daftar pelanggan (admin view).
     */
    public function index()
    {
        $pelanggan = User::where('role', 'pelanggan')->orderBy('name')->paginate(10);
        $totalPelanggan = User::where('role', 'pelanggan')->count();

        return view('admin.pelanggan.index', compact('pelanggan', 'totalPelanggan'));
    }

    /**
     * Simpan pelanggan baru (diinput admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
        ]);

        $pelanggan = User::create([
            'name' => $validated['name'],
            'role' => 'pelanggan',
            'email' => $validated['email'],
            'password' => Hash::make('12345678'), // Default password
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'nama_sekolah' => $validated['nama_sekolah'] ?? null,
        ]);

        ActivityLog::log('Menambahkan pelanggan baru: ' . $pelanggan->name, 'User', $pelanggan->id);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan. Password default: 12345678');
    }

    /**
     * Perbarui data pelanggan.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
        ]);

        $pelanggan = User::where('role', 'pelanggan')->findOrFail($id);
        $pelanggan->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'nama_sekolah' => $validated['nama_sekolah'] ?? null,
        ]);

        ActivityLog::log('Memperbarui data pelanggan: ' . $pelanggan->name, 'User', $pelanggan->id);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus pelanggan.
     */
    public function destroy($id)
    {
        $pelanggan = User::where('role', 'pelanggan')->findOrFail($id);
        $name = $pelanggan->name;
        $pelanggan->delete();

        ActivityLog::log('Menghapus pelanggan: ' . $name, 'User', $id);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Hapus beberapa pelanggan sekaligus.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('admin.pelanggan.index')
                ->with('error', 'Tidak ada pelanggan yang dipilih.');
        }

        $count = User::where('role', 'pelanggan')->whereIn('id', $ids)->delete();

        ActivityLog::log("Menghapus {$count} pelanggan secara bulk", 'User', null);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', "{$count} pelanggan berhasil dihapus.");
    }
}
