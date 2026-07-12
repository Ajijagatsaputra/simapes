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
                            borderDash: [5, 5],
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
            if (!presetSelect || !customWrapper) return;
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

        if (alphaInput && betaInput && gammaInput) {
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

            if (presetSelect) {
                presetSelect.value = matchedPreset;
                updateParams();
                presetSelect.addEventListener('change', updateParams);
            }
        }
    });

    // ── AJAX AI ANALYSIS HANDLER ──────────────────────────────────────────
    function runAiAnalysis() {
        const provider = document.getElementById('aiProviderSelect').value;
        const alpha = document.getElementById('alpha') ? document.getElementById('alpha').value : 0.2;
        const beta = document.getElementById('beta') ? document.getElementById('beta').value : 0.1;
        const gamma = document.getElementById('gamma') ? document.getElementById('gamma').value : 0.3;

        const contentArea = document.getElementById('aiContentArea');
        const token = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

        // Sembunyikan tombol download PDF terlebih dahulu
        const btnPdf = document.getElementById('btnDownloadPdf');
        if (btnPdf) {
            btnPdf.style.display = 'none';
        }

        // Tampilkan loading screen interaktif
        contentArea.innerHTML = `
            <div class="ai-loading-container">
                <div class="ai-spinner"></div>
                <div class="ai-loading-text" id="aiLoadingStatus">Menghubungkan ke layanan AI...</div>
                <div style="width: 60%; max-width: 300px;">
                    <div class="shimmer-line"></div>
                    <div class="shimmer-line" style="width: 85%;"></div>
                    <div class="shimmer-line" style="width: 90%;"></div>
                </div>
            </div>
        `;

        // Daftar pesan loading bergantian
        const loadingMessages = [
            "AI sedang mengumpulkan data historis dan peramalan...",
            "AI sedang menganalisis pola musiman dan tren penjualan...",
            "AI sedang menghitung kebutuhan bahan baku (kain, kancing, benang, resleting)...",
            "AI sedang merumuskan rekomendasi operasional konveksi...",
            "Menyusun laporan analitik strategis SIMAPES..."
        ];

        let msgIdx = 0;
        const msgInterval = setInterval(() => {
            const statusEl = document.getElementById('aiLoadingStatus');
            if (statusEl) {
                statusEl.textContent = loadingMessages[msgIdx % loadingMessages.length];
                msgIdx++;
            } else {
                clearInterval(msgInterval);
            }
        }, 3000);

        // Kirim request ke backend
        fetch("{{ route('admin.prediksi.analisisAi') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({
                provider: provider,
                alpha: alpha,
                beta: beta,
                gamma: gamma
            })
        })
            .then(response => response.json())
            .then(data => {
                clearInterval(msgInterval);
                if (data.success) {
                    const parsedHtml = localMarkdownParser(data.analysis);
                    contentArea.innerHTML = `
                    <div class="ai-rendered-markdown">
                        ${parsedHtml}
                    </div>
                `;
                    // Tampilkan dan update url tombol PDF
                    if (btnPdf) {
                        const baseUrl = "{{ route('admin.prediksi.exportPdf') }}";
                        btnPdf.href = `${baseUrl}?alpha=${alpha}&beta=${beta}&gamma=${gamma}`;
                        btnPdf.style.display = 'inline-flex';
                    }
                } else {
                    contentArea.innerHTML = `
                    <div style="color: #e63946; padding: 20px; text-align: center; font-weight: 600;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        Gagal memuat analisis: ${data.message}
                    </div>
                `;
                }
            })
            .catch(err => {
                clearInterval(msgInterval);
                contentArea.innerHTML = `
                <div style="color: #e63946; padding: 20px; text-align: center; font-weight: 600;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 8px; display: block; margin-left: auto; margin-right: auto;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Terjadi kesalahan koneksi ke server. Silakan coba lagi.
                </div>
            `;
            });
    }

    // ── LOCAL MARKDOWN TO SEMANTIC HTML CONVERTER ──────────────────────────
    function localMarkdownParser(text) {
        let html = text;

        // Escape HTML tags to prevent XSS (if text comes from AI)
        html = html.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

        // Headers (e.g. ### Header)
        html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
        html = html.replace(/^#### (.*$)/gim, '<h4>$1</h4>');

        // Bold (e.g. **bold**)
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // Blockquotes (e.g. > blockquote)
        html = html.replace(/^\> (.*$)/gim, '<blockquote><p>$1</p></blockquote>');

        // Bullet points (e.g. - item)
        let lines = html.split('\n');
        let inList = false;
        for (let i = 0; i < lines.length; i++) {
            let line = lines[i].trim();
            if (line.startsWith('- ') || line.startsWith('* ')) {
                let content = line.substring(2);
                if (!inList) {
                    lines[i] = '<ul><li>' + content + '</li>';
                    inList = true;
                } else {
                    lines[i] = '<li>' + content + '</li>';
                }
            } else {
                if (inList) {
                    lines[i] = '</ul>' + lines[i];
                    inList = false;
                }
            }
        }
        if (inList) {
            lines[lines.length - 1] = lines[lines.length - 1] + '</ul>';
        }
        html = lines.join('\n');

        // Paragraphs: wrap non-heading, non-list, non-blockquote lines in p tags if they are not empty
        html = html.split('\n').map(line => {
            let trimmed = line.trim();
            if (trimmed === '') return '';
            if (trimmed.startsWith('<h') || trimmed.startsWith('<ul') || trimmed.startsWith('<li') || trimmed.startsWith('</ul') || trimmed.startsWith('<blockquote') || trimmed.startsWith('</blockquote')) {
                return line;
            }
            return '<p>' + line + '</p>';
        }).join('\n');

        return html;
    }
</script>