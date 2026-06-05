<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Services\PredictionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    public function index(Request $request)
    {
        // ── 1. Ambil Parameter Smoothing dari Form Input (Dinamis) ───────
        $alpha = (float) $request->input('alpha', 0.2);
        $beta  = (float) $request->input('beta', 0.1);
        $gamma = (float) $request->input('gamma', 0.3);

        // Validasi input desimal 0 s.d 1
        $alpha = max(0.0001, min(1.0, $alpha));
        $beta  = max(0.0001, min(1.0, $beta));
        $gamma = max(0.0001, min(1.0, $gamma));

        // ── 2. Buat Deret Waktu Historis Kontinu dari Database ────────────
        // Cari pesanan paling pertama di database
        $pesananTerlama = Pesanan::orderBy('tanggal_pesanan', 'asc')->first();
        
        if (!$pesananTerlama) {
            // Jika kosong, kirim pesan error ke view agar menampilkan warning
            return view('prediksi.index', [
                'hasData'    => false,
                'message'    => 'Belum ada data transaksi pesanan sama sekali di database. Silakan jalankan seeder untuk mengisi data simulasi.',
                'alpha'      => $alpha,
                'beta'       => $beta,
                'gamma'      => $gamma,
                'parameters' => compact('alpha', 'beta', 'gamma')
            ]);
        }

        // Tentukan batas tanggal awal (dari pesanan pertama) dan akhir (bulan saat ini)
        $tanggalMulai = Carbon::parse($pesananTerlama->tanggal_pesanan)->startOfMonth();
        $tanggalAkhir = Carbon::now()->startOfMonth();

        // Hitung berapa bulan rentangnya
        $selisihBulan = $tanggalMulai->diffInMonths($tanggalAkhir);

        // Jika data kurang dari 24 bulan, berikan warning
        if ($selisihBulan < 23) { // 23 bulan selisih = 24 data point bulan
            return view('prediksi.index', [
                'hasData'    => false,
                'message'    => 'Data pesanan di database baru tersedia selama ' . ($selisihBulan + 1) . ' bulan. Rumus musiman Holt-Winters membutuhkan minimal 24 bulan data (2 tahun penuh) untuk beroperasi secara akurat.',
                'alpha'      => $alpha,
                'beta'       => $beta,
                'gamma'      => $gamma,
                'parameters' => compact('alpha', 'beta', 'gamma')
            ]);
        }

        // Buat deret waktu bulanan tanpa ada bulan yang terlewat (kontinu)
        $historis = [];
        for ($i = 0; $i <= $selisihBulan; $i++) {
            $currentMonth = $tanggalMulai->copy()->addMonths($i);
            
            // Hitung total pesanan di bulan tersebut
            $count = Pesanan::whereYear('tanggal_pesanan', $currentMonth->year)
                ->whereMonth('tanggal_pesanan', $currentMonth->month)
                ->count();

            $historis[] = [
                'tanggal' => $currentMonth->format('Y-m'),
                'label'   => $currentMonth->isoFormat('MMM YYYY'),
                'count'   => $count
            ];
        }

        // ── 3. Jalankan Algoritma Holt-Winters ────────────────────────────
        $result = $this->predictionService->calculateHoltWinters($historis, $alpha, $beta, $gamma, 12);

        if (!$result['success']) {
            return view('prediksi.index', [
                'hasData'    => false,
                'message'    => $result['message'],
                'alpha'      => $alpha,
                'beta'       => $beta,
                'gamma'      => $gamma,
                'parameters' => compact('alpha', 'beta', 'gamma')
            ]);
        }

        // ── 4. Ekstrak Data Statistik & Evaluasi ──────────────────────────
        $prediksi = $result['prediksi'];
        $mape = $result['mape'];
        $mad = $result['mad'];

        // Cari bulan puncak prediksi
        $puncakPrediksi = null;
        $maxCount = 0;
        foreach ($prediksi as $p) {
            if ($p['count'] > $maxCount) {
                $maxCount = $p['count'];
                $puncakPrediksi = $p['label'];
            }
        }

        // Total prediksi pesanan 1 tahun ke depan
        $totalPrediksiTahunDepan = array_sum(array_column($prediksi, 'count'));
        // Rata-rata pesanan per bulan
        $rataRataPesananPrediksi = round($totalPrediksiTahunDepan / 12);

        $parameters = compact('alpha', 'beta', 'gamma');

        return view('prediksi.index', compact(
            'historis',
            'prediksi',
            'mape',
            'mad',
            'puncakPrediksi',
            'totalPrediksiTahunDepan',
            'rataRataPesananPrediksi',
            'parameters',
            'alpha',
            'beta',
            'gamma'
        ))->with('hasData', true);
    }
}
