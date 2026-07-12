<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;

use App\Services\PredictionService;

class DashboardController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

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

        // ── Ambil Data Historis untuk Prediksi ──────────────────────
        $historis = [];
        $hasPrediction = false;

        // 1. Cek data upload di session
        if (session()->has('uploaded_prediction_data')) {
            $historis = session('uploaded_prediction_data');
        } else {
            // Cek data di database
            $pesananTerlama = Pesanan::orderBy('tanggal_pesanan', 'asc')->first();

            if ($pesananTerlama) {
                $tanggalMulai = Carbon::parse($pesananTerlama->tanggal_pesanan)->startOfMonth();
                $tanggalAkhir = Carbon::now()->startOfMonth();
                $selisihBulan = $tanggalMulai->diffInMonths($tanggalAkhir);

                if ($selisihBulan >= 23) {
                    for ($i = 0; $i <= $selisihBulan; $i++) {
                        $currentMonth = $tanggalMulai->copy()->addMonths($i);
                        $count = Pesanan::whereYear('tanggal_pesanan', $currentMonth->year)
                            ->whereMonth('tanggal_pesanan', $currentMonth->month)
                            ->count();
                        $historis[] = [
                            'tanggal' => $currentMonth->format('Y-m'),
                            'label' => $currentMonth->isoFormat('MMM YYYY'),
                            'count' => $count
                        ];
                    }
                }
            }
        }

        // 2. Jalankan perhitungan Holt-Winters (12 bulan ke depan) jika data cukup
        $chartPrediksi = [];
        if (count($historis) >= 24) {
            // Gunakan parameter default sistem (Alpha 0.2, Beta 0.1, Gamma 0.3)
            $result = $this->predictionService->calculateHoltWinters($historis, 0.2, 0.1, 0.3, 12);
            if ($result['success']) {
                $chartPrediksi = $result['prediksi'];
                $hasPrediction = true;
            }
        }

        // Fallback jika tidak ada data prediksi
        if (!$hasPrediction) {
            $chartPrediksi = [];
            for ($i = 0; $i < 12; $i++) {
                $bulan = Carbon::now()->addMonths($i + 1);
                $chartPrediksi[] = [
                    'label' => $bulan->isoFormat('MMM YYYY'),
                    'count' => 0,
                ];
            }
        }

        // ── Statistik Kartu ─────────────────────────────────────────
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalProduk = Produk::count();
        $totalPesanan = Pesanan::count();
        $pesananDiproses = Pesanan::whereIn('status', ['diproses', 'dikerjakan'])->count();
        $pesananSelesai = Pesanan::where('status', 'selesai')->count();
        $pesananTerbaru = Pesanan::with('user')->latest()->take(5)->get();
        $activityLogs = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'chartPesanan',
            'chartPrediksi',
            'hasPrediction',
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
