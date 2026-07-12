<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\AiPredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiPredictionTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@gmail.com',
        ]);
    }

    /**
     * Test admin can access prediction page
     */
    public function test_admin_can_access_prediction_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.prediksi.index'));
        $response->assertStatus(200);
        $response->assertSee('Prediksi Jumlah Pesanan');
    }

    /**
     * Test downloading template CSV
     */
    public function test_admin_can_download_prediction_template()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.prediksi.template'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="template_data_prediksi_simapes.csv"');
        $response->assertSee('Bulan,Jumlah_Pesanan');
    }

    /**
     * Test uploading prediction CSV stores data in session
     */
    public function test_admin_can_upload_valid_csv_prediction_data()
    {
        // Buat konten CSV mock 24 baris (minimal)
        $csvContent = "Bulan,Jumlah_Pesanan\n";
        for ($i = 1; $i <= 24; $i++) {
            $year = 2024 + intval($i / 12);
            $month = str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT);
            $csvContent .= "{$year}-{$month}," . rand(10, 50) . "\n";
        }

        $file = UploadedFile::fake()->createWithContent('test_prediction.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.prediksi.upload'), [
            'file_excel' => $file,
        ]);

        $response->assertRedirect(route('admin.prediksi.index'));
        $response->assertSessionHas('uploaded_prediction_data');
        $response->assertSessionHas('uploaded_prediction_filename', 'test_prediction.csv');
    }

    /**
     * Test uploading prediction CSV with semicolon delimiter (Indonesian Excel locale)
     */
    public function test_admin_can_upload_valid_semicolon_csv_prediction_data()
    {
        // Buat konten CSV mock 24 baris (minimal) dengan separator titik koma (;)
        $csvContent = "Bulan;Jumlah_Pesanan\n";
        for ($i = 1; $i <= 24; $i++) {
            $year = 2024 + intval($i / 12);
            $month = str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT);
            $csvContent .= "{$year}-{$month};" . rand(10, 50) . "\n";
        }

        $file = UploadedFile::fake()->createWithContent('test_prediction_semicolon.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.prediksi.upload'), [
            'file_excel' => $file,
        ]);

        $response->assertRedirect(route('admin.prediksi.index'));
        $response->assertSessionHas('uploaded_prediction_data');
        $response->assertSessionHas('uploaded_prediction_filename', 'test_prediction_semicolon.csv');
    }

    /**
     * Test uploading prediction CSV with multi-column year/month split and categories
     */
    public function test_admin_can_upload_valid_multicol_csv_prediction_data()
    {
        // Buat konten CSV mock 24 baris (minimal) dengan format Tahun, Bulan, dan kategori produk
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $csvContent = "Tahun,Bulan,Seragam_TK,Seragam_SD,Seragam_SMP,Seragam_SMA,Atribut\n";

        $rowIdx = 0;
        for ($year = 2022; $year <= 2024; $year++) {
            foreach ($months as $month) {
                $rowIdx++;
                $csvContent .= "{$year},{$month},10,20,30,40,50\n"; // Total sum = 150 per month
                if ($rowIdx >= 24)
                    break;
            }
            if ($rowIdx >= 24)
                break;
        }

        $file = UploadedFile::fake()->createWithContent('test_prediction_multicol.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.prediksi.upload'), [
            'file_excel' => $file,
        ]);

        $response->assertRedirect(route('admin.prediksi.index'));
        $response->assertSessionHas('uploaded_prediction_data');
        $response->assertSessionHas('uploaded_prediction_filename', 'test_prediction_multicol.csv');

        $data = session('uploaded_prediction_data');
        $this->assertCount(24, $data);
        $this->assertEquals(150, $data[0]['count']); // 10+20+30+40+50
        $this->assertEquals('2022-01', $data[0]['tanggal']);
    }

    /**
     * Test upload fails if data has less than 24 months
     */
    public function test_admin_upload_fails_if_less_than_24_months()
    {
        // Hanya 10 baris
        $csvContent = "Bulan,Jumlah_Pesanan\n";
        for ($i = 1; $i <= 10; $i++) {
            $csvContent .= "2024-" . str_pad($i, 2, '0', STR_PAD_LEFT) . ",15\n";
        }

        $file = UploadedFile::fake()->createWithContent('invalid_prediction.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.prediksi.upload'), [
            'file_excel' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('Data Excel minimal harus berisi 24 bulan', session('error'));
    }

    /**
     * Test clearing uploaded data from session
     */
    public function test_admin_can_clear_uploaded_prediction_data()
    {
        $response = $this->actingAs($this->admin)
            ->withSession([
                'uploaded_prediction_data' => [['tanggal' => '2024-01', 'label' => 'Jan 2024', 'count' => 10]],
                'uploaded_prediction_filename' => 'old_file.csv'
            ])
            ->post(route('admin.prediksi.clear'));

        $response->assertRedirect(route('admin.prediksi.index'));
        $response->assertSessionMissing('uploaded_prediction_data');
        $response->assertSessionMissing('uploaded_prediction_filename');
    }

    /**
     * Test AI analysis AJAX request
     */
    public function test_admin_can_run_ai_analysis_via_gemini()
    {
        // Mock Gemini HTTP call
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => '### Analisis AI Mocked Response']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        // Buat data historis mock di session agar bisa langsung dianalisis
        $historis = [];
        for ($i = 1; $i <= 24; $i++) {
            $year = 2024 + intval($i / 12);
            $month = str_pad(($i % 12) + 1, 2, '0', STR_PAD_LEFT);
            $historis[] = [
                'tanggal' => "{$year}-{$month}",
                'label' => "Bulan {$i}",
                'count' => 20
            ];
        }

        $response = $this->actingAs($this->admin)
            ->withSession([
                'uploaded_prediction_data' => $historis,
                'uploaded_prediction_filename' => 'test.csv'
            ])
            ->postJson(route('admin.prediksi.analisisAi'), [
                'provider' => 'gemini',
                'alpha' => 0.2,
                'beta' => 0.1,
                'gamma' => 0.3
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'analysis' => '### Analisis AI Mocked Response'
        ]);
    }
}
