@extends('layouts.main')

@section('title', 'Data Pelanggan — SIMAPES')

@push('styles')
    <style>
        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.2;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #8ca0bf;
            margin-top: 4px;
        }

        .breadcrumb a {
            color: #8ca0bf;
            text-decoration: none;
            transition: color .15s;
        }

        .breadcrumb a:hover {
            color: #4A90D9;
        }

        .breadcrumb-sep {
            font-size: .7rem;
            opacity: .5;
        }

        .breadcrumb-current {
            color: #4A90D9;
            font-weight: 600;
        }

        .page-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            color: #6b7e9f;
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 10px;
            padding: 8px 14px;
        }

        /* ── Stat Card ── */
        .stat-bar {
            background: #fff;
            border: 1px solid #e8eef8;
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 22px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
        }

        .stat-bar-icon {
            width: 50px;
            height: 50px;
            background: #e8f0fd;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4A90D9;
            flex-shrink: 0;
        }

        .stat-bar-label {
            font-size: .75rem;
            color: #8ca0bf;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-bar-value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.1;
        }

        .stat-bar-desc {
            font-size: .72rem;
            color: #a0aec0;
        }

        /* ── Main Panel Layout ── */
        .pelanggan-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        /* ── Card ── */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
        }

        /* ── Table Toolbar ── */
        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .table-toolbar-left {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-wrap {
            display: flex;
            align-items: center;
            background: #f5f8ff;
            border: 1px solid #dde8f8;
            border-radius: 10px;
            padding: 6px 12px;
            gap: 8px;
        }

        .search-wrap input {
            border: none;
            background: transparent;
            outline: none;
            font-size: .8rem;
            color: #1a2b4a;
            width: 160px;
            font-family: inherit;
        }

        .search-wrap input::placeholder {
            color: #aab9d0;
        }

        .search-wrap svg {
            color: #8ca0bf;
            flex-shrink: 0;
        }

        .btn-tambah {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s, transform .15s;
        }

        .btn-tambah:hover {
            background: #3a7bc8;
            transform: translateY(-1px);
        }

        /* ── Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
        }

        .data-table thead th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e8eef8;
        }

        .data-table thead th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .data-table thead th:last-child {
            border-radius: 0 8px 8px 0;
            text-align: center;
        }

        .data-table tbody td {
            padding: 11px 12px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr:hover td {
            background: #fafcff;
        }

        .data-table td:last-child {
            text-align: center;
        }

        /* ── Aksi Buttons ── */
        .aksi-wrap {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .btn-edit,
        .btn-hapus {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: opacity .15s, transform .15s;
        }

        .btn-edit {
            background: #4A90D9;
            color: #fff;
        }

        .btn-hapus {
            background: #e05a5a;
            color: #fff;
        }

        .btn-edit:hover,
        .btn-hapus:hover {
            opacity: .85;
            transform: scale(1.08);
        }

        /* ── Nomor baris ── */
        .row-number {
            color: #8ca0bf;
            font-weight: 600;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 18px;
        }

        .page-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #dde8f8;
            border-radius: 8px;
            background: #fff;
            color: #4A90D9;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: inherit;
            transition: background .15s, color .15s;
        }

        .page-btn:hover {
            background: #4A90D9;
            color: #fff;
            border-color: #4A90D9;
        }

        .page-btn.active {
            background: #4A90D9;
            color: #fff;
            border-color: #4A90D9;
        }

        .page-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .page-dots {
            color: #8ca0bf;
            font-size: .8rem;
            font-weight: 600;
            padding: 0 4px;
        }

        /* ── Form Panel ── */
        .form-panel .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
        }

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
        .form-textarea:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        .form-textarea {
            resize: vertical;
            min-height: 72px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #f0f4fb;
        }

        .btn-batal {
            padding: 8px 18px;
            background: #f0f4fb;
            border: 1px solid #dde8f8;
            border-radius: 9px;
            color: #5a7090;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .btn-batal:hover {
            background: #e2e8f4;
        }

        .btn-simpan {
            padding: 8px 20px;
            background: #4A90D9;
            border: none;
            border-radius: 9px;
            color: #fff;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s;
        }

        .btn-simpan:hover {
            background: #3a7bc8;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #a0aec0;
        }

        .empty-state svg {
            margin-bottom: 12px;
            opacity: .4;
        }

        .empty-state p {
            font-size: .85rem;
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Pelanggan</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Pelanggan</span>
            </nav>
        </div>
        <div class="page-date">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            {{ \Carbon\Carbon::now()->isoFormat('DD MMM YYYY') }}
        </div>
    </div>

    {{-- ── Stat Bar ── --}}
    <div class="stat-bar">
        <div class="stat-bar-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </div>
        <div>
            <div class="stat-bar-label">Total Pelanggan</div>
            <div class="stat-bar-value">{{ $totalPelanggan }}</div>
            <div class="stat-bar-desc">Pelanggan Terdaftar</div>
        </div>
    </div>

    {{-- ── Main Layout: Tabel + Form ── --}}
    <div class="pelanggan-layout">

        {{-- ── Tabel Pelanggan ── --}}
        <div class="card">
            <div class="table-toolbar">
                <span class="table-toolbar-left">Daftar Pelanggan</span>
                <div class="toolbar-right">
                    <div class="search-wrap">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari Pelanggan" oninput="filterTable()">
                    </div>
                    <button class="btn-tambah" id="btn-open-form" onclick="openForm()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Tambah Pelanggan
                    </button>
                </div>
            </div>

            <table class="data-table" id="tablePelanggan">
                <thead>
                    <tr>
                        <th style="width:48px">No.</th>
                        <th>Nama Pelanggan</th>
                        <th>No. WhatsApp</th>
                        <th>Alamat</th>
                        <th>Nama Sekolah</th>
                        <th style="width:80px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($pelanggan as $index => $p)
                        <tr
                            data-search="{{ strtolower($p->name . ' ' . ($p->no_whatsapp ?? '') . ' ' . ($p->nama_sekolah ?? '')) }}">
                            <td class="row-number">{{ $pelanggan->firstItem() + $index }}</td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->no_whatsapp ?? '-' }}</td>
                            <td>{{ $p->alamat ?? '-' }}</td>
                            <td>{{ $p->nama_sekolah ?? '-' }}</td>
                            <td>
                                <div class="aksi-wrap">
                                    <button class="btn-edit" title="Edit"
                                        onclick="editPelanggan({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->no_whatsapp ?? '') }}', '{{ addslashes($p->alamat ?? '') }}', '{{ addslashes($p->nama_sekolah ?? '') }}')">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('pelanggan.destroy', $p->id) }}"
                                        id="form-hapus-pelanggan-{{ $p->id }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-hapus" title="Hapus"
                                            onclick="confirmHapus(document.getElementById('form-hapus-pelanggan-{{ $p->id }}'), '{{ addslashes($p->name) }}')">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                    </svg>
                                    <p>Belum ada data pelanggan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($pelanggan->hasPages())
                <div class="pagination-wrap">
                    {{-- Prev --}}
                    @if($pelanggan->onFirstPage())
                        <button class="page-btn" disabled>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                    @else
                        <a href="{{ $pelanggan->previousPageUrl() }}" class="page-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </a>
                    @endif

                    {{-- Page Numbers (Responsive Sliding Window) --}}
                    @php
                        $start = max(1, $pelanggan->currentPage() - 2);
                        $end = min($pelanggan->lastPage(), $pelanggan->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $pelanggan->url(1) }}" class="page-btn">1</a>
                        @if($start > 2)
                            <span class="page-dots">...</span>
                        @endif
                    @endif

                    @for($page = $start; $page <= $end; $page++)
                        <a href="{{ $pelanggan->url($page) }}"
                            class="page-btn {{ $page == $pelanggan->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endfor

                    @if($end < $pelanggan->lastPage())
                        @if($end < $pelanggan->lastPage() - 1)
                            <span class="page-dots">...</span>
                        @endif
                        <a href="{{ $pelanggan->url($pelanggan->lastPage()) }}" class="page-btn">{{ $pelanggan->lastPage() }}</a>
                    @endif

                    {{-- Next --}}
                    @if($pelanggan->hasMorePages())
                        <a href="{{ $pelanggan->nextPageUrl() }}" class="page-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </a>
                    @else
                        <button class="page-btn" disabled>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Form Panel ── --}}
        <div class="card form-panel" id="formPanel">
            <div class="card-title" id="formTitle">Tambah Pelanggan</div>

            <form method="POST" id="formPelanggan" action="{{ route('pelanggan.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="pelanggan_id" id="pelangganId" value="">

                <div class="form-group">
                    <label class="form-label" for="namaPelanggan">Nama Pelanggan</label>
                    <input class="form-input" type="text" id="namaPelanggan" name="name" placeholder="Masukkan nama lengkap"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="noWhatsapp">No WhatsApp</label>
                    <input class="form-input" type="text" id="noWhatsapp" name="no_whatsapp" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea class="form-textarea" id="alamat" name="alamat"
                        placeholder="Jl. Contoh No. 1, Kota..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="namaSekolah">Nama Sekolah</label>
                    <input class="form-input" type="text" id="namaSekolah" name="nama_sekolah"
                        placeholder="SDN / SMPN / SMAN ...">
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-batal" onclick="resetForm()">Batal</button>
                    <button type="submit" class="btn-simpan" id="btnSimpan">Simpan</button>
                </div>
            </form>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        // ── Search Filter ────────────────────────────────────────────────
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr[data-search]');
            rows.forEach(row => {
                row.style.display = row.dataset.search.includes(q) ? '' : 'none';
            });
        }

        // ── Form: Mode Tambah ────────────────────────────────────────────
        function openForm() {
            document.getElementById('formTitle').textContent = 'Tambah Pelanggan';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formPelanggan').action = '{{ route("pelanggan.store") }}';
            document.getElementById('pelangganId').value = '';
            document.getElementById('namaPelanggan').value = '';
            document.getElementById('noWhatsapp').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('namaSekolah').value = '';
            document.getElementById('btnSimpan').textContent = 'Simpan';
            document.getElementById('namaPelanggan').focus();
        }

        // ── Form: Mode Edit ──────────────────────────────────────────────
        function editPelanggan(id, nama, wa, alamat, sekolah) {
            document.getElementById('formTitle').textContent = 'Edit Pelanggan';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formPelanggan').action = '/pelanggan/' + id;
            document.getElementById('pelangganId').value = id;
            document.getElementById('namaPelanggan').value = nama;
            document.getElementById('noWhatsapp').value = wa;
            document.getElementById('alamat').value = alamat;
            document.getElementById('namaSekolah').value = sekolah;
            document.getElementById('btnSimpan').textContent = 'Update';
            document.getElementById('namaPelanggan').focus();
            document.getElementById('formPanel').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // ── Reset Form ───────────────────────────────────────────────────
        function resetForm() {
            document.getElementById('formTitle').textContent = 'Tambah Pelanggan';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formPelanggan').reset();
            document.getElementById('formPelanggan').action = '{{ route("pelanggan.store") }}';
            document.getElementById('btnSimpan').textContent = 'Simpan';
        }
    </script>
@endpush