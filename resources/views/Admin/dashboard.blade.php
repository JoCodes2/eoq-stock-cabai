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
            <h4 id="txt_profit" class="fw-bold text-info">Rp 0</h4>
            <small id="txt_gross_profit" class="text-[10px] text-muted">Kotor: Rp 0</small>
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

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">TOTAL BIAYA PENYIMPANAN</small>
                    <h5 id="txt_holding" class="fw-bold text-warning mb-0">Rp 0</h5>
                </div>
                <i class="fas fa-warehouse fa-2x text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card border-0 shadow-sm p-3 bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted fw-bold">TOTAL BIAYA PEMESANAN</small>
                    <h5 id="txt_ordering" class="fw-bold text-orange mb-0" style="color: #fd7e14;">Rp 0</h5>
                </div>
                <i class="fas fa-truck-loading fa-2x opacity-50" style="color: #fd7e14;"></i>
            </div>
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

        const summary = res.summary || {};

        // 1. Update Widget Ringkasan Finansial
        // Menggunakan total_modal_stok agar sinkron dengan backend
        $('#txt_modal').text('Rp ' + parseFloat(summary.total_modal_stok || 0).toLocaleString('id-ID'));
        $('#txt_omzet').text('Rp ' + parseFloat(summary.omzet || 0).toLocaleString('id-ID'));
        $('#txt_profit').text('Rp ' + parseFloat(summary.keuntungan_bersih || 0).toLocaleString('id-ID'));
        $('#txt_gross_profit').text('Kotor: Rp ' + parseFloat(summary.keuntungan_kotor || 0).toLocaleString('id-ID'));

        // 2. Update Widget Biaya Operasional
        // Mengambil dari dalam objek biaya_operasional sesuai struktur backend baru
        const biayaOp = summary.biaya_operasional || {};
        $('#txt_holding').text('Rp ' + parseFloat(biayaOp.penyimpanan || 0).toLocaleString('id-ID'));
        $('#txt_ordering').text('Rp ' + parseFloat(biayaOp.pemesanan || 0).toLocaleString('id-ID'));

        // 3. Update Status Reorder
        $('#txt_reorder').text((summary.perlu_reorder || 0) + ' Produk');

        // 4. Render Charts Produk
        const eoqData = res.chart_eoq || [];

        // Bersihkan container sebelum render (mencegah duplikasi jika fungsi dipanggil ulang)
        $('#chartContainer').empty();

        if(eoqData.length === 0) {
            $('#chartContainer').append('<div class="col-12 text-center text-muted">Tidak ada data analisis stok.</div>');
        }

        eoqData.forEach((item, index) => {
            const chartId = `chart_item_${index}`;
            // Menggunakan field 'status' yang sudah dikirim backend
            const isCritical = item.status === 'Reorder';
            const statusColor = isCritical ? 'border-danger' : 'border-success';
            const badgeStatus = isCritical
                ? '<span class="badge bg-danger animate-pulse">REORDER</span>'
                : '<span class="badge bg-success">STOK AMAN</span>';

            $('#chartContainer').append(`
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 ${statusColor}" style="border-top: 4px solid !important;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-uppercase">${item.nama}</h6>
                                    <small class="text-muted">Stok Saat Ini: <b>${item.stok_sekarang} Kg</b></small>
                                </div>
                                ${badgeStatus}
                            </div>
                            <div style="height: 180px;">
                                <canvas id="${chartId}"></canvas>
                            </div>
                            <div class="mt-3 p-2 bg-light rounded">
                                <div class="d-flex justify-content-between text-[11px] mb-1">
                                    <span>Saran Pembelian (EOQ)</span>
                                    <span class="fw-bold text-primary">${item.eoq} Kg</span>
                                </div>
                                <div class="d-flex justify-content-between text-[11px]">
                                    <span>Titik Pesan (ROP)</span>
                                    <span class="fw-bold text-orange">${item.rop} Kg</span>
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
                    labels: ['Stok', 'ROP', 'EOQ'],
                    datasets: [{
                        data: [item.stok_sekarang, item.rop, item.eoq],
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
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    });
});
</script>
@endsection
