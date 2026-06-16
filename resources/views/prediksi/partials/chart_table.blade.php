{{-- Chart Visualisasi --}}
<div class="card">
    <div class="card-title-wrap">
        <span class="card-title">Grafik Proyeksi Permintaan Pesanan (Historis vs Prediksi)</span>
    </div>
    <div style="position: relative; height: 350px;">
        <canvas id="chartPrediksiTahunan"></canvas>
    </div>
</div>

{{-- Tabel Data Prediksi --}}
<div class="card">
    <div class="card-title-wrap">
        <span class="card-title">Tabel Hasil Prediksi (12 Bulan Ke Depan)</span>
    </div>
    <div class="pred-table-wrap">
        <table class="pred-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th style="text-align: right;">Jumlah Prediksi</th>
                    <th style="text-align: center;">Tingkat Volume</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prediksi as $p)
                    <tr>
                        <td style="font-weight: 600;">{{ $p['label'] }}</td>
                        <td style="text-align: right; font-weight: 700; color: #1a2b4a;">
                            {{ $p['count'] }} Pesanan
                        </td>
                        <td style="text-align: center;">
                            @if($p['count'] >= 18)
                                <span class="badge-status tinggi">Volume Tinggi</span>
                            @elseif($p['count'] >= 8)
                                <span class="badge-status sedang">Volume Sedang</span>
                            @else
                                <span class="badge-status rendah">Volume Rendah</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
