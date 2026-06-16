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
        <div class="seeder-hint">
            php artisan db:seed --class=PesananHistorisSeeder
        </div>
    </div>
</div>
