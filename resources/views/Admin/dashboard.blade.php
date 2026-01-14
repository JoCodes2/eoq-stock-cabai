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
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Total investasi dalam stok barang
            </small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold">TOTAL OMZET (SELESAI)</small>
            <h4 id="txt_omzet" class="fw-bold text-success">Rp 0</h4>
            <small class="text-[10px] text-muted">*Hanya pesanan berstatus selesai</small>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Total pendapatan dari penjualan yang sudah selesai
            </small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold">KEUNTUNGAN BERSIH</small>
            <h4 id="txt_profit" class="fw-bold text-primary">Rp 0</h4>
            <small id="txt_margin" class="text-[10px] text-muted">Margin: 0%</small>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Keuntungan setelah dikurangi semua biaya
            </small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 h-100">
            <small class="text-muted fw-bold">STOK KRITIS (ROP)</small>
            <h4 id="txt_reorder" class="fw-bold text-danger">0 Produk</h4>
            <small class="text-[10px] text-muted">Perlu reorder segera</small>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Jumlah produk yang stoknya mencapai titik pesan ulang
            </small>
        </div>
    </div>
</div>

<!-- Alarm untuk Biaya Operasional Rendah -->
<div id="costWarning" class="row mb-4" style="display: none;">
    <div class="col-12">
        <div class="alert alert-info border-info">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">ℹ️ INFORMASI: Biaya Operasional Rendah</h6>
                    <p class="mb-2" id="costWarningMessage">
                        Biaya operasional Anda sangat rendah dibandingkan skala bisnis.
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <small class="d-block"><strong>Data Saat Ini:</strong></small>
                            <small class="d-block">• Biaya Penyimpanan: Rp <span id="debug_holding">0</span>/tahun</small>
                            <small class="d-block">• Rata-rata per produk: Rp <span id="debug_holding_per">0</span>/tahun</small>
                            <small class="d-block">• Jumlah Produk EOQ: <span id="debug_eoq_count">0</span></small>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block"><strong>Rekomendasi:</strong></small>
                            <small class="d-block">• Biaya penyimpanan biasanya 15-25% dari nilai stok</small>
                            <small class="d-block">• Nilai stok Anda: Rp <span id="debug_stok_value">0</span></small>
                            <small class="d-block">• Seharusnya: Rp <span id="debug_recommended">0</span>/tahun</small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" onclick="$('#costWarning').hide()"></button>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Keuntungan -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">KEUNTUNGAN KOTOR</small>
                    <h5 id="txt_gross_profit" class="fw-bold text-info mb-0">Rp 0</h5>
                    <small id="txt_gross_margin" class="text-[10px] text-muted">Margin Kotor: 0%</small>
                </div>
                <i class="fas fa-chart-line fa-2x text-info opacity-50"></i>
            </div>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Omzet - HPP (belum dikurangi biaya operasional)
            </small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">BIAYA PENYIMPANAN</small>
                    <h5 id="txt_holding" class="fw-bold text-warning mb-0">Rp 0</h5>
                    <small id="txt_holding_percent" class="text-[10px] text-muted">0% dari Omzet</small>
                </div>
                <i class="fas fa-warehouse fa-2x text-warning opacity-50"></i>
            </div>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Biaya simpan stok per tahun (dari pengaturan EOQ)
            </small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">BIAYA PEMESANAN</small>
                    <h5 id="txt_ordering" class="fw-bold text-orange mb-0" style="color: #fd7e14;">Rp 0</h5>
                    <small id="txt_ordering_percent" class="text-[10px] text-muted">0% dari Omzet</small>
                </div>
                <i class="fas fa-truck-loading fa-2x opacity-50" style="color: #fd7e14;"></i>
            </div>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Biaya pesan stok per transaksi (dari pengaturan EOQ)
            </small>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm p-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">TOTAL BIAYA OPS</small>
                    <h5 id="txt_total_operational" class="fw-bold text-danger mb-0">Rp 0</h5>
                    <small id="txt_total_percent" class="text-[10px] text-muted">0% dari Omzet</small>
                </div>
                <i class="fas fa-calculator fa-2x text-danger opacity-50"></i>
            </div>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Biaya Penyimpanan + Biaya Pemesanan
            </small>
        </div>
    </div>
