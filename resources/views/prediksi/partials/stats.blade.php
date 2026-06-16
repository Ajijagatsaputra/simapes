{{-- ── Stat Cards Grid (Tampil jika ada data) ── --}}
<div class="stats-grid">
    {{-- Card 1: MAPE / Akurasi --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <span class="stat-label">Akurasi Prediksi (HW)</span>
        </div>
        <div class="stat-value">{{ number_format(100 - $mape, 2) }}%</div>
        <div class="stat-desc" style="display: flex; flex-direction: column; gap: 2px; line-height: 1.3;">
            <span>MAPE HW: <strong>{{ number_format($mape, 2) }}%</strong> (Stabil)</span>
            <span style="color: #f5a54a; font-weight: 500;">MAPE SES: {{ number_format($sesMape, 2) }}% (Datar)</span>
        </div>
    </div>

    {{-- Card 2: Bulan Puncak --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                    <line x1="2" y1="20" x2="22" y2="20" />
                </svg>
            </div>
            <span class="stat-label">Bulan Puncak Prediksi</span>
        </div>
        <div class="stat-value" style="font-size: 1.35rem; font-weight: 800; padding: 2px 0;">{{ $puncakPrediksi }}
        </div>
        <div class="stat-desc">Est. Permintaan Tertinggi</div>
    </div>

    {{-- Card 3: Total Prediksi Pesanan --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="16" y1="13" x2="8" y2="13" />
                    <line x1="16" y1="17" x2="8" y2="17" />
                    <polyline points="10 9 9 9 8 9" />
                </svg>
            </div>
            <span class="stat-label">Total Est. Pesanan</span>
        </div>
        <div class="stat-value">{{ $totalPrediksiTahunDepan }}</div>
        <div class="stat-desc">12 Bulan ke Depan</div>
    </div>

    {{-- Card 4: Rata-Rata Bulanan --}}
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <span class="stat-label">Rata-Rata Bulanan</span>
        </div>
        <div class="stat-value">{{ $rataRataPesananPrediksi }} / bln</div>
        <div class="stat-desc">Berdasarkan hasil proyeksi</div>
    </div>
</div>
