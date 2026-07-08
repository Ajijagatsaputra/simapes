@extends('layouts.pelanggan')
@section('title', 'Buat Pesanan Seragam - SIMAPES')

@push('styles')
    <style>
        .order-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .order-container {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px dashed #e2e8f4;
            padding-bottom: 12px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 6px;
        }

        /* ── Table Items ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .items-table th {
            text-align: left;
            padding: 10px;
            font-size: .75rem;
            font-weight: 600;
            color: #8ca0bf;
            border-bottom: 1px solid #e2e8f4;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .form-select,
        .form-input {
            width: 100%;
            padding: 9px 12px;
            font-family: inherit;
            font-size: .82rem;
            color: #1a2b4a;
            background: #f5f8ff;
            border: 1.5px solid #c5d8f5;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s;
        }

        .form-select:focus,
        .form-input:focus {
            border-color: #4A90D9;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.12);
        }

        .btn-add-row {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e8f0fd;
            color: #4A90D9;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-add-row:hover {
            background: #4A90D9;
            color: #fff;
        }

        .btn-remove-row {
            width: 32px;
            height: 32px;
            background: #fdeaea;
            color: #e05a5a;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-remove-row:hover {
            background: #e05a5a;
            color: #fff;
        }

        /* ── Order Summary Box ── */
        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: .85rem;
            color: #5a7090;
            margin-bottom: 10px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 800;
            color: #1a2b4a;
            border-top: 1px dashed #e2e8f4;
            padding-top: 14px;
            margin-top: 14px;
        }

        .btn-submit {
            display: block;
            width: 100%;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            margin-top: 18px;
            text-align: center;
        }

        .btn-submit:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        /* ── Excel Section ── */
        .excel-card {
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
            text-align: center;
        }

        .btn-excel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f5f8ff;
            border: 1px dashed #c5d8f5;
            color: #4A90D9;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            margin-top: 12px;
            transition: all 0.2s;
        }

        .btn-excel:hover {
            background: #e8f0fd;
            border-style: solid;
        }
    </style>
@endpush