</div>

<!-- Informasi Tambahan -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">RATIO MODAL/OMZET</small>
            <h5 id="txt_modal_ratio" class="fw-bold mb-0">0%</h5>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Persentase omzet terhadap modal
            </small>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">BIAYA PENYIMPANAN/TAHUN</small>
            <h5 id="txt_holding_yearly" class="fw-bold text-warning mb-0">Rp 0</h5>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Total biaya simpan per tahun
            </small>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">AVG BIAYA/PRODUK</small>
            <h5 id="txt_holding_per_product" class="fw-bold text-warning mb-0">Rp 0</h5>
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Rata-rata biaya simpan per produk/tahun
            </small>
        </div>
    </div>
</div>

<!-- Breakdown Perhitungan -->
<div class="row mb-4" id="breakdownContainer">
    <!-- Akan diisi oleh JavaScript -->
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
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Persentase omzet terhadap modal. ROI 100% = modal kembali
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
            <small class="text-[10px] text-muted d-block mt-2">
                <i class="fas fa-info-circle me-1"></i> Semakin tinggi persentase = biaya operasional semakin efisien
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
        const debug = s.debug_info || {};

        // Format angka dengan fungsi helper
        function formatRupiah(angka) {
            if (angka === null || angka === undefined) return '0';
            const num = parseFloat(angka);
            if (isNaN(num)) return '0';
            return num.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        function formatCurrency(angka) {
            return 'Rp ' + formatRupiah(angka);
        }

        // Check for low operational costs
        checkLowOperationalCosts(s, debug);

        // Update Finansial
        const modal = s.modal_investisi || s.modal || 0;
        $('#txt_modal').text(formatCurrency(modal));

        $('#txt_omzet').text(formatCurrency(s.omzet || 0));

        // Update keuntungan bersih
        const keuntunganBersih = Math.max(parseFloat(s.keuntungan_bersih || 0), 0);
        $('#txt_profit').text(formatCurrency(keuntunganBersih));

        // Hitung margin
        let marginPercent = 0;
        if (s.omzet > 0) {
            marginPercent = ((keuntunganBersih / s.omzet) * 100).toFixed(2);
        }
        $('#txt_margin').text(`Margin: ${marginPercent}%`);

        // Warna margin
        if (marginPercent >= 20) {
            $('#txt_margin').removeClass('text-muted').addClass('text-success');
        } else if (marginPercent >= 10) {
            $('#txt_margin').removeClass('text-muted').addClass('text-warning');
        } else if (marginPercent > 0) {
            $('#txt_margin').removeClass('text-muted').addClass('text-danger');
        }

        // Update keuntungan kotor
        const keuntunganKotor = s.keuntungan_kotor || 0;
        $('#txt_gross_profit').text(formatCurrency(keuntunganKotor));

        // Margin kotor
        let grossMarginPercent = 0;
        if (s.omzet > 0 && keuntunganKotor) {
            grossMarginPercent = ((keuntunganKotor / s.omzet) * 100).toFixed(2);
        }
        $('#txt_gross_margin').text(`Margin Kotor: ${grossMarginPercent}%`);

        // Update Biaya
        const biayaPenyimpanan = parseFloat(s.biaya_penyimpanan || 0);
        const biayaPemesanan = parseFloat(s.biaya_pemesanan || 0);
        const totalBiayaOperasional = biayaPenyimpanan + biayaPemesanan;

        $('#txt_holding').text(formatCurrency(biayaPenyimpanan));
        $('#txt_ordering').text(formatCurrency(biayaPemesanan));
        $('#txt_total_operational').text(formatCurrency(totalBiayaOperasional));

        // Update informasi tambahan
        const modalRatio = modal > 0 ? ((s.omzet / modal) * 100).toFixed(2) : 0;
        $('#txt_modal_ratio').text(`${modalRatio}%`);

        const biayaPenyimpananTahunan = biayaPenyimpanan * 12;
        $('#txt_holding_yearly').text(formatCurrency(biayaPenyimpananTahunan));

        const avgBiayaPerProduk = debug.jumlah_produk_eoq > 0 ? (biayaPenyimpananTahunan / debug.jumlah_produk_eoq) : 0;
        $('#txt_holding_per_product').text(formatCurrency(avgBiayaPerProduk));

        // Hitung persentase biaya dari omzet
        let holdingPercent = 0;
        let orderingPercent = 0;
        let totalPercent = 0;

        if (s.omzet > 0) {
            holdingPercent = ((biayaPenyimpanan / s.omzet) * 100).toFixed(4);
            orderingPercent = ((biayaPemesanan / s.omzet) * 100).toFixed(3);
            totalPercent = ((totalBiayaOperasional / s.omzet) * 100).toFixed(4);
        }

        $('#txt_holding_percent').text(`${holdingPercent}% dari Omzet`);
        $('#txt_ordering_percent').text(`${orderingPercent}% dari Omzet`);
        $('#txt_total_percent').text(`${totalPercent}% dari Omzet`);

        // Warna peringatan
        const setPercentColor = (element, percent) => {
            if (percent > 10) {
                element.removeClass('text-muted text-success').addClass('text-danger');
            } else if (percent > 5) {
                element.removeClass('text-muted text-success').addClass('text-warning');
            } else if (percent > 0) {
                element.removeClass('text-muted').addClass('text-success');
            }
        };

        setPercentColor($('#txt_holding_percent'), parseFloat(holdingPercent));
        setPercentColor($('#txt_ordering_percent'), parseFloat(orderingPercent));
        setPercentColor($('#txt_total_percent'), parseFloat(totalPercent));

        // Update stok kritis
        const reorderCount = s.perlu_reorder || 0;
        $('#txt_reorder').text(reorderCount + ' Produk');
        if (reorderCount > 0) {
            $('#txt_reorder').addClass('animate-pulse');
        }

        // Hitung ROI
        let roiPercent = 0;
        if (modal > 0 && s.omzet > 0) {
            roiPercent = ((s.omzet / modal) * 100).toFixed(2);
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
        if (s.omzet > 0 && totalBiayaOperasional > 0) {
            efisiensiPercent = (100 - ((totalBiayaOperasional / s.omzet) * 100)).toFixed(2);
            efisiensiPercent = Math.max(efisiensiPercent, 0);
        }

        $('#txt_efisiensi_percent').text(efisiensiPercent + '%');
        $('#efisiensi_progress').css('width', Math.min(efisiensiPercent, 100) + '%');
        $('#txt_efisiensi_detail').text(`Total Biaya Ops: ${formatCurrency(totalBiayaOperasional)}`);

        // TAMPILKAN BREAKDOWN PERHITUNGAN
        $('#breakdownContainer').html(`
            <div class="col-12">
                <div class="card border-0 shadow-sm p-3 bg-light">
                    <h6 class="fw-bold mb-3"><i class="fas fa-calculator text-primary me-2"></i> Breakdown Perhitungan Keuntungan</h6>
                    <div class="row text-center">
                        <div class="col-md-2 mb-2">
                            <small class="text-muted d-block">OMZET</small>
                            <div class="fw-bold text-success fs-6">${formatCurrency(s.omzet || 0)}</div>
                            <small class="text-muted">(100%)</small>
                        </div>
                        <div class="col-md-2 mb-2">
                            <small class="text-muted d-block">HPP</small>
                            <div class="fw-bold text-danger fs-6">- ${formatCurrency(s.hpp || 0)}</div>
                            <small class="text-muted">(${s.omzet > 0 ? ((s.hpp/s.omzet)*100).toFixed(1) : 0}%)</small>
                        </div>
                        <div class="col-md-2 mb-2">
                            <small class="text-muted d-block">KEUNTUNGAN KOTOR</small>
                            <div class="fw-bold text-info fs-6">${formatCurrency(keuntunganKotor)}</div>
                            <small class="text-muted">(${grossMarginPercent}%)</small>
                        </div>
                        <div class="col-md-2 mb-2">
                            <small class="text-muted d-block">BIAYA PENYIMPANAN</small>
                            <div class="fw-bold text-warning fs-6">- ${formatCurrency(biayaPenyimpanan)}</div>
                            <small class="text-muted">(${holdingPercent}%)</small>
                        </div>
                        <div class="col-md-2 mb-2">
                            <small class="text-muted d-block">BIAYA PEMESANAN</small>
                            <div class="fw-bold text-orange fs-6">- ${formatCurrency(biayaPemesanan)}</div>
                            <small class="text-muted">(${orderingPercent}%)</small>
                        </div>
                        <div class="col-md-2 mb-2">
                            <small class="text-muted d-block">TOTAL BIAYA OPS</small>
                            <div class="fw-bold text-danger fs-6">- ${formatCurrency(totalBiayaOperasional)}</div>
                            <small class="text-muted">(${totalPercent}%)</small>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-3 border-top">
                        <div class="fw-bold text-primary fs-5">
                            = KEUNTUNGAN BERSIH: ${formatCurrency(keuntunganBersih)}
                        </div>
                        <small class="text-muted">(Margin Bersih: ${marginPercent}% dari Omzet)</small>
                        <div class="mt-2">
                            <span class="badge ${marginPercent >= 20 ? 'bg-success' : marginPercent >= 10 ? 'bg-warning' : 'bg-danger'}">
                                ${marginPercent >= 20 ? 'SANGAT BAIK' : marginPercent >= 10 ? 'BAIK' : 'PERLU DITINGKATKAN'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `);

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
            const isCritical = parseFloat(item.stok_sekarang) <= parseFloat(item.rop);
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

    // Fungsi untuk mengecek biaya operasional yang terlalu rendah
    function checkLowOperationalCosts(s, debug) {
        const biayaPenyimpananTahunan = parseFloat(debug.total_biaya_penyimpanan_tahunan || 0);
        const nilaiStokTotal = parseFloat(debug.nilai_stok_total || 0);
        const jumlahProduk = parseInt(debug.jumlah_produk_eoq || 0);

        // Hitung rekomendasi biaya penyimpanan (15-25% dari nilai stok)
        const rekomendasiMin = nilaiStokTotal * 0.15; // 15%
        const rekomendasiMax = nilaiStokTotal * 0.25; // 25%
        const rekomendasiRata = (rekomendasiMin + rekomendasiMax) / 2;

        // Tampilkan warning jika biaya terlalu rendah
        if (biayaPenyimpananTahunan > 0 && rekomendasiRata > 0 &&
            biayaPenyimpananTahunan < rekomendasiMin * 0.1) { // Kurang dari 10% dari minimum

            $('#debug_holding').text(formatRupiah(biayaPenyimpananTahunan));
            $('#debug_holding_per').text(formatRupiah(debug.avg_biaya_penyimpanan_per_produk || 0));
            $('#debug_eoq_count').text(jumlahProduk);
            $('#debug_stok_value').text(formatRupiah(nilaiStokTotal));
            $('#debug_recommended').text(formatRupiah(rekomendasiRata));

            $('#costWarningMessage').html(`
                Biaya penyimpanan Anda (Rp ${formatRupiah(biayaPenyimpananTahunan)}/tahun)
                sangat rendah dibandingkan nilai stok (Rp ${formatRupiah(nilaiStokTotal)}).
                <br>Untuk akurasi EOQ, biaya penyimpanan seharusnya sekitar 15-25% dari nilai stok.
            `);

            $('#costWarning').show();
        }
    }
});
</script>
@endsection
