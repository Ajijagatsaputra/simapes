<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalPesanan = Pesanan::where('user_id', $user->id)->count();
        $pesananDiproses = Pesanan::where('user_id', $user->id)->where('status', 'diproses')->count();
        $pesananDikerjakan = Pesanan::where('user_id', $user->id)->where('status', 'dikerjakan')->count();
        $pesananSelesai = Pesanan::where('user_id', $user->id)->where('status', 'selesai')->count();
        $pesananTerbaru = Pesanan::where('user_id', $user->id)->with('details.produk')->latest()->take(5)->get();
        $totalProduk = Produk::count();

        return view('pelanggan.dashboard', compact(
            'totalPesanan',
            'pesananDiproses',
            'pesananDikerjakan',
            'pesananSelesai',
            'pesananTerbaru',
            'totalProduk',
        ));
    }
}
