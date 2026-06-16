<?php

namespace App\Services;

class PredictionService
{
    /**
     * Menghitung prediksi menggunakan Holt-Winters Triple Exponential Smoothing (Multiplicative)
     *
     * @param array $historicalData Array data aktual bulanan: [['tanggal' => 'Y-m', 'label' => '...', 'count' => int], ...]
     * @param float $alpha Parameter smoothing Level (0-1)
     * @param float $beta Parameter smoothing Trend (0-1)
     * @param float $gamma Parameter smoothing Seasonality (0-1)
     * @param int $forecastSteps Jumlah bulan yang ingin diprediksi ke depan (default 12)
     * @return array
     */
    public function calculateHoltWinters(array $historicalData, float $alpha, float $beta, float $gamma, int $forecastSteps = 12): array
    {
        $n = count($historicalData);
        $seasonLength = 12; // Musiman 12 bulan (tahunan)

        // Validasi kecukupan data (minimal 24 bulan atau 2 tahun penuh)
        if ($n < 24) {
            return [
                'success' => false,
                'message' => 'Data transaksi di database masih kurang dari 24 bulan (minimal 2 tahun penuh) untuk menghitung pola musiman secara akurat.'
            ];
        }

        // Ambil nilai data aktual Y_t
        $y = array_column($historicalData, 'count');

        // ── STEP 1: INISIALISASI LEVEL (L_0) & TREN (T_0) ──────────────────
        // Rata-rata tahun pertama (A1) dan tahun kedua (A2)
        $a1 = array_sum(array_slice($y, 0, $seasonLength)) / $seasonLength;
        $a2 = array_sum(array_slice($y, $seasonLength, $seasonLength)) / $seasonLength;

        // Hindari nilai nol untuk menghindari pembagian dengan nol
        if ($a1 == 0) $a1 = 1.0;
        if ($a2 == 0) $a2 = 1.0;

        $l0 = $a1; 
        $t0 = ($a2 - $a1) / $seasonLength;

        // ── STEP 2: INISIALISASI INDEKS MUSIMAN (S_t) UNTUK TAHUN PERTAMA ────
        // S_i = 0.5 * ( (Y_i / A1) + (Y_{i+12} / A2) ) untuk i = 0..11
        $s = [];
        for ($i = 0; $i < $seasonLength; $i++) {
            $s[$i] = 0.5 * (($y[$i] / $a1) + ($y[$i + $seasonLength] / $a2));
            if ($s[$i] == 0) {
                $s[$i] = 1.0; // Fallback jika indeks musiman nol
            }
        }

        // Array penampung nilai Level (L), Trend (T) dan Forecast (F) historis
        $l = array_fill(0, $n, 0.0);
        $t = array_fill(0, $n, 0.0);
        $f = array_fill(0, $n, null); // Hasil prediksi historis (satu langkah ke depan)

        // Set nilai inisialisasi pada akhir tahun pertama (index 11)
        $l[$seasonLength - 1] = $l0;
        $t[$seasonLength - 1] = $t0;

        // ── STEP 3: EVALUASI ITERATIF (Dari bulan ke-13 s.d. bulan terakhir) ──
        // t dimulai dari index 12 (bulan ke-13)
        for ($i = $seasonLength; $i < $n; $i++) {
            // Prediksi satu langkah ke depan untuk bulan ini (F_i) dibuat di bulan sebelumnya (i-1)
            $f[$i] = ($l[$i - 1] + $t[$i - 1]) * $s[$i - $seasonLength];

            // 1. Perbarui Level (L_i)
            // L_i = alpha * (Y_i / S_{i-12}) + (1 - alpha) * (L_{i-1} + T_{i-1})
            $seasonalIndex = $s[$i - $seasonLength];
            if ($seasonalIndex == 0) $seasonalIndex = 1.0;
            $l[$i] = ($alpha * ($y[$i] / $seasonalIndex)) + ((1 - $alpha) * ($l[$i - 1] + $t[$i - 1]));

            // 2. Perbarui Trend (T_i)
            // T_i = beta * (L_i - L_{i-1}) + (1 - beta) * T_{i-1}
            $t[$i] = ($beta * ($l[$i] - $l[$i - 1])) + ((1 - $beta) * $t[$i - 1]);

            // 3. Perbarui Indeks Musiman (S_i)
            // S_i = gamma * (Y_i / L_i) + (1 - gamma) * S_{i-12}
            $currentLevel = $l[$i];
            if ($currentLevel == 0) $currentLevel = 1.0;
            $s[$i] = ($gamma * ($y[$i] / $currentLevel)) + ((1 - $gamma) * $s[$i - $seasonLength]);
        }

        // ── STEP 4: PERHITUNGAN PREDISKI MASA DEPAN (Forecast Steps) ─────────
        $forecastValues = [];
        $lastLevel = $l[$n - 1];
        $lastTrend = $t[$n - 1];

        for ($m = 1; $m <= $forecastSteps; $m++) {
            // Indeks musiman sirkular dari periode tahun terakhir
            $seasonalIdx = $s[$n - $seasonLength + (($m - 1) % $seasonLength)];
            
            // F_{n+m} = (L_n + m * T_n) * S_{n-12+m}
            $predictedCount = ($lastLevel + ($m * $lastTrend)) * $seasonalIdx;
            
            // Jumlah pesanan tidak boleh negatif, minimal 0
            $forecastValues[] = max(0, round($predictedCount));
        }

        // ── STEP 5: PERHITUNGAN ERROR (MAPE & MAD) ─────────────────────────
        $absolutePercentageErrors = [];
        $absoluteErrors = [];
        $validErrorCount = 0;

        for ($i = $seasonLength; $i < $n; $i++) {
            if ($y[$i] > 0 && !is_null($f[$i])) {
                $error = abs($y[$i] - $f[$i]);
                $absoluteErrors[] = $error;
                $absolutePercentageErrors[] = ($error / $y[$i]) * 100;
                $validErrorCount++;
            }
        }

        $mape = $validErrorCount > 0 ? (array_sum($absolutePercentageErrors) / $validErrorCount) : 0.0;
        $mad = count($absoluteErrors) > 0 ? (array_sum($absoluteErrors) / count($absoluteErrors)) : 0.0;

        // Format label bulan ke depan
        $futureForecast = [];
        $lastDate = \Carbon\Carbon::parse($historicalData[$n - 1]['tanggal'] . '-01');
        
        for ($m = 1; $m <= $forecastSteps; $m++) {
            $nextDate = $lastDate->copy()->addMonths($m);
            $futureForecast[] = [
                'tanggal' => $nextDate->format('Y-m'),
                'label'   => $nextDate->isoFormat('MMM YYYY'),
                'count'   => $forecastValues[$m - 1]
            ];
        }

        return [
            'success' => true,
            'historis_fitted' => $f, // Nilai forecast kecocokan historis
            'prediksi' => $futureForecast,
            'mape' => $mape,
            'mad' => $mad
        ];
    }

