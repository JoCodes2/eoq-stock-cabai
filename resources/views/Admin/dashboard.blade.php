@extends('Layouts.Base')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold uppercase">Total Modal</small>
            <h4 id="txt_modal" class="fw-bold text-dark">Rp 0</h4>
            <div class="progress mt-2" style="height: 4px;">
                <div class="progress-bar bg-dark" style="width: 100%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold">TOTAL OMZET (SELESAI)</small>
            <h4 id="txt_omzet" class="fw-bold text-success">Rp 0</h4>
            <small class="text-[10px] text-muted">*Hanya pesanan berstatus selesai</small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold">KEUNTUNGAN BERSIH</small>
            <h4 id="txt_profit" class="fw-bold text-primary">Rp 0</h4>
            <small id="txt_margin" class="text-[10px] text-muted">Margin: 0%</small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold">STOK KRITIS (ROP)</small>
            <h4 id="txt_reorder" class="fw-bold text-danger">0 Produk</h4>
            <small class="text-[10px] text-muted">Perlu reorder segera</small>
        </div>
    </div>
</div>

<!-- Alarm System untuk Data Tidak Wajar -->
<div id="dataWarning" class="row mb-4" style="display: none;">
    <div class="col-12">
        <div class="alert alert-warning border-warning">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">⚠️ PERHATIAN: Data Biaya Tidak Wajar</h6>
                    <p class="mb-2" id="warningMessage">
                        Terdeteksi biaya operasional yang tidak realistis. Mungkin ada kesalahan input data EOQ.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="d-block"><strong>Kemungkinan Penyebab:</strong></small>
                            <small class="d-block">1. Biaya penyimpanan diinput sebagai nominal bukan persentase</small>
                            <small class="d-block">2. Nilai biaya terlalu besar untuk skala bisnis</small>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block"><strong>Saran Perbaikan:</strong></small>
                            <small class="d-block">1. Periksa pengaturan EOQ di menu Master Data</small>
                            <small class="d-block">2. Pastikan biaya penyimpanan dalam % (misal: 20 untuk 20%)</small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" onclick="$('#dataWarning').hide()"></button>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Keuntungan -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">KEUNTUNGAN KOTOR</small>
                    <h5 id="txt_gross_profit" class="fw-bold text-info mb-0">Rp 0</h5>
                    <small id="txt_gross_margin" class="text-[10px] text-muted">Margin Kotor: 0%</small>
                </div>
                <i class="fas fa-chart-line fa-2x text-info opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">BIAYA PENYIMPANAN</small>
                    <h5 id="txt_holding" class="fw-bold text-warning mb-0">Rp 0</h5>
                    <small id="txt_holding_percent" class="text-[10px] text-muted">0% dari Omzet</small>
                </div>
                <i class="fas fa-warehouse fa-2x text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">BIAYA PEMESANAN</small>
                    <h5 id="txt_ordering" class="fw-bold text-orange mb-0" style="color: #fd7e14;">Rp 0</h5>
                    <small id="txt_ordering_percent" class="text-[10px] text-muted">0% dari Omzet</small>
                </div>
                <i class="fas fa-truck-loading fa-2x opacity-50" style="color: #fd7e14;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Progress Bar ROI & Efisiensi -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex justify-content-between mb-2">
                <small class="text-muted fw-bold">ROI (Return on Investment)</small>
                <small id="txt_roi_percent" class="fw-bold">0%</small>
            </div>
            <div class="progress" style="height: 20px;">
                <div id="roi_progress" class="progress-bar"
                     role="progressbar" style="width: 0%;"
                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted mt-2 d-block">
                <span id="txt_roi_detail">Modal: Rp 0 → Omzet: Rp 0</span>
            </small>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex justify-content-between mb-2">
                <small class="text-muted fw-bold">EFISIENSI OPERASIONAL</small>
                <small id="txt_efisiensi_percent" class="fw-bold">0%</small>
            </div>
            <div class="progress" style="height: 20px;">
                <div id="efisiensi_progress" class="progress-bar bg-info"
                     role="progressbar" style="width: 0%;"
                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted mt-2 d-block">
                <span id="txt_efisiensi_detail">Biaya Operasional vs Omzet</span>
            </small>
        </div>
    </div>
</div>

<hr>
<h5 class="fw-bold mb-3"><i class="fa fa-chart-pie me-2 text-danger"></i> Analisis Stok & Titik Pesan (EOQ/ROP)</h5>

