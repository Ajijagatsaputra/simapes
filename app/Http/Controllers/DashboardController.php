<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Grafik Jumlah Pesanan (12 bulan terakhir) ──────────────
        $chartPesanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $count = Pesanan::whereYear('tanggal_pesanan', $bulan->year)
                        ->whereMonth('tanggal_pesanan', $bulan->month)
                        ->count();
            $chartPesanan[] = [
                'label' => $bulan->isoFormat('MMM YYYY'),
                'count' => $count,
            ];
        }

        // ── Grafik Prediksi (6 bulan ke depan) ─────────────────────
        $chartPrediksi = [];
        for ($i = 0; $i < 6; $i++) {
            $bulan = Carbon::now()->addMonths($i + 1);
            // TODO: ganti dengan hasil model Prediksi nyata jika sudah diimplementasikan
            $count = rand(80, 130);
            $chartPrediksi[] = [
                'label' => $bulan->isoFormat('MMM YYYY'),
                'count' => $count,
            ];
        }

        // ── Statistik Kartu ─────────────────────────────────────────
        $totalPelanggan = User::count();
        $totalProduk = Produk::count();
        $totalPesanan = Pesanan::count();
        $pesananDiproses = Pesanan::whereIn('status', ['diproses', 'dikerjakan'])->count();
        $pesananSelesai = Pesanan::where('status', 'selesai')->count();
        $pesananTerbaru = Pesanan::with('user')->latest()->take(5)->get();
        $activityLogs = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'chartPesanan',
            'chartPrediksi',
            'totalPelanggan',
            'totalProduk',
            'totalPesanan',
            'pesananDiproses',
            'pesananSelesai',
            'pesananTerbaru',
            'activityLogs',
        ));
    }
}
