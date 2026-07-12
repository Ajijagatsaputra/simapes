{{-- ── Form Parameter Input ── --}}
@if(request()->query('optimized'))
    <div
        style="background: #e8f8ee; border-left: 5px solid #34c472; color: #2e7d32; padding: 14px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <span>Parameter Berhasil Dioptimasi Menggunakan <strong>Grid Search</strong>! Ditemukan tingkat kesalahan (MAPE)
            terendah untuk data transaksi historis Anda.</span>
    </div>
@endif

@if(session()->has('uploaded_prediction_data'))
    <div
        style="background: #e8f4fd; border-left: 5px solid #4A90D9; color: #1a2b4a; padding: 14px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; box-shadow: 0 2px 8px rgba(74, 144, 217, 0.06);">
        <div style="display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                style="color: #4A90D9;">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>Menggunakan Data Alternatif Upload: <strong>{{ session('uploaded_prediction_filename') }}</strong></span>
        </div>
        <form action="{{ route('admin.prediksi.clear') }}" method="POST" style="margin: 0; display: inline;">
            @csrf
            <button type="submit" class="btn-hitung"
                style="background: #e05a5a; padding: 6px 14px; font-size: 0.78rem; min-height: auto; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus &amp; Gunakan Data Baru
            </button>
        </form>
    </div>
@endif

<div class="form-prediksi">
    <form action="{{ route('admin.prediksi.index') }}" method="GET" id="formPrediksi">
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div class="form-preset-grid">
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
                <div>
                    <a href="{{ route('admin.prediksi.index', ['optimize' => 1]) }}" class="btn-hitung"
                        style="width: 100%; background: #8a63d2; text-decoration: none; box-sizing: border-box; text-align: center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                            <polyline points="2 17 12 22 22 17"></polyline>
                            <polyline points="2 12 12 17 22 12"></polyline>
                        </svg>
                        Optimasi Parameter
                    </a>
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