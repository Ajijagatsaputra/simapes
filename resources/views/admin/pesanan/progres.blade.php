@extends('layouts.main')
@section('title', 'Kelola Progres Produksi ' . $pesanan->no_pesanan . ' — SIMAPES')

@push('styles')
    <style>
        .page-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #5a7090;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: 18px;
            transition: color .15s;
        }

        .page-back:hover {
            color: #4A90D9;
        }

        .progres-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:1024px) {
            .progres-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Items Table */
        .item-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .78rem;
        }

        .item-tbl th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .7rem;
            text-transform: uppercase;
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #e8eef8;
        }

        .item-tbl td {
            padding: 10px 8px;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 5px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border: 1.5px solid #dde8f8;
            border-radius: 9px;
            padding: 8px 11px;
            font-size: .82rem;
            font-family: inherit;
            color: #1a2b4a;
            background: #fafdff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        /* Dynamic Stage List Styles */
        .stage-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
            margin-bottom: 16px;
        }

        .stage-table th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .7rem;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }

        .stage-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f0f4fb;
            vertical-align: top;
        }

        .btn-add {
            background: #f0f6ff;
            color: #4A90D9;
            border: 1px dashed #4A90D9;
            border-radius: 9px;
            padding: 8px 16px;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background .15s, color .15s;
        }

        .btn-add:hover {
            background: #4A90D9;
            color: #fff;
        }

        .btn-remove {
            background: #fdeaea;
            color: #e05a5a;
            border: none;
            border-radius: 6px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-remove:hover {
            background: #fcd5d5;
        }

        .btn-submit {
            display: block;
            width: 100%;
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
            margin-top: 14px;
            text-align: center;
        }

        .btn-submit:hover {
            background: #3a7bc8;
        }

        .btn-submit:disabled {
            background: #b8d4f4;
            cursor: not-allowed;
        }

        /* Warning Banner */
        .calculator-banner {
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .banner-warning {
            background: #fff3e6;
            border: 1px solid #ffe6cc;
            color: #b45309;
        }

        .banner-success {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #047857;
        }

        .banner-title {
            font-weight: 800;
            font-size: .85rem;
        }

        .banner-desc {
            font-size: .75rem;
        }

        /* Image Preview */
        .img-preview {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #dde8f8;
        }

        .img-preview-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }

        .alert-error {
            background: #fdeaea;
            border: 1px solid #fcd5d5;
            color: #e05a5a;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: .8rem;
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.pesanan.index') }}" class="page-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12" />
            <polyline points="12 19 5 12 12 5" />
        </svg>
        Kembali ke Data Pesanan
    </a>

    <h1 style="font-size:1.5rem; font-weight:800; color:#1a2b4a; margin-bottom:6px;">Kelola Progres Produksi — {{ $pesanan->no_pesanan }}</h1>
    <p style="font-size:.82rem; color:#6b7e9f; margin-bottom:20px;">{{ $pesanan->user->name }} · {{ $pesanan->user->nama_sekolah ?? '-' }}</p>

    {{-- Error Banner --}}
    @if($errors->any() || session('error'))
        <div class="alert-error">
            @if(session('error'))
                <div>{{ session('error') }}</div>
            @endif
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="progres-grid">
        {{-- Left Card: Form Kelola Progres --}}
        <div>
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    Tahapan Pengerjaan Produksi
                </div>

                {{-- Live Target Calculator Banner --}}
                <div id="calculatorBanner" class="calculator-banner banner-warning">
                    <div class="banner-title" id="bannerTitle">Memuat kalkulasi...</div>
                    <div class="banner-desc" id="bannerDesc">Harap tunggu.</div>
                </div>

                <form method="POST" action="{{ route('admin.pesanan.progres.update', $pesanan->id) }}" enctype="multipart/form-data" id="progresForm">
                    @csrf
                    
                    <div style="overflow-x:auto;">
                        <table class="stage-table">
                            <thead>
                                <tr>
                                    <th style="width: 250px;">Tahapan Produksi</th>
                                    <th style="width: 100px;">Jumlah (Pcs)</th>
                                    <th>Dokumentasi & Catatan</th>
                                    <th style="width: 50px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="stageContainer">
                                @forelse($pesanan->progresProduksis as $index => $progres)
                                    <tr class="stage-row" data-index="{{ $index }}">
                                        <td>
                                            <input type="hidden" name="stages[{{ $index }}][id]" value="{{ $progres->id }}">
                                            <input type="hidden" name="stages[{{ $index }}][existing_dokumentasi]" value="{{ $progres->dokumentasi }}">
                                            
                                            <input type="text" name="stages[{{ $index }}][tahapan]" class="form-input stage-tahapan" 
                                                list="tahapan-suggestions" placeholder="Pilih atau tulis tahapan..." value="{{ $progres->tahapan }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="stages[{{ $index }}][jumlah_pcs]" class="form-input stage-pcs" 
                                                min="0" max="{{ $totalPcs }}" placeholder="0" value="{{ $progres->jumlah_pcs }}" oninput="calcTotalPcs()" required>
                                        </td>
                                        <td>
                                            <input type="file" name="stages[{{ $index }}][dokumentasi]" class="form-input" style="font-size: 0.72rem; padding: 4px 6px;" accept="image/*">
                                            @if($progres->dokumentasi)
                                                <div class="img-preview-container">
                                                    <img src="{{ asset('storage/' . $progres->dokumentasi) }}" class="img-preview" alt="Preview">
                                                    <span style="font-size:0.65rem; color:#8ca0bf;">Gambar saat ini</span>
                                                </div>
                                            @endif
                                            <input type="text" name="stages[{{ $index }}][catatan]" class="form-input" style="margin-top: 6px; font-size: 0.78rem;" 
                                                placeholder="Catatan tambahan..." value="{{ $progres->catatan }}">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-remove" onclick="removeRow(this)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Fallback is handled by auto-initialization, but if somehow empty, JS will add a default row -->
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn-add" id="btnAddRow" onclick="addRow()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Tahapan Produksi
                    </button>

                    <button type="submit" class="btn-submit" id="btnSubmitProgres">Simpan Progres</button>
                </form>
            </div>
        </div>

        {{-- Right Card: Reference Order Details --}}
        <div>
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Rincian Item Pesanan (Referensi)
                </div>

                <div style="overflow-x:auto;">
                    <table class="item-tbl">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Ukuran</th>
                                <th style="text-align: right;">Jumlah (Pcs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->details as $detail)
                                <tr>
                                    <td style="font-weight: 600;">{{ $detail->produk->nama_produk ?? '-' }}</td>
                                    <td>
                                        <span style="background: #e8f0fd; color: #4A90D9; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: .72rem;">
                                            {{ $detail->ukuran }}
                                        </span>
                                    </td>
                                    <td style="text-align: right; font-weight: 600;">{{ $detail->total_item }} pcs</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 1px dashed #dde8f8;">
                                <td colspan="2" style="font-weight: 800; padding: 12px 8px; color: #1a2b4a;">Total Item Target</td>
                                <td style="text-align: right; font-weight: 800; padding: 12px 8px; color: #4A90D9; font-size: .9rem;">{{ $totalPcs }} pcs</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Datalist Suggestions --}}
    <datalist id="tahapan-suggestions">
        <option value="Persiapan Bahan">
        <option value="Proses Potong Kain">
        <option value="Proses Jahit">
        <option value="Proses Pemasangan Kancing">
        <option value="QC & Packing">
        <option value="Selesai Produksi">
    </datalist>