<div class="row" id="chartContainer">
    <div class="col-12 text-center py-5" id="loader">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 text-muted">Menganalisis data pasar dan stok...</p>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $.get('/v1/dashboard/chart-eoq', function(res) {
        $('#loader').remove();
        const s = res.summary || {};

        // Format angka dengan fungsi helper
        function formatRupiah(angka) {
            return parseFloat(angka).toLocaleString('id-ID');
        }

        function formatCurrency(angka) {
            return 'Rp ' + formatRupiah(angka);
        }

        // DETECT UNREALISTIC DATA
        const hasUnrealisticData = detectUnrealisticData(s);
        if (hasUnrealisticData.isUnrealistic) {
            $('#dataWarning').show();
            $('#warningMessage').html(hasUnrealisticData.message);
        }

        // Update Finansial
        $('#txt_modal').text(formatCurrency(s.modal_investasi || s.modal || 0));
        $('#txt_omzet').text(formatCurrency(s.omzet || 0));

        // Pastikan keuntungan bersih tidak negatif di tampilan
        const keuntunganBersih = Math.max(parseFloat(s.keuntungan_bersih || 0), 0);
        $('#txt_profit').text(formatCurrency(keuntunganBersih));

        // Hitung margin (jika omzet > 0)
        let marginPercent = 0;
        if (s.omzet > 0) {
            marginPercent = ((keuntunganBersih / s.omzet) * 100).toFixed(1);
        }
        $('#txt_margin').text(`Margin: ${marginPercent}%`);

        // Warna margin berdasarkan persentase
        if (marginPercent >= 20) {
            $('#txt_margin').removeClass('text-muted').addClass('text-success');
        } else if (marginPercent >= 10) {
            $('#txt_margin').removeClass('text-muted').addClass('text-warning');
        } else if (marginPercent > 0) {
            $('#txt_margin').removeClass('text-muted').addClass('text-danger');
        }

        // Update keuntungan kotor
        $('#txt_gross_profit').text(formatCurrency(s.keuntungan_kotor || 0));

        // Margin kotor
        let grossMarginPercent = 0;
        if (s.omzet > 0 && s.keuntungan_kotor) {
            grossMarginPercent = ((s.keuntungan_kotor / s.omzet) * 100).toFixed(1);
        }
        $('#txt_gross_margin').text(`Margin Kotor: ${grossMarginPercent}%`);

        // Update Biaya - dengan persentase dari omzet
        const biayaPenyimpanan = s.biaya_penyimpanan || 0;
        const biayaPemesanan = s.biaya_pemesanan || 0;

        $('#txt_holding').text(formatCurrency(biayaPenyimpanan));
        $('#txt_ordering').text(formatCurrency(biayaPemesanan));

        // Hitung persentase biaya dari omzet
        let holdingPercent = 0;
        let orderingPercent = 0;
        if (s.omzet > 0) {
            holdingPercent = ((biayaPenyimpanan / s.omzet) * 100).toFixed(1);
            orderingPercent = ((biayaPemesanan / s.omzet) * 100).toFixed(1);
        }

        $('#txt_holding_percent').text(`${holdingPercent}% dari Omzet`);
        $('#txt_ordering_percent').text(`${orderingPercent}% dari Omzet`);

        // Warna peringatan jika persentase terlalu tinggi
        if (holdingPercent > 10) {
            $('#txt_holding_percent').removeClass('text-muted').addClass('text-danger');
        } else if (holdingPercent > 5) {
            $('#txt_holding_percent').removeClass('text-muted').addClass('text-warning');
        }

        if (orderingPercent > 5) {
            $('#txt_ordering_percent').removeClass('text-muted').addClass('text-danger');
        } else if (orderingPercent > 2) {
            $('#txt_ordering_percent').removeClass('text-muted').addClass('text-warning');
        }

        // Update stok kritis
        const reorderCount = s.perlu_reorder || 0;
        $('#txt_reorder').text(reorderCount + ' Produk');
        if (reorderCount > 0) {
            $('#txt_reorder').addClass('animate-pulse');
        }

        // Hitung ROI
        let roiPercent = 0;
        const modal = s.modal_investasi || s.modal || 0;
        if (modal > 0 && s.omzet > 0) {
            roiPercent = ((s.omzet / modal) * 100).toFixed(1);
        }

        // Update ROI Progress Bar
        const roiProgress = Math.min(roiPercent, 100);
        const roiProgressBar = $('#roi_progress');
        roiProgressBar.css('width', roiProgress + '%');
        roiProgressBar.text(roiPercent + '%');

        // Warna progress bar berdasarkan ROI
        if (roiPercent >= 100) {
            roiProgressBar.removeClass('bg-danger bg-warning').addClass('bg-success');
        } else if (roiPercent >= 50) {
            roiProgressBar.removeClass('bg-danger bg-success').addClass('bg-warning');
        } else {
            roiProgressBar.removeClass('bg-success bg-warning').addClass('bg-danger');
        }

        $('#txt_roi_percent').text(roiPercent + '%');
        $('#txt_roi_detail').html(`
            Modal: ${formatCurrency(modal)} → Omzet: ${formatCurrency(s.omzet || 0)}
        `);

        // Hitung Efisiensi Operasional
        let efisiensiPercent = 0;
        const totalBiayaOperasional = biayaPenyimpanan + biayaPemesanan;
        if (s.omzet > 0 && totalBiayaOperasional > 0) {
            // Efisiensi = (1 - (biaya operasional / omzet)) * 100
            efisiensiPercent = (100 - ((totalBiayaOperasional / s.omzet) * 100)).toFixed(1);
            efisiensiPercent = Math.max(efisiensiPercent, 0); // Tidak boleh negatif
        }

        $('#txt_efisiensi_percent').text(efisiensiPercent + '%');
        $('#efisiensi_progress').css('width', Math.min(efisiensiPercent, 100) + '%');
        $('#txt_efisiensi_detail').text(`Biaya Operasional: ${formatCurrency(totalBiayaOperasional)}`);

        // Render Charts Produk
        const eoqData = res.chart_eoq || [];
        $('#chartContainer').empty();

        if(eoqData.length === 0) {
            $('#chartContainer').append(`
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Tidak ada data analisis stok. Pastikan Anda telah mengatur pengaturan EOQ untuk produk.
                    </div>
                </div>
            `);
            return;
        }

        eoqData.forEach((item, index) => {
            const chartId = `chart_item_${index}`;
            const isCritical = item.stok_sekarang <= item.rop;
            const statusColor = isCritical ? 'border-danger' : 'border-success';
            const badgeStatus = isCritical
                ? '<span class="badge bg-danger animate-pulse"><i class="fas fa-exclamation-triangle me-1"></i> REORDER</span>'
                : '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> STOK AMAN</span>';

            const stokPercent = item.eoq > 0 ? (item.stok_sekarang / item.eoq * 100).toFixed(1) : 0;
            const ropPercent = item.eoq > 0 ? (item.rop / item.eoq * 100).toFixed(1) : 0;

            $('#chartContainer').append(`
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 ${statusColor}" style="border-top: 4px solid !important;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-uppercase">${item.nama}</h6>
                                    <small class="text-muted">Stok: <b>${formatRupiah(item.stok_sekarang)} Kg</b></small>
                                    <br>
                                    <small class="text-muted">${stokPercent}% dari EOQ | ROP: ${ropPercent}% dari EOQ</small>
                                </div>
                                ${badgeStatus}
                            </div>
                            <div style="height: 180px;">
                                <canvas id="${chartId}"></canvas>
                            </div>
                            <div class="mt-3 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between text-[11px] mb-1">
                                    <span><i class="fas fa-shopping-cart text-primary me-1"></i> EOQ (Optimal)</span>
                                    <span class="fw-bold text-primary">${formatRupiah(item.eoq)} Kg</span>
                                </div>
                                <div class="d-flex justify-content-between text-[11px]">
                                    <span><i class="fas fa-bell text-orange me-1"></i> ROP (Reorder Point)</span>
                                    <span class="fw-bold" style="color: #fd7e14;">${formatRupiah(item.rop)} Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            const ctx = document.getElementById(chartId).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Stok Sekarang', 'ROP', 'EOQ'],
                    datasets: [{
                        label: 'Kg',
                        data: [
                            Math.max(item.stok_sekarang, 0),
                            Math.max(item.rop, 0),
                            Math.max(item.eoq, 0)
                        ],
                        backgroundColor: [
                            isCritical ? 'rgba(220, 53, 69, 0.8)' : 'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ],
                        borderWidth: 0,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${formatRupiah(context.raw)} Kg`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    return formatRupiah(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: {
                                    size: 10,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        });
    }).fail(function(xhr, status, error) {
        $('#loader').remove();
        $('#chartContainer').html(`
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Gagal memuat data dashboard. Silakan coba lagi.
                    <br><small class="text-muted">${xhr.responseJSON?.message || error}</small>
                </div>
            </div>
        `);
    });

    // Fungsi deteksi data tidak wajar
    function detectUnrealisticData(data) {
        const omzet = data.omzet || 0;
        const holdingCost = data.biaya_penyimpanan || 0;
        const orderingCost = data.biaya_pemesanan || 0;
        const modal = data.modal_investasi || data.modal || 0;

        const issues = [];

        // 1. Biaya penyimpanan > 30% omzet (tidak wajar)
        if (omzet > 0 && holdingCost > 0) {
            const holdingPercent = (holdingCost / omzet) * 100;
            if (holdingPercent > 30) {
                issues.push(`Biaya penyimpanan (${holdingPercent.toFixed(1)}% dari omzet) tidak wajar. Seharusnya < 10%`);
            }
        }

        // 2. Biaya penyimpanan > biaya pemesanan 100x (tidak seimbang)
        if (holdingCost > 0 && orderingCost > 0 && holdingCost > orderingCost * 100) {
            issues.push(`Biaya penyimpanan terlalu besar dibanding biaya pemesanan`);
        }

        // 3. Omzet terlalu kecil dibanding modal (turnover rendah)
        if (modal > 0 && omzet > 0) {
            const turnover = (omzet / modal) * 100;
            if (turnover < 10) { // Kurang dari 10% ROI
                issues.push(`ROI rendah (${turnover.toFixed(1)}%), modal tidak efisien`);
            }
        }

        // 4. Total biaya > keuntungan kotor
        const totalCost = holdingCost + orderingCost;
        const grossProfit = data.keuntungan_kotor || 0;
        if (totalCost > grossProfit) {
            issues.push(`Total biaya operasional melebihi keuntungan kotor`);
        }

        if (issues.length > 0) {
            return {
                isUnrealistic: true,
                message: issues.join('<br>')
            };
        }

        return { isUnrealistic: false, message: '' };
    }
});
</script>
@endsection