    /**
     * Menghitung prediksi menggunakan Single Exponential Smoothing (SES) sebagai pembanding
     */
    public function calculateSES(array $historicalData, float $alpha, int $forecastSteps = 12): array
    {
        $n = count($historicalData);
        if ($n < 1) {
            return ['success' => false, 'message' => 'Data tidak cukup.'];
        }
        $y = array_column($historicalData, 'count');

        $f = array_fill(0, $n, null);
        $f[0] = (float) $y[0]; // Inisialisasi F_1 = Y_1

        for ($i = 1; $i < $n; $i++) {
            $f[$i] = ($alpha * $y[$i - 1]) + ((1 - $alpha) * $f[$i - 1]);
        }

        // Ramalan langkah ke depan (SES konstan)
        $lastForecast = ($alpha * $y[$n - 1]) + ((1 - $alpha) * $f[$n - 1]);
        $forecastValues = [];
        for ($m = 1; $m <= $forecastSteps; $m++) {
            $forecastValues[] = max(0, round($lastForecast));
        }

        // Hitung MAPE untuk SES (mulai indeks ke-12 agar sebanding dengan Holt-Winters)
        $absolutePercentageErrors = [];
        $validErrorCount = 0;
        $seasonLength = 12;

        for ($i = $seasonLength; $i < $n; $i++) {
            if ($y[$i] > 0 && !is_null($f[$i])) {
                $error = abs($y[$i] - $f[$i]);
                $absolutePercentageErrors[] = ($error / $y[$i]) * 100;
                $validErrorCount++;
            }
        }
        $mape = $validErrorCount > 0 ? (array_sum($absolutePercentageErrors) / $validErrorCount) : 0.0;

        // Format tanggal
        $futureForecast = [];
        $lastDate = \Carbon\Carbon::parse($historicalData[$n - 1]['tanggal'] . '-01');
        for ($m = 1; $m <= $forecastSteps; $m++) {
            $nextDate = $lastDate->copy()->addMonths($m);
            $futureForecast[] = [
                'tanggal' => $nextDate->format('Y-m'),
                'label'   => $nextDate->isoFormat('MMM YYYY'),
                'count'   => $forecastValues[$m - 1]
            ];
        }

        return [
            'success' => true,
            'prediksi' => $futureForecast,
            'mape' => $mape
        ];
    }

    /**
     * Mencari parameter smoothing terbaik (alpha, beta, gamma) dengan Grid Search Optimization (MAPE terkecil)
     */
    public function findOptimalParameters(array $historicalData): array
    {
        $bestAlpha = 0.2;
        $bestBeta = 0.1;
        $bestGamma = 0.3;
        $minMape = 999999.0;

        // Grid search step 0.05
        for ($alpha = 0.05; $alpha <= 0.95; $alpha += 0.05) {
            for ($beta = 0.05; $beta <= 0.95; $beta += 0.05) {
                for ($gamma = 0.05; $gamma <= 0.95; $gamma += 0.05) {
                    $res = $this->calculateHoltWinters($historicalData, $alpha, $beta, $gamma, 12);
                    if (isset($res['success']) && $res['success'] && $res['mape'] < $minMape) {
                         $minMape = $res['mape'];
                         $bestAlpha = $alpha;
                         $bestBeta = $beta;
                         $bestGamma = $gamma;
                    }
                }
            }
        }

        return [
            'alpha' => round($bestAlpha, 2),
            'beta' => round($bestBeta, 2),
            'gamma' => round($bestGamma, 2),
            'mape' => $minMape
        ];
    }
}

