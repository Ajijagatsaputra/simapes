<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $riwayat = Pesanan::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->with('details.produk')
            ->latest()
            ->paginate(10);

        return view('pelanggan.riwayat', compact('riwayat'));
    }
}
