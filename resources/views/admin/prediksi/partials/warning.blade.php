{{-- ── Tampilan Peringatan Jika Data Tidak Cukup ── --}}
<div class="warning-box">
    <div class="warning-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
            <line x1="12" y1="9" x2="12" y2="13" />
            <line x1="12" y1="17" x2="12.01" y2="17" />
        </svg>
    </div>
    <div>
        <h3 class="warning-title">Data Historis Tidak Mencukupi</h3>
        <p class="warning-desc">{{ $message }}</p>
        <div class="warning-desc" style="margin-top: 8px;">
            Untuk melakukan simulasi data 3 tahun penuh secara otomatis di database, silakan jalankan command artisan
            seeder di terminal proyek Anda:
        </div>
        <div class="seeder-hint" style="margin-bottom: 8px;">
            php artisan db:seed --class=PesananHistorisSeeder
        </div>
        <div class="warning-desc" style="font-size: 0.78rem; opacity: 0.85;">
            Jika Anda menggunakan <strong>Docker</strong>, jalankan perintah ini:
        </div>
        <div class="seeder-hint" style="background: #2b384e; color: #a5d6ff;">
            docker compose exec app php artisan db:seed --class=PesananHistorisSeeder
        </div>
    </div>
</div>

<div class="warning-box"
    style="margin-top: 20px; flex-direction: column; background: #f0f4fb; border-left-color: #4A90D9; box-shadow: 0 4px 12px rgba(74, 144, 217, 0.08);">
    <div style="display: flex; gap: 16px; align-items: flex-start; width: 100%;">
        <div class="warning-icon" style="color: #4A90D9;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="17 8 12 3 7 8" />
                <line x1="12" y1="3" x2="12" y2="15" />
            </svg>
        </div>
        <div>
            <h3 class="warning-title" style="color: #1a2b4a;">Upload Data Historis Alternatif (Format CSV)</h3>
            <p class="warning-desc" style="color: #4a5a7a;">
                Anda dapat mengunggah file data penjualan bulanan dalam format <strong>CSV (Comma Separated
                    Values)</strong>. Jika menggunakan Microsoft Excel, simpan file sebagai <strong>CSV (UTF-8)</strong>
                atau <strong>CSV (Comma Delimited)</strong> sebelum mengunggah.
            </p>
        </div>
    </div>

    <div style="width: 100%; margin-top: 16px; padding-top: 16px; border-top: 1px dashed #cedbe9;">
        <form action="{{ route('admin.prediksi.upload') }}" method="POST" enctype="multipart/form-data"
            style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
            @csrf
            <div style="position: relative; overflow: hidden; display: inline-block;">
                <input type="file" name="file_excel" id="file_excel_warning" required accept=".csv"
                    style="display: none;"
                    onchange="document.getElementById('file-name-warning').textContent = this.files[0] ? this.files[0].name : ''">
                <button type="button" onclick="document.getElementById('file_excel_warning').click()" class="btn-hitung"
                    style="background: #ffffff; color: #4A90D9; border: 1px solid #4A90D9;">
                    Pilih File CSV
                </button>
            </div>
            <span id="file-name-warning" style="font-size: 0.8rem; color: #4a5a7a; font-weight: 500;"></span>
            <button type="submit" class="btn-hitung" style="background: #34c472;">
                Upload & Hitung
            </button>
            <a href="{{ route('admin.prediksi.template') }}" class="btn-hitung"
                style="background: #8ca0bf; text-decoration: none;">
                Download Template
            </a>
        </form>
    </div>
</div>