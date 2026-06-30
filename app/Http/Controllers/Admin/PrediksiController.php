<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $alpha = (float) $request->input('alpha', 0.2);
        $beta = (float) $request->input('beta', 0.1);
        $gamma = (float) $request->input('gamma', 0.3);

        $alpha = max(0.0001, min(1.0, $alpha));
        $beta = max(0.0001, min(1.0, $beta));
        $gamma = max(0.0001, min(1.0, $gamma));

        $pesananTerlama = Pesanan::orderBy('tanggal_pesanan', 'asc')->first();

        if (!$pesananTerlama) {
            return view('admin.prediksi.index', [
                'hasData' => false,
                'message' => 'Belum ada data transaksi pesanan sama sekali di database.',
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
                'parameters' => compact('alpha', 'beta', 'gamma')
            ]);
        }

        $tanggalMulai = Carbon::parse($pesananTerlama->tanggal_pesanan)->startOfMonth();
        $tanggalAkhir = Carbon::now()->startOfMonth();
        $selisihBulan = $tanggalMulai->diffInMonths($tanggalAkhir);

        if ($selisihBulan < 23) {
            return view('admin.prediksi.index', [
                'hasData' => false,
                'message' => 'Data pesanan baru tersedia selama ' . ($selisihBulan + 1) . ' bulan. Butuh minimal 24 bulan.',
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
                'parameters' => compact('alpha', 'beta', 'gamma')
            ]);
        }

        $historis = [];
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

        if ($request->has('optimize')) {
            $opt = $this->predictionService->findOptimalParameters($historis);
            return redirect()->route('admin.prediksi.index', [
                'alpha' => $opt['alpha'],
                'beta' => $opt['beta'],
                'gamma' => $opt['gamma'],
                'optimized' => 1
            ]);
        }

        $result = $this->predictionService->calculateHoltWinters($historis, $alpha, $beta, $gamma, 12);
        $sesResult = $this->predictionService->calculateSES($historis, $alpha, 12);

        if ($result['success']) {
            \App\Models\ActivityLog::log('Simulasi prediksi pesanan (Alpha:' . $alpha . ', Beta:' . $beta . ', Gamma:' . $gamma . ')', 'Prediksi');
        }

        if (!$result['success']) {
            return view('admin.prediksi.index', [
                'hasData' => false,
                'message' => $result['message'],
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
                'parameters' => compact('alpha', 'beta', 'gamma')
            ]);
        }

        $prediksi = $result['prediksi'];
        $mape = $result['mape'];
        $mad = $result['mad'];
        $historis_fitted = $result['historis_fitted'];
        $sesMape = $sesResult['mape'];

        $puncakPrediksi = null;
        $maxCount = 0;
        foreach ($prediksi as $p) {
            if ($p['count'] > $maxCount) {
                $maxCount = $p['count'];
                $puncakPrediksi = $p['label'];
            }
        }

        $totalPrediksiTahunDepan = array_sum(array_column($prediksi, 'count'));
        $rataRataPesananPrediksi = round($totalPrediksiTahunDepan / 12);
        $rataRataQtyPerOrder = 20;
        $totalEstimasiItem = $totalPrediksiTahunDepan * $rataRataQtyPerOrder;

        $distribusiProduk = [
            'Baju Seragam OSIS SMA' => 0.40,
            'Celana Seragam Abu-Abu SMA' => 0.30,
            'Baju Seragam Pramuka' => 0.20,
            'Jas Almamater Sekolah' => 0.10,
        ];

        $mrp = [
            'kain' => ['nama' => 'Kain Drill & Katun', 'satuan' => 'Meter', 'jumlah' => 0, 'keterangan' => 'Bahan utama baju, celana & almamater'],
            'kancing' => ['nama' => 'Kancing Baju', 'satuan' => 'Pcs', 'jumlah' => 0, 'keterangan' => 'Kancing standar seragam & jas almamater'],
            'benang' => ['nama' => 'Benang Jahit', 'satuan' => 'Roll', 'jumlah' => 0, 'keterangan' => 'Benang jahit warna senada'],
            'resleting' => ['nama' => 'Resleting Celana', 'satuan' => 'Pcs', 'jumlah' => 0, 'keterangan' => 'Resleting celana abu-abu'],
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

        $leadTimes = ['kain' => 5, 'kancing' => 3, 'benang' => 2, 'resleting' => 4];
        foreach ($mrp as $key => $val) {
            $mrp[$key]['jumlah'] = ceil($val['jumlah']);
            $lt = $leadTimes[$key] ?? 3;
            $mrp[$key]['lead_time'] = $lt;
            $avgDaily = $mrp[$key]['jumlah'] / 360;
            $safetyStock = ceil(0.5 * $avgDaily * $lt);
            $rop = ceil(($avgDaily * $lt) + $safetyStock);
            $mrp[$key]['safety_stock'] = $safetyStock;
            $mrp[$key]['rop'] = $rop;
        }

        $suppliers = \App\Models\Supplier::all();
        $rekomendasiSupplier = [];
        foreach ($mrp as $key => $val) {
            $rekomendasiSupplier[$key] = $suppliers->filter(function ($s) use ($key) {
                return is_array($s->kategori_bahan) && in_array($key, $s->kategori_bahan);
            });
        }

        $parameters = compact('alpha', 'beta', 'gamma');

        return view('admin.prediksi.index', compact(
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
            'mrp',
            'rekomendasiSupplier',
            'sesMape',
            'historis_fitted'
        ))->with('hasData', true);
    }

    public function printPo(Request $request)
    {
        $supplierId = $request->query('supplier_id');
        $supplier = \App\Models\Supplier::findOrFail($supplierId);
        $bahan = $request->query('bahan');
        $jumlah = $request->query('jumlah');
        $satuan = $request->query('satuan');
        $noPo = 'PO/' . Carbon::now()->format('Ymd') . '/' . strtoupper(bin2hex(random_bytes(2)));

        return view('admin.prediksi.print_po', compact('supplier', 'bahan', 'jumlah', 'satuan', 'noPo'));
    }
}