@endsection

@push('scripts')
    <script>
        const targetPcs = {{ $totalPcs }};
        let rowIndex = {{ $pesanan->progresProduksis->count() }};

        function addRow() {
            const container = document.getElementById('stageContainer');
            const newRow = document.createElement('tr');
            newRow.className = 'stage-row';
            newRow.setAttribute('data-index', rowIndex);

            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="stages[${rowIndex}][id]" value="">
                    <input type="text" name="stages[${rowIndex}][tahapan]" class="form-input stage-tahapan" 
                        list="tahapan-suggestions" placeholder="Pilih atau tulis tahapan..." required>
                </td>
                <td>
                    <input type="number" name="stages[${rowIndex}][jumlah_pcs]" class="form-input stage-pcs" 
                        min="0" max="${targetPcs}" placeholder="0" value="0" oninput="calcTotalPcs()" required>
                </td>
                <td>
                    <input type="file" name="stages[${rowIndex}][dokumentasi]" class="form-input" style="font-size: 0.72rem; padding: 4px 6px;" accept="image/*">
                    <input type="text" name="stages[${rowIndex}][catatan]" class="form-input" style="margin-top: 6px; font-size: 0.78rem;" 
                        placeholder="Catatan tambahan...">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="btn-remove" onclick="removeRow(this)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </td>
            `;

            container.appendChild(newRow);
            rowIndex++;
            calcTotalPcs();
        }

        function removeRow(button) {
            const row = button.closest('tr');
            row.remove();
            calcTotalPcs();
        }

        function calcTotalPcs() {
            let hasError = false;
            let offendingStage = '';

            document.querySelectorAll('.stage-row').forEach(row => {
                const tahapanInput = row.querySelector('.stage-tahapan');
                const pcsInput = row.querySelector('.stage-pcs');
                const pcs = parseInt(pcsInput.value) || 0;
                
                if (pcs > targetPcs) {
                    hasError = true;
                    offendingStage = tahapanInput.value || 'Salah satu tahapan';
                }
            });

            const banner = document.getElementById('calculatorBanner');
            const bannerTitle = document.getElementById('bannerTitle');
            const bannerDesc = document.getElementById('bannerDesc');
            const submitBtn = document.getElementById('btnSubmitProgres');

            if (!hasError) {
                banner.className = 'calculator-banner banner-success';
                bannerTitle.textContent = '✓ Progres Valid';
                bannerDesc.textContent = `Jumlah pcs pada masing-masing tahapan tidak melebihi target pesanan (${targetPcs} pcs). Anda dapat menyimpan progres.`;
                submitBtn.disabled = false;
            } else {
                banner.className = 'calculator-banner banner-warning';
                bannerTitle.textContent = '⚠️ Jumlah Pcs Melebihi Target';
                bannerDesc.textContent = `Jumlah pcs pada tahapan "${offendingStage}" melebihi target pesanan (${targetPcs} pcs). Harap perbaiki sebelum menyimpan.`;
                submitBtn.disabled = true;
            }
        }

        // Initialize calculator on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add a default row if empty
            if (document.querySelectorAll('.stage-row').length === 0) {
                addRow();
            } else {
                calcTotalPcs();
            }
        });
    </script>
@endpush
