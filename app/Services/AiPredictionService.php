<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiPredictionService
{
    /**
     * Jalankan analisis peramalan menggunakan model AI (Gemini atau OpenRouter)
     *
     * @param string $provider
     * @param array $historicalData
     * @param array $forecastData
     * @param array $mrpData
     * @param float $mape
     * @param float $mad
     * @return string
     */
    public function analyze(string $provider, array $historicalData, array $forecastData, array $mrpData, float $mape, float $mad): string
    {
        $prompt = $this->buildPrompt($historicalData, $forecastData, $mrpData, $mape, $mad);

        if ($provider === 'gemini') {
            return $this->callGemini($prompt);
        } else {
            return $this->callOpenRouter($prompt);
        }
    }

    /**
     * Membuat prompt analisis data terstruktur untuk AI
     */
    protected function buildPrompt(array $historical, array $forecast, array $mrp, float $mape, float $mad): string
    {
        // Format data historis
        $histText = "";
        foreach ($historical as $h) {
            $histText .= "- {$h['label']}: {$h['count']} pesanan\n";
        }

        // Format data peramalan
        $foreText = "";
        foreach ($forecast as $f) {
            $foreText .= "- {$f['label']}: {$f['count']} pesanan\n";
        }

        // Format data kebutuhan bahan (MRP)
        $mrpText = "";
        foreach ($mrp as $key => $val) {
            $mrpText .= "- **{$val['nama']}**:\n";
            $mrpText .= "  * Total Kebutuhan 1 Tahun: {$val['jumlah']} {$val['satuan']}\n";
            $mrpText .= "  * Lead Time Pengadaan: {$val['lead_time']} hari\n";
            $mrpText .= "  * Safety Stock (Stok Pengaman): {$val['safety_stock']} {$val['satuan']}\n";
            $mrpText .= "  * Reorder Point (ROP - Titik Pemesanan Kembali): {$val['rop']} {$val['satuan']}\n";
            $mrpText .= "  * Keterangan: {$val['keterangan']}\n";
        }

        return <<<PROMPT
Anda adalah AI ahli dalam analitik bisnis konveksi dan peramalan inventori/rantai pasok untuk sistem SIMAPES (Sistem Informasi Manajemen Pemesanan & Prediksi Konveksi Seragam).

Tugas Anda adalah menganalisis data historis transaksi, hasil peramalan Holt-Winters Triple Exponential Smoothing, dan kebutuhan bahan baku (Material Requirements Planning - MRP) di bawah ini, kemudian memberikan laporan analitik bisnis yang komprehensif, strategis, dan mudah dipahami oleh pemilik konveksi/admin.

Berikut adalah data terstruktur hasil perhitungan sistem:

### 1. DATA TRANSAKSI HISTORIS BULANAN
{$histText}

### 2. HASIL PERAMALAN HOLT-WINTERS (12 BULAN KE DEPAN)
{$foreText}

### 3. EVALUASI ERROR MODEL PERAMALAN
- Mean Absolute Percentage Error (MAPE): {$mape}%
- Mean Absolute Deviation (MAD): {$mad} pesanan

### 4. RENCANA KEBUTUHAN BAHAN BAKU (MRP) & INVENTORI
{$mrpText}

---

Berdasarkan data di atas, tolong berikan analisis mendalam yang mencakup poin-poin berikut:

1. **Analisis Tren & Pola Musiman**: Jelaskan secara rinci pola fluktuasi pesanan historis. Identifikasi bulan-bulan puncak (peak season, misalnya saat menjelang tahun ajaran baru sekolah) dan bulan-bulan sepi (low season).
2. **Evaluasi Keakuratan Peramalan**: Berikan penilaian terhadap performa model Holt-Winters berdasarkan nilai error MAPE ({$mape}%) dan MAD ({$mad}). Jelaskan apa arti persentase MAPE tersebut bagi keandalan peramalan ini (apakah sangat akurat, baik, atau kurang).
3. **Panduan Strategis Inventori & MRP**: Berikan instruksi praktis tentang bagaimana pemilik konveksi harus menyikapi nilai Safety Stock dan Reorder Point (ROP) untuk masing-masing bahan baku. Jelaskan kapan pemesanan ulang harus dilakukan agar tidak terjadi stockout (kehabisan bahan) dengan mempertimbangkan Lead Time pengadaan masing-masing bahan.
4. **Rekomendasi Operasional & Manajemen Rantai Pasok**: Berikan rekomendasi bisnis konkret mengenai manajemen kapasitas produksi (misal: penambahan tenaga kerja lepas/penjahit borongan pada bulan sibuk), persiapan modal kerja, serta kemitraan dengan supplier untuk mengantisipasi lonjakan permintaan.

**Instruksi Output:**
- Tuliskan jawaban Anda secara profesional dalam **Bahasa Indonesia** yang baik, formal, dan mudah dipahami.
- Gunakan format **Markdown** yang bersih (gunakan heading, bold text, bullet points, dan blok kutipan jika diperlukan) agar dapat dirender dengan indah di halaman web.
- JANGAN gunakan tag HTML mentah. Langsung gunakan format Markdown standar.
PROMPT;
    }

    /**
     * Panggil API Gemini secara langsung
     */
    protected function callGemini(string $prompt): string
    {
        $key = config('services.gemini.key');
        $url = config('services.gemini.url');

        if (!$key) {
            throw new \Exception("GEMINI_API_KEY belum dikonfigurasi di file .env");
        }

        $response = Http::timeout(30)
            ->post("{$url}?key={$key}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

        if ($response->failed()) {
            $errorMsg = $response->json('error.message') ?? $response->body();
            throw new \Exception("Gemini API Error: " . $errorMsg);
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (!$text) {
            throw new \Exception("Gemini API tidak mengembalikan konten analisis.");
        }

        return $text;
    }

    /**
     * Panggil API OpenRouter (Gemini 2.5 Flash)
     */
    protected function callOpenRouter(string $prompt): string
    {
        $key = config('services.openrouter.key');
        $model = config('services.openrouter.model', 'google/gemini-2.5-flash');

        if (!$key) {
            throw new \Exception("OPENROUTER_API_KEY belum dikonfigurasi di file .env");
        }

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => "Bearer {$key}",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => 'http://localhost',
                'X-Title' => 'SIMAPES',
            ])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

        if ($response->failed()) {
            $errorMsg = $response->json('error.message') ?? $response->body();
            throw new \Exception("OpenRouter API Error: " . $errorMsg);
        }

        $text = $response->json('choices.0.message.content');

        if (!$text) {
            throw new \Exception("OpenRouter API tidak mengembalikan konten analisis.");
        }

        return $text;
    }
}
