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
            grid-template-columns: 1.6fr 1fr;
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

        /* ── 5 Stage Step Tracker Cards ── */
        .stage-card {
            background: #fff;
            border: 1.5px solid #e8eef8;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            position: relative;
        }

        .stage-card.completed {
            border-color: #34c472;
            background: #fcfdfc;
        }

        .stage-card.locked {
            opacity: 0.65;
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .stage-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stage-num-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stage-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e8f0fd;
            color: #4A90D9;
            font-weight: 800;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stage-card.completed .stage-num {
            background: #34c472;
            color: #fff;
        }

        .stage-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .stage-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 12px;
        }

        .badge-done {
            background: #e8f8ee;
            color: #27a85d;
        }

        .badge-locked {
            background: #edf2f7;
            color: #718096;
        }

        .badge-pending {
            background: #fff3e6;
            color: #d97706;
        }

        .stage-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        @media(max-width: 640px) {
            .stage-form-grid {
                grid-template-columns: 1fr;
            }
        }

        .img-preview {
            width: 54px;
            height: 54px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #dde8f8;
        }

        .pdf-preview {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            background: #edf2f7;
            border-radius: 6px;
            color: #2d3748;
            font-size: 0.75rem;
            text-decoration: none;
            font-weight: 600;
        }

        /* Items & Logs Table */
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

        .alert-error {
            background: #fdeaea;
            border: 1px solid #fcd5d5;
            color: #e05a5a;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: .8rem;
        }

        /* Timeline Status Logs */
        .timeline-log {
            position: relative;
            padding-left: 20px;
            margin-top: 10px;
        }

        .timeline-log::before {
            content: '';
            position: absolute;
            left: 6px;
            top: 4px;
            bottom: 4px;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 14px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -20px;
            top: 3px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #4A90D9;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e2e8f0;
        }

        .timeline-date {
            font-size: 0.7rem;
            color: #718096;
            font-weight: 600;
        }

        .timeline-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #2d3748;
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
        {{-- Left Card: 5-Stage Tracker Forms --}}
        <div>
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    Tracking Progres 5 Tahapan Produksi
                </div>

                @php
                    $tahapanList = \App\Models\ProgresProduksi::TAHAPAN;
                @endphp

                @foreach($tahapanList as $ke => $namaTahap)
                    @php
                        $slot = $slots[$ke] ?? null;
                        $isCompleted = $slot && $slot->isSelesai();
                        
                        // Cek terkunci jika tahap sebelumnya belum selesai
                        $isLocked = false;
                        if ($ke > 1) {
                            $prevSlot = $slots[$ke - 1] ?? null;
                            if (!$prevSlot || !$prevSlot->isSelesai()) {
                                $isLocked = true;
                            }
                        }
                    @endphp

                    <div class="stage-card {{ $isCompleted ? 'completed' : ($isLocked ? 'locked' : '') }}">
                        <div class="stage-header">
                            <div class="stage-num-title">
                                <div class="stage-num">{{ $ke }}</div>
                                <div>
                                    <div class="stage-title">Tahap {{ $ke }}: {{ $namaTahap }}</div>
                                    @if($isCompleted)
                                        <div style="font-size: 0.72rem; color: #27a85d; font-weight: 600; margin-top: 2px;">
                                            ✓ Selesai pada: {{ \Carbon\Carbon::parse($slot->selesai_pada)->isoFormat('DD MMM YYYY, HH:mm') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($isCompleted)
                                    <span class="stage-badge badge-done">Selesai</span>
                                @elseif($isLocked)
                                    <span class="stage-badge badge-locked">Terkunci</span>
                                @else
                                    <span class="stage-badge badge-pending">Sedang Berjalan</span>
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.pesanan.progres.update', $pesanan->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tahapan_ke" value="{{ $ke }}">

                            <div class="stage-form-grid">
                                <div>
                                    <label class="form-label" style="font-size:0.75rem; font-weight:600; color:#5a7090;">Jumlah Selesai (Pcs)</label>
                                    <input type="number" name="jumlah_pcs" class="form-input" min="0" max="{{ $totalPcs }}" 
                                        value="{{ old('jumlah_pcs', $slot->jumlah_pcs ?? $totalPcs) }}" 
                                        {{ $isCompleted || $isLocked ? 'disabled' : '' }} required style="padding: 6px 10px; font-size: 0.8rem;">
                                </div>
                                <div>
                                    <label class="form-label" style="font-size:0.75rem; font-weight:600; color:#5a7090;">
                                        {{ $ke === 5 ? 'Upload Nota / Dokumen (PDF/Foto)' : 'Upload Foto Dokumentasi' }}
                                    </label>
                                    <input type="file" name="dokumentasi" class="form-input" accept="{{ $ke === 5 ? 'image/*,.pdf' : 'image/*' }}" 
                                        {{ $isCompleted || $isLocked ? 'disabled' : '' }} style="padding: 4px 6px; font-size: 0.72rem;">
                                </div>
                            </div>

                            <div style="margin-top: 8px;">
                                <label class="form-label" style="font-size:0.75rem; font-weight:600; color:#5a7090;">Catatan Pengerjaan</label>
                                @php
                                    $defaultCatatan = $slot->catatan ?? '';
                                    if ($ke === 4 && empty($defaultCatatan)) {
                                        $defaultCatatan = 'Pesanan sudah selesai, silahkan lakukan pelunasan agar pesanan dapat diambil';
                                    }
                                @endphp
                                <input type="text" name="catatan" class="form-input" placeholder="{{ $ke === 4 ? 'Pesanan sudah selesai, silahkan lakukan pelunasan agar pesanan dapat diambil' : 'Tulis catatan tahapan ini...' }}" 
                                    value="{{ old('catatan', $defaultCatatan) }}" {{ $isCompleted || $isLocked ? 'disabled' : '' }} style="padding: 6px 10px; font-size: 0.8rem;">
                            </div>

                            @if($slot && $slot->dokumentasi)
                                <div style="margin-top: 8px;">
                                    @if(Str::endsWith($slot->dokumentasi, '.pdf'))
                                        <a href="{{ asset('storage/' . $slot->dokumentasi) }}" target="_blank" class="pdf-preview">
                                            📄 Lihat File Nota PDF
                                        </a>
                                    @else
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <a href="{{ asset('storage/' . $slot->dokumentasi) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $slot->dokumentasi) }}" class="img-preview" alt="Dokumentasi">
                                            </a>
                                            <span style="font-size:0.7rem; color:#8ca0bf;">Klik gambar untuk memperbesar</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div style="display:flex; align-items:center; justify-content: space-between; gap: 16px; margin-top: 16px; padding-top: 12px; border-top: 1px dashed #edf2f7;">
                                <label style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#2d3748; cursor:pointer;">
                                    <input type="checkbox" name="tandai_selesai" value="1" {{ $isCompleted ? 'checked' : '' }} {{ $isCompleted || $isLocked ? 'disabled' : '' }}>
                                    Tandai Tahap Ini Selesai
                                </label>
                                <button type="submit" class="btn-submit" {{ $isCompleted || $isLocked ? 'disabled' : '' }} style="padding: 7px 16px; font-size: 0.78rem; background: {{ $isCompleted || $isLocked ? '#cbd5e1' : '#4A90D9' }}; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:{{ $isCompleted || $isLocked ? 'not-allowed' : 'pointer' }}; margin-left: auto;">
                                    Simpan Tahap {{ $ke }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right Card: Reference & Logs --}}
        <div>
            {{-- Rincian Item Pesanan --}}
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Rincian Item Pesanan
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
                            @php
                                $groupedDetails = $pesanan->details->groupBy('produk_id');
                            @endphp
                            @foreach($groupedDetails as $produkId => $details)
                                @php
                                    $firstItem = $details->first();
                                    $totalQty = $details->sum('total_item');
                                @endphp
                                <tr>
                                    <td style="font-weight: 600;">{{ $firstItem->produk->nama_produk ?? '-' }}</td>
                                    <td>
                                        <div style="display:flex; flex-wrap:wrap; gap:4px; align-items:center;">
                                            @foreach($details as $d)
                                                <span style="display:inline-flex; align-items:center; gap:3px; background:#eef3fc; border:1px solid #c5d8f5; border-radius:20px; padding:2px 7px; font-size:0.7rem; white-space:nowrap;">
                                                    <span style="background:#4A90D9; color:#fff; border-radius:10px; padding:1px 5px; font-weight:700; font-size:0.65rem;">{{ $d->ukuran }}</span>
                                                    <span style="color:#2d4060; font-weight:600;">×{{ $d->total_item }}</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td style="text-align: right; font-weight: 600;">{{ $totalQty }} pcs</td>
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

            {{-- Timeline Log Status Pesanan --}}
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    Log Perubahan Status (Timestamp)
                </div>

                <div class="timeline-log">
                    @forelse($pesanan->statusLogs as $log)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-date">[{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}]</div>
                            <div class="timeline-title">Status: {{ $log->label ?? ucfirst($log->status) }}</div>
                            @if($log->catatan)
                                <div style="font-size: 0.72rem; color: #718096;">{{ $log->catatan }}</div>
                            @endif
                        </div>
                    @empty
                        <div style="font-size: 0.78rem; color: #a0aec0; text-align: center; padding: 10px 0;">
                            Belum ada log status tercatat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
