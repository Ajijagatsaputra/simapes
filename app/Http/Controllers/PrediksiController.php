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
        $beta = (float) $request->input('beta', 0.1);
        $gamma = (float) $request->input('gamma', 0.3);

        // Validasi input desimal 0 s.d 1
        $alpha = max(0.0001, min(1.0, $alpha));
        $beta = max(0.0001, min(1.0, $beta));
        $gamma = max(0.0001, min(1.0, $gamma));

        // ── 2. Buat Deret Waktu Historis Kontinu dari Database ────────────
        // Cari pesanan paling pertama di database
        $pesananTerlama = Pesanan::orderBy('tanggal_pesanan', 'asc')->first();

        if (!$pesananTerlama) {
            // Jika kosong, kirim pesan error ke view agar menampilkan warning
            return view('prediksi.index', [
                'hasData' => false,
                'message' => 'Belum ada data transaksi pesanan sama sekali di database. Silakan jalankan seeder untuk mengisi data simulasi.',
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
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
                'hasData' => false,
                'message' => 'Data pesanan di database baru tersedia selama ' . ($selisihBulan + 1) . ' bulan. Rumus musiman Holt-Winters membutuhkan minimal 24 bulan data (2 tahun penuh) untuk beroperasi secara akurat.',
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
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
                'label' => $currentMonth->isoFormat('MMM YYYY'),
                'count' => $count
            ];
        }

        // ── 3. Jalankan Algoritma Holt-Winters ────────────────────────────
        $result = $this->predictionService->calculateHoltWinters($historis, $alpha, $beta, $gamma, 12);

        if ($result['success']) {
            \App\Models\ActivityLog::log('Melakukan simulasi prediksi pesanan (Alpha: ' . $alpha . ', Beta: ' . $beta . ', Gamma: ' . $gamma . ')', 'Prediksi');
        }

        if (!$result['success']) {
            return view('prediksi.index', [
                'hasData' => false,
                'message' => $result['message'],
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
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

        // ── 5. Kalkulasi Kebutuhan Bahan Baku (MRP) Dinamis ─────────────
        $rataRataQtyPerOrder = 20; // Rata-rata 20 pcs per transaksi pesanan
        $totalEstimasiItem = $totalPrediksiTahunDepan * $rataRataQtyPerOrder;

        // Distribusi jenis produk berdasarkan proporsi rata-rata
        $distribusiProduk = [
            'Baju Seragam OSIS SMA' => 0.40,
            'Celana Seragam Abu-Abu SMA' => 0.30,
            'Baju Seragam Pramuka' => 0.20,
            'Jas Almamater Sekolah' => 0.10,
        ];

        $mrp = [
            'kain' => [
                'nama' => 'Kain Drill & Katun',
                'satuan' => 'Meter',
                'jumlah' => 0,
                'keterangan' => 'Bahan utama baju, celana & almamater'
            ],
            'kancing' => [
                'nama' => 'Kancing Baju',
                'satuan' => 'Pcs',
                'jumlah' => 0,
                'keterangan' => 'Kancing standar seragam & jas almamater'
            ],
            'benang' => [
                'nama' => 'Benang Jahit',
                'satuan' => 'Roll',
                'jumlah' => 0,
                'keterangan' => 'Benang jahit warna senada'
            ],
            'resleting' => [
                'nama' => 'Resleting Celana',
                'satuan' => 'Pcs',
                'jumlah' => 0,
                'keterangan' => 'Resleting celana abu-abu'
            ],
        ];

        foreach ($distribusiProduk as $namaProduk => $porsi) {
            $qtyProduk = round($totalEstimasiItem * $porsi);

            if ($namaProduk === 'Baju Seragam OSIS SMA') {
                $mrp['kain']['jumlah'] += $qtyProduk * 1.5;
                $mrp['kancing']['jumlah'] += $qtyProduk * 6;
                $mrp['benang']['jumlah'] += $qtyProduk * 0.2;
            } elseif ($namaProduk === 'Celana Seragam Abu-Abu SMA') {
                $mrp['kain']['jumlah'] += $qtyProduk * 1.3;
                $mrp['kancing']['jumlah'] += $qtyProduk * 1;
                $mrp['benang']['jumlah'] += $qtyProduk * 0.2;
                $mrp['resleting']['jumlah'] += $qtyProduk * 1;
            } elseif ($namaProduk === 'Baju Seragam Pramuka') {
                $mrp['kain']['jumlah'] += $qtyProduk * 1.5;
                $mrp['kancing']['jumlah'] += $qtyProduk * 6;
                $mrp['benang']['jumlah'] += $qtyProduk * 0.2;
            } elseif ($namaProduk === 'Jas Almamater Sekolah') {
                $mrp['kain']['jumlah'] += $qtyProduk * 2.0;
                $mrp['kancing']['jumlah'] += $qtyProduk * 4;
                $mrp['benang']['jumlah'] += $qtyProduk * 0.3;
            }
        }

        foreach ($mrp as $key => $val) {
            $mrp[$key]['jumlah'] = ceil($val['jumlah']);
        }

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
            'gamma',
            'mrp'
        ))->with('hasData', true);
    }
}
