<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\ActivityLog;
use App\Services\PredictionService;
use App\Services\AiPredictionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    protected $predictionService;
    protected $aiPredictionService;

    public function __construct(PredictionService $predictionService, AiPredictionService $aiPredictionService)
    {
        $this->predictionService = $predictionService;
        $this->aiPredictionService = $aiPredictionService;
    }

    /**
     * Tampilkan halaman utama prediksi (dari database atau data upload session)
     */
    public function index(Request $request)
    {
        if ($request->has('optimize')) {
            $data = $this->getPredictionData($request);
            if ($data['hasData']) {
                $opt = $this->predictionService->findOptimalParameters($data['historis']);
                return redirect()->route('admin.prediksi.index', [
                    'alpha' => $opt['alpha'],
                    'beta' => $opt['beta'],
                    'gamma' => $opt['gamma'],
                    'optimized' => 1
                ]);
            }
        }

        $data = $this->getPredictionData($request);

        if (!$data['hasData']) {
            return view('admin.prediksi.index', [
                'hasData' => false,
                'message' => $data['message'] ?? 'Belum ada data historis yang tersedia.',
                'alpha' => $data['alpha'],
                'beta' => $data['beta'],
                'gamma' => $data['gamma'],
                'parameters' => [
                    'alpha' => $data['alpha'],
                    'beta' => $data['beta'],
                    'gamma' => $data['gamma']
                ]
            ]);
        }

        ActivityLog::log(
            'Simulasi prediksi pesanan (Alpha:' . $data['alpha'] . ', Beta:' . $data['beta'] . ', Gamma:' . $data['gamma'] . ')' .
            ($data['uploadedFilename'] ? ' [File: ' . $data['uploadedFilename'] . ']' : ''),
            'Prediksi'
        );

        $parameters = [
            'alpha' => $data['alpha'],
            'beta' => $data['beta'],
            'gamma' => $data['gamma']
        ];

        return view('admin.prediksi.index', array_merge($data, compact('parameters')));
    }

    /**
     * Hitung peramalan dan MRP menggunakan helper agar modular
     */
    protected function getPredictionData(Request $request)
    {
        $alpha = (float) $request->input('alpha', 0.2);
        $beta = (float) $request->input('beta', 0.1);
        $gamma = (float) $request->input('gamma', 0.3);

        $alpha = max(0.0001, min(1.0, $alpha));
        $beta = max(0.0001, min(1.0, $beta));
        $gamma = max(0.0001, min(1.0, $gamma));

        $historis = [];
        $uploadedFilename = null;

        // 1. Cek data upload di session
        if (session()->has('uploaded_prediction_data')) {
            $historis = session('uploaded_prediction_data');
            $uploadedFilename = session('uploaded_prediction_filename');
        } else {
            // Cek data di database
            $pesananTerlama = Pesanan::orderBy('tanggal_pesanan', 'asc')->first();

            if (!$pesananTerlama) {
                return [
                    'hasData' => false,
                    'message' => 'Belum ada data transaksi pesanan sama sekali di database.',
                    'alpha' => $alpha,
                    'beta' => $beta,
                    'gamma' => $gamma,
                ];
            }

            $tanggalMulai = Carbon::parse($pesananTerlama->tanggal_pesanan)->startOfMonth();
            $tanggalAkhir = Carbon::now()->startOfMonth();
            $selisihBulan = $tanggalMulai->diffInMonths($tanggalAkhir);

            if ($selisihBulan < 23) {
                return [
                    'hasData' => false,
                    'message' => 'Data pesanan baru tersedia selama ' . ($selisihBulan + 1) . ' bulan. Butuh minimal 24 bulan.',
                    'alpha' => $alpha,
                    'beta' => $beta,
                    'gamma' => $gamma,
                ];
            }

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

        // Jalankan perhitungan Holt-Winters & SES
        $result = $this->predictionService->calculateHoltWinters($historis, $alpha, $beta, $gamma, 12);
        $sesResult = $this->predictionService->calculateSES($historis, $alpha, 12);

        if (!$result['success']) {
            return [
                'hasData' => false,
                'message' => $result['message'],
                'alpha' => $alpha,
                'beta' => $beta,
                'gamma' => $gamma,
            ];
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

        $suppliers = Supplier::all();
        $rekomendasiSupplier = [];
        foreach ($mrp as $key => $val) {
            $rekomendasiSupplier[$key] = $suppliers->filter(function ($s) use ($key) {
                return is_array($s->kategori_bahan) && in_array($key, $s->kategori_bahan);
            });
        }

        return [
            'hasData' => true,
            'historis' => $historis,
            'prediksi' => $prediksi,
            'mape' => $mape,
            'mad' => $mad,
            'puncakPrediksi' => $puncakPrediksi,
            'totalPrediksiTahunDepan' => $totalPrediksiTahunDepan,
            'rataRataPesananPrediksi' => $rataRataPesananPrediksi,
            'mrp' => $mrp,
            'rekomendasiSupplier' => $rekomendasiSupplier,
            'sesMape' => $sesMape,
            'historis_fitted' => $historis_fitted,
            'alpha' => $alpha,
            'beta' => $beta,
            'gamma' => $gamma,
            'uploadedFilename' => $uploadedFilename,
        ];
    }

    /**
     * Upload dan parse file CSV/Excel data pesanan historis
     */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file_excel');
        $handle = fopen($file->getRealPath(), 'r');

        // Hapus BOM jika ada
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $historis = [];
        $rowNum = 0;
        $headerSkipped = false;

        while (($cols = fgetcsv($handle, 1000, ',')) !== false) {
            $rowNum++;

            // Skip baris kosong
            if (empty(array_filter($cols, fn($c) => trim($c) !== ''))) {
                continue;
            }

            // Lewati baris header
            if ($rowNum === 1 && !is_numeric(trim($cols[1] ?? ''))) {
                $headerSkipped = true;
                continue;
            }

            if (!$headerSkipped && $rowNum === 1) {
                continue;
            }

            $dateStr = trim($cols[0] ?? '');
            $countVal = trim($cols[1] ?? '');

            if ($dateStr === '' || $countVal === '') {
                continue;
            }

            try {
                $carbonDate = Carbon::parse($dateStr);
                $formattedDate = $carbonDate->format('Y-m');
                $label = $carbonDate->isoFormat('MMM YYYY');
                $count = (int) $countVal;

                $historis[] = [
                    'tanggal' => $formattedDate,
                    'label' => $label,
                    'count' => max(0, $count)
                ];
            } catch (\Exception $e) {
                fclose($handle);
                return redirect()->back()->with('error', "Format tanggal tidak valid pada baris {$rowNum}: '{$dateStr}'");
            }
        }

        fclose($handle);

        if (count($historis) < 24) {
            return redirect()->back()->with('error', 'Data Excel minimal harus berisi 24 bulan (2 tahun) data historis untuk menghitung pola musiman Holt-Winters.');
        }

        // Urutkan data secara kronologis berdasarkan tanggal
        usort($historis, fn($a, $b) => strcmp($a['tanggal'], $b['tanggal']));

        // Simpan ke session
        session([
            'uploaded_prediction_data' => $historis,
            'uploaded_prediction_filename' => $file->getClientOriginalName()
        ]);

        return redirect()->route('admin.prediksi.index')->with('success', 'File Excel/CSV data pesanan historis berhasil di-upload!');
    }

    /**
     * Hapus data upload dari session dan kembali ke data DB
     */
    public function clearUpload()
    {
        session()->forget(['uploaded_prediction_data', 'uploaded_prediction_filename']);
        return redirect()->route('admin.prediksi.index')->with('success', 'Kembali menggunakan data transaksi dari database.');
    }

    /**
     * Download template CSV prediksi
     */
    public function downloadTemplate()
    {
        $rows = [
            ['Bulan', 'Jumlah_Pesanan'],
            ['2024-01', '15'],
            ['2024-02', '18'],
            ['2024-03', '12'],
            ['2024-04', '20'],
            ['2024-05', '35'],
            ['2024-06', '50'],
            ['2024-07', '65'],
            ['2024-08', '40'],
            ['2024-09', '25'],
            ['2024-10', '18'],
            ['2024-11', '14'],
            ['2024-12', '10'],
            ['2025-01', '17'],
            ['2025-02', '20'],
            ['2025-03', '15'],
            ['2025-04', '24'],
            ['2025-05', '38'],
            ['2025-06', '55'],
            ['2025-07', '70'],
            ['2025-08', '45'],
            ['2025-09', '28'],
            ['2025-10', '22'],
            ['2025-11', '16'],
            ['2025-12', '12'],
        ];

        $filename = 'template_data_prediksi_simapes.csv';
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, "\xEF\xBB\xBF"); // BOM for Excel

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * AJAX endpoint untuk analisis AI menggunakan Gemini/OpenRouter
     */
    public function analisisAi(Request $request)
    {
        $provider = $request->input('provider', 'gemini');
        if (!in_array($provider, ['gemini', 'openrouter'])) {
            return response()->json(['success' => false, 'message' => 'Provider AI tidak valid.'], 400);
        }

        $data = $this->getPredictionData($request);

        if (!$data['hasData']) {
            return response()->json(['success' => false, 'message' => 'Data tidak cukup untuk melakukan analisis AI.'], 400);
        }

        try {
            $analysis = $this->aiPredictionService->analyze(
                $provider,
                $data['historis'],
                $data['prediksi'],
                $data['mrp'],
                $data['mape'],
                $data['mad']
            );

            return response()->json([
                'success' => true,
                'analysis' => $analysis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tampilkan cetak PO
     */
    public function printPo(Request $request)
    {
        $supplierId = $request->query('supplier_id');
        $supplier = Supplier::findOrFail($supplierId);
        $bahan = $request->query('bahan');
        $jumlah = $request->query('jumlah');
        $satuan = $request->query('satuan');
        $noPo = 'PO/' . Carbon::now()->format('Ymd') . '/' . strtoupper(bin2hex(random_bytes(2)));

        return view('admin.prediksi.print_po', compact('supplier', 'bahan', 'jumlah', 'satuan', 'noPo'));
    }
}
