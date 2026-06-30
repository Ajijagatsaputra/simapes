<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('pelanggan.profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'no_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nama_sekolah' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? $user->no_whatsapp,
            'alamat' => $validated['alamat'] ?? $user->alamat,
            'nama_sekolah' => $validated['nama_sekolah'] ?? $user->nama_sekolah,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('pelanggan.profil.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
