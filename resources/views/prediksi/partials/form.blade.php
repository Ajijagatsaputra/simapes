{{-- ── Form Parameter Input ── --}}
<div class="form-prediksi">
    <form action="{{ route('prediksi.index') }}" method="GET" id="formPrediksi">
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; align-items: flex-end;">
                <div class="form-group">
                    <label for="preset-select" style="font-weight: 700; color: #1a2b4a;">
                        Mode Sensitivitas Prediksi
                    </label>
                    <select id="preset-select" class="preset-select">
                        <option value="otomatis">Rekomendasi Sistem (Stabil & Optimal)</option>
                        <option value="tren">Sensitif terhadap Tren Penjualan Terbaru (Responsif)</option>
                        <option value="musiman">Fokus pada Siklus Musiman Tahunan (Tahun Ajaran Baru)</option>
                        <option value="kustom">Kustom Parameter Lanjutan (Keperluan Uji Sidang / Uji Akurasi)</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-hitung" style="width: 100%;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        Hitung Prediksi
                    </button>
                </div>
            </div>

            {{-- Row Parameter Lanjutan (hanya muncul saat Kustom dipilih) ── --}}
            <div id="custom-params-wrapper" class="form-grid"
                style="display: none; border-top: 1px dashed #e2e8f4; padding-top: 16px;">
                <div class="form-group">
                    <label for="alpha">
                        &alpha; (Alpha) - Pemulus Level
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="color:#8ca0bf; cursor:pointer;"
                            title="Bobot pemulusan untuk Level dasar penjualan. Nilai: 0 - 1.">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </label>
                    <input type="number" step="0.0001" min="0" max="1" name="alpha" id="alpha" value="{{ $alpha }}">
                </div>
                <div class="form-group">
                    <label for="beta">
                        &beta; (Beta) - Pemulus Tren
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="color:#8ca0bf; cursor:pointer;"
                            title="Bobot pemulusan untuk perubahan tren kenaikan/penurunan. Nilai: 0 - 1.">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </label>
                    <input type="number" step="0.0001" min="0" max="1" name="beta" id="beta" value="{{ $beta }}">
                </div>
                <div class="form-group">
                    <label for="gamma">
                        &gamma; (Gamma) - Pemulus Musiman
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" style="color:#8ca0bf; cursor:pointer;"
                            title="Bobot pemulusan untuk pola musiman tahunan. Nilai: 0 - 1.">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="16" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12.01" y2="8" />
                        </svg>
                    </label>
                    <input type="number" step="0.0001" min="0" max="1" name="gamma" id="gamma" value="{{ $gamma }}">
                </div>
            </div>
        </div>
    </form>
</div>
