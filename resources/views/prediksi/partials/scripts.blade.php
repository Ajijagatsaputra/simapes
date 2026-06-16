@if($hasData)
    {{-- Mengimpor Chart.js dari CDN (Jika belum dimuat di layout utama) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Data Historis dari Controller
            const dataHistoris = @json($historis);
            // Data Prediksi dari Controller
            const dataPrediksi = @json($prediksi);
            // Data Fitted Value Historis (Backtesting)
            const dataFitted = @json($historis_fitted);

            // Olah label dan data point untuk Chart
            const labelsHistoris = dataHistoris.map(item => item.label);
            const countsHistoris = dataHistoris.map(item => item.count);

            const labelsPrediksi = dataPrediksi.map(item => item.label);
            const countsPrediksi = dataPrediksi.map(item => item.count);

            // Gabungkan label (historis + 12 bulan prediksi)
            const allLabels = [...labelsHistoris, ...labelsPrediksi];

            // Setup data point historis (data riil, null di akhir agar garis terputus)
            const datasetHistoris = [...countsHistoris, ...Array(labelsPrediksi.length).fill(null)];

            // Setup data point fitted (backtesting model, null di akhir)
            const datasetFitted = [...dataFitted, ...Array(labelsPrediksi.length).fill(null)];

            // Setup data point prediksi (null di awal + 1 titik pertemuan di index akhir historis agar garis menyambung + data prediksi)
            const titikPertemuan = countsHistoris[countsHistoris.length - 1];
            const datasetPrediksi = [...Array(countsHistoris.length - 1).fill(null), titikPertemuan, ...countsPrediksi];

            const ctx = document.getElementById('chartPrediksiTahunan').getContext('2d');

            // Setup gradient warna latar bawah kurva
            const gradientBlue = ctx.createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(74, 144, 217, 0.2)');
            gradientBlue.addColorStop(1, 'rgba(74, 144, 217, 0)');

            const gradientPurple = ctx.createLinearGradient(0, 0, 0, 300);
            gradientPurple.addColorStop(0, 'rgba(138, 99, 210, 0.2)');
            gradientPurple.addColorStop(1, 'rgba(138, 99, 210, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [
                        {
                            label: 'Jumlah Pesanan Aktual (Historis)',
                            data: datasetHistoris,
                            borderColor: '#4A90D9',
                            backgroundColor: gradientBlue,
                            borderWidth: 3,
                            pointBackgroundColor: '#4A90D9',
                            pointRadius: function (context) {
                                return context.dataIndex % 3 === 0 ? 3 : 0;
                            },
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Fitted Value (Model Backtesting)',
                            data: datasetFitted,
                            borderColor: '#34c472',
                            borderWidth: 2,
                            borderDash: [3, 3],
                            pointRadius: 0,
                            fill: false,
                            tension: 0.3
                        },
                        {
                            label: 'Proyeksi Prediksi (Holt-Winters)',
                            data: datasetPrediksi,
                            borderColor: '#8a63d2',
                            backgroundColor: gradientPurple,
                            borderWidth: 3,
                            borderDash: [5, 5], // Membuat garis putus-putus khusus untuk prediksi
                            pointBackgroundColor: '#8a63d2',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Inter',
                                size: 11,
                                    weight: '500'
                                },
                                color: '#1a2b4a'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            bodyFont: {
                                family: 'Inter'
                            },
                            titleFont: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 9
                                },
                                color: '#8ca0bf',
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 20
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f4fb'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 10
                                },
                                color: '#8ca0bf'
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        });
    </script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const presetSelect = document.getElementById('preset-select');
        const customWrapper = document.getElementById('custom-params-wrapper');
        const alphaInput = document.getElementById('alpha');
        const betaInput = document.getElementById('beta');
        const gammaInput = document.getElementById('gamma');

        const presets = {
            otomatis: { alpha: 0.2, beta: 0.1, gamma: 0.3 },
            tren: { alpha: 0.5, beta: 0.4, gamma: 0.3 },
            musiman: { alpha: 0.2, beta: 0.1, gamma: 0.6 }
        };

        function updateParams() {
            const val = presetSelect.value;
            if (val === 'kustom') {
                customWrapper.style.display = 'grid';
            } else {
                customWrapper.style.display = 'none';
                if (presets[val]) {
                    alphaInput.value = presets[val].alpha;
                    betaInput.value = presets[val].beta;
                    gammaInput.value = presets[val].gamma;
                }
            }
        }

        // Tentukan state awal select berdasarkan input saat ini
        const currentAlpha = parseFloat(alphaInput.value);
        const currentBeta = parseFloat(betaInput.value);
        const currentGamma = parseFloat(gammaInput.value);

        let matchedPreset = 'kustom';
        for (const [key, p] of Object.entries(presets)) {
            if (Math.abs(p.alpha - currentAlpha) < 0.0001 &&
                Math.abs(p.beta - currentBeta) < 0.0001 &&
                Math.abs(p.gamma - currentGamma) < 0.0001) {
                matchedPreset = key;
                break;
            }
        }

        presetSelect.value = matchedPreset;
        updateParams();

        presetSelect.addEventListener('change', updateParams);
    });
</script>