@section('content')
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 1.6rem; font-weight: 800; color: #1a2b4a;">Buat Pesanan Baru</h1>
        <p style="font-size: .85rem; color: #6b7e9f; margin-top: 4px;">Masukkan total akumulasi per ukuran baju dari seluruh
            kelas (Kelas 1 – Kelas 6). Setiap kombinasi produk dan ukuran dicatat sebagai satu baris pesanan.</p>
        <p
            style="font-size: .78rem; color: #8ca0bf; margin-top: 6px; background: #f5f8ff; border: 1px solid #dde8f8; border-radius: 8px; padding: 10px 14px; line-height: 1.6;">
            <strong style="color: #4A90D9;">💡 Contoh:</strong> Kaos Olahraga Ukuran M (50 pcs), Kaos Olahraga Ukuran XL (50
            pcs) — masing-masing diinput pada baris terpisah. Tidak ada batasan jumlah pesanan.
        </p>
    </div>

    <form method="POST" action="{{ route('pelanggan.pesanan.store') }}" id="orderForm">
        @csrf

        <div class="order-container">
            {{-- Left Side: Items --}}
            <div>
                <div class="card">
                    <div class="card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Daftar Pesanan Seragam
                    </div>

                    <div style="overflow-x: auto; width: 100%;">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Jenis Seragam</th>
                                    <th style="width: 120px; text-align: center;">Ukuran</th>
                                    <th style="width: 150px; text-align: center;">Jumlah</th>
                                    <th style="width: 130px; text-align: right;">Subtotal</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Dynamic rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn-add-row" onclick="addRow()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Seragam
                    </button>
                </div>
            </div>

            {{-- Right Side: Summary --}}
            <div>
                <div class="card">
                    <div class="card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Ringkasan Pesanan
                    </div>

                    <div class="summary-row">
                        <span>Total Jumlah Item</span>
                        <span id="summaryTotalItems">0 Pcs</span>
                    </div>
                    <div class="summary-row">
                        <span>Status Pengajuan</span>
                        <span style="color: #d97706; font-weight: 600;">Menunggu Tinjauan Admin</span>
                    </div>

                    <div class="summary-total">
                        <span>Estimasi Total</span>
                        <span id="summaryGrandTotal" style="color: #4A90D9;">Rp 0</span>
                    </div>

                    <p style="font-size: .72rem; color: #8ca0bf; margin-top: 10px; line-height: 1.5; text-align: center;">
                        Pembayaran dilakukan setelah pesanan ditinjau dan disetujui oleh admin.
                    </p>

                    <button type="submit" class="btn-submit">
                        Ajukan Pesanan
                    </button>
                </div>

                {{-- Excel Import/Export Card --}}
                <div class="excel-card">
                    <h3 style="font-size: .92rem; font-weight: 700; color: #1a2b4a;">Pemesanan Massal via Excel</h3>
                    <p style="font-size: .75rem; color: #6b7e9f; margin-top: 4px;">Punya pesanan dalam jumlah banyak?
                        Gunakan template Excel kami</p>
                    <a href="{{ route('pelanggan.pesanan.template') }}" class="btn-excel">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Unduh Template
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        const produkList = @json($produk);
        const preselectedProdukId = @json(request()->query('produk_id'));
        let rowCount = 0;

        document.addEventListener('DOMContentLoaded', () => {
            // Add initial row
            addRow(preselectedProdukId);
        });

        function addRow(selectedId = null) {
            rowCount++;
            const body = document.getElementById('itemsBody');
            const row = document.createElement('tr');
            row.id = `row-${rowCount}`;

            // Dropdown options
            let options = '<option value="">-- Pilih Seragam --</option>';
            produkList.forEach(p => {
                const selectedAttr = (selectedId && Number(selectedId) === p.id) ? 'selected' : '';
                options += `<option value="${p.id}" data-price="${p.harga}" ${selectedAttr}>${p.nama_produk} - Rp ${formatNumber(p.harga)}</option>`;
            });

            row.innerHTML = `
                                        <td>
                                            <select name="items[${rowCount}][produk_id]" class="form-select" onchange="calculateRowSubtotal(${rowCount})" required>
                                                ${options}
                                            </select>
                                        </td>
                                        <td>
                                            <select name="items[${rowCount}][ukuran]" class="form-select" style="text-align: center;" required>
                                                <option value="" disabled>-- Ukuran --</option>
                                                <option value="S">S</option>
                                                <option value="M" selected>M</option>
                                                <option value="L">L</option>
                                                <option value="XL">XL</option>
                                                <option value="XXL">XXL</option>
                                                <option value="3XL">3XL</option>
                                                <option value="4XL">4XL</option>
                                                <option value="5XL">5XL</option>
                                            </select>
                                        </td>
                                    <td style="text-align: center; position: relative; padding-bottom: 22px;">
                                        <input type="number" name="items[${rowCount}][total_item]" class="form-input" value="1" min="1" oninput="calculateRowSubtotal(${rowCount})" required style="text-align: center; max-width: 80px; margin: 0 auto; display: block;">
                                        <span style="font-size: .65rem; color: #8ca0bf; position: absolute; bottom: 4px; left: 0; right: 0; text-align: center; white-space: nowrap;">Agregat semua kelas</span>
                                    </td>
                                        <td style="text-align: right; font-weight: 700; color: #1a2b4a;" id="subtotal-${rowCount}">
                                            Rp 0
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="btn-remove-row" onclick="removeRow(${rowCount})" title="Hapus item">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg>
                                            </button>
                                        </td>
                                    `;

            body.appendChild(row);
            calculateRowSubtotal(rowCount);
        }

        function removeRow(id) {
            const row = document.getElementById(`row-${id}`);
            if (document.querySelectorAll('#itemsBody tr').length > 1) {
                row.remove();
                calculateGrandTotal();
            } else {
                showToast('Minimal harus ada 1 item pesanan', 'warning');
            }
        }

        function calculateRowSubtotal(id) {
            const row = document.getElementById(`row-${id}`);
            const select = row.querySelector(`select[name="items[${id}][produk_id]"]`);
            const quantityInput = row.querySelector(`input[name="items[${id}][total_item]"]`);
            const subtotalCell = document.getElementById(`subtotal-${id}`);

            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.value ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
            const quantity = parseInt(quantityInput.value) || 0;

            const subtotal = price * quantity;
            subtotalCell.textContent = `Rp ${formatNumber(subtotal)}`;
            subtotalCell.setAttribute('data-value', subtotal);

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            let totalItems = 0;

            document.querySelectorAll('#itemsBody tr').forEach(row => {
                const subtotalCell = row.querySelector('td[id^="subtotal-"]');
                const quantityInput = row.querySelector('input[name*="[total_item]"]');

                const subtotal = parseFloat(subtotalCell.getAttribute('data-value')) || 0;
                const quantity = parseInt(quantityInput.value) || 0;

                grandTotal += subtotal;
                totalItems += quantity;
            });

            document.getElementById('summaryTotalItems').textContent = `${totalItems} Pcs`;
            document.getElementById('summaryGrandTotal').textContent = `Rp ${formatNumber(grandTotal)}`;
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    </script>
@endsection