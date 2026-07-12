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

    // ── INTERACTIVE WHAT-IF SCENARIO SIMULATOR ────────────────────────────
    function runWhatIfSimulation(val) {
        const multiplier = parseFloat(val);
        const formatNum = (num) => new Intl.NumberFormat('id-ID').format(num);

        // 1. Update Tabel MRP
        const rows = document.querySelectorAll('tr[data-mrp-key]');
        rows.forEach(row => {
            const baseJumlah = parseFloat(row.getAttribute('data-base-jumlah'));
            const leadTime = parseFloat(row.getAttribute('data-lead-time'));

            const newJumlah = Math.ceil(baseJumlah * multiplier);
            const avgDaily = newJumlah / 360;
            const newSafetyStock = Math.ceil(0.5 * avgDaily * leadTime);
            const newRop = Math.ceil((avgDaily * leadTime) + newSafetyStock);

            const cellJumlah = row.querySelector('.mrp-jumlah');
            const cellSafety = row.querySelector('.mrp-safety-stock');
            const cellRop = row.querySelector('.mrp-rop');

            if (cellJumlah) {
                if (multiplier !== 1.0) {
                    const diffPercent = Math.round((multiplier - 1.0) * 100);
                    const prefix = diffPercent > 0 ? '+' : '';
                    cellJumlah.innerHTML = `${formatNum(newJumlah)} <span style="font-size: 0.72rem; font-weight: bold; color: ${diffPercent > 0 ? '#34c472' : '#ef4444'}; display: block;">(${prefix}${diffPercent}%)</span>`;
                } else {
                    cellJumlah.textContent = formatNum(newJumlah);
                }
            }
            if (cellSafety) cellSafety.textContent = formatNum(newSafetyStock);
            if (cellRop) cellRop.textContent = formatNum(newRop);
        });

        // 2. Update Badge Judul (Kebutuhan Estimasi Pesanan)
        const baseOrders = {{ $totalPrediksiTahunDepan ?? 0 }};
        const basePcs = baseOrders * 20;
        const newOrders = Math.round(baseOrders * multiplier);
        const newPcs = Math.round(basePcs * multiplier);
        const titleBadge = document.querySelector('.mrp-title-badge');
        if (titleBadge) {
            titleBadge.textContent = `Est. untuk ${formatNum(newOrders)} Pesanan (${formatNum(newPcs)} Pcs)`;
        }

        // 3. Update Jumlah Kebutuhan di Detail Supplier
        const reqAmounts = document.querySelectorAll('.mrp-required-amount');
        reqAmounts.forEach(span => {
            const baseJumlah = parseFloat(span.getAttribute('data-base-jumlah'));
            const satuan = span.getAttribute('data-satuan');
            const newJumlah = Math.ceil(baseJumlah * multiplier);

            if (multiplier !== 1.0) {
                const diffPercent = Math.round((multiplier - 1.0) * 100);
                const prefix = diffPercent > 0 ? '+' : '';
                span.innerHTML = `Dibutuhkan: ${formatNum(newJumlah)} ${satuan} <span style="font-size: 0.72rem; font-weight: bold; color: ${diffPercent > 0 ? '#2e7d32' : '#d32f2f'};">(${prefix}${diffPercent}%)</span>`;
            } else {
                span.textContent = `Dibutuhkan: ${formatNum(newJumlah)} ${satuan}`;
            }
        });

        // 4. Update Parameter URL Cetak PO & Link WhatsApp
        const waBtns = document.querySelectorAll('.sup-wa-btn');
        waBtns.forEach(btn => {
            const supName = btn.getAttribute('data-sup-name');
            const phone = btn.getAttribute('data-phone');
            const bahanName = btn.getAttribute('data-bahan-name');
            const satuan = btn.getAttribute('data-satuan');

            const parentContainer = btn.closest('[style*="background: #fafcff"]');
            if (parentContainer) {
                const reqSpan = parentContainer.querySelector('.mrp-required-amount');
                if (reqSpan) {
                    const baseJumlah = parseFloat(reqSpan.getAttribute('data-base-jumlah'));
                    const newJumlah = Math.ceil(baseJumlah * multiplier);

                    const message = `Halo ${supName}, kami tertarik untuk memesan bahan baku ${bahanName} sebanyak ${newJumlah} ${satuan}.`;
                    btn.href = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                }
            }
        });

        const poBtns = document.querySelectorAll('.sup-po-btn');
        poBtns.forEach(btn => {
            const parentContainer = btn.closest('[style*="background: #fafcff"]');
            if (parentContainer) {
                const reqSpan = parentContainer.querySelector('.mrp-required-amount');
                if (reqSpan) {
                    const baseJumlah = parseFloat(reqSpan.getAttribute('data-base-jumlah'));
                    const newJumlah = Math.ceil(baseJumlah * multiplier);

                    const url = new URL(btn.href, window.location.origin);
                    url.searchParams.set('jumlah', newJumlah);
                    btn.href = url.pathname + url.search;
                }
            }
        });
    }
</script>