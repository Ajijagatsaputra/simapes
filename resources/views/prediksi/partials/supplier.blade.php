{{-- ── Rekomendasi Supplier ── --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-title-wrap">
        <span class="card-title" style="display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" style="color: #8a63d2;">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                <line x1="9" y1="3" x2="9" y2="21" />
            </svg>
            Rekomendasi Supplier Pengadaan Bahan Baku
        </span>
    </div>
    <p style="font-size: .8rem; color: #6b7e9f; line-height: 1.5; margin-bottom: 18px;">
        Berikut adalah supplier terdaftar yang direkomendasikan berdasarkan kecocokan kategori bahan baku hasil
        kalkulasi MRP di atas.
    </p>

    <div style="display: flex; flex-direction: column; gap: 16px;">
        @foreach($mrp as $key => $val)
            <div style="background: #fafcff; border: 1px solid #e8eef8; border-radius: 12px; padding: 16px;">
                <div
                    style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #e2e8f4; padding-bottom: 10px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="badge-status sedang"
                            style="text-transform: uppercase; font-size: 0.65rem;">{{ $val['nama'] }}</span>
                        <span style="font-size: 0.8rem; font-weight: 700; color: #1a2b4a;">Dibutuhkan:
                            {{ number_format($val['jumlah'], 0, ',', '.') }} {{ $val['satuan'] }}</span>
                    </div>
                </div>

                @if($rekomendasiSupplier[$key]->isEmpty())
                    <div style="font-size: 0.8rem; color: #a0aec0; padding: 6px 0;">
                        Belum ada supplier terdaftar untuk kategori ini.
                    </div>
                @else
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                        @foreach($rekomendasiSupplier[$key] as $sup)
                            <div
                                style="background: #fff; border: 1px solid #e8eef8; border-radius: 10px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                                <div>
                                    <div style="font-size: 0.82rem; font-weight: 700; color: #1a2b4a;">{{ $sup->nama_supplier }}
                                    </div>
                                    <div
                                        style="font-size: 0.72rem; color: #8ca0bf; margin-top: 4px; display: flex; align-items: flex-start; gap: 4px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                                            <path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        {{ $sup->alamat ?? '-' }}
                                    </div>
                                    <div style="font-size: 0.74rem; color: #5a7090; margin-top: 6px; line-height: 1.4;">
                                        {{ $sup->deskripsi ?? '-' }}
                                    </div>
                                </div>

                                @if($sup->no_whatsapp)
                                    <div>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sup->no_whatsapp) }}?text=Halo%20{{ urlencode($sup->nama_supplier) }},%20kami%20tertarik%20untuk%20memesan%20bahan%20baku%20{{ urlencode(strtolower($val['nama'])) }}%20sebanyak%20{{ $val['jumlah'] }}%20{{ $val['satuan'] }}."
                                            target="_blank"
                                            style="display: inline-flex; align-items: center; gap: 6px; background: #e8f8ee; color: #2e7d32; border: 1px solid #a5d6a7; padding: 6px 12px; border-radius: 8px; font-size: 0.72rem; font-weight: 700; text-decoration: none; transition: background 0.2s;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path
                                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                            </svg>
                                            Hubungi WhatsApp
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
