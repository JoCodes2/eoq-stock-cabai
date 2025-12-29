@extends('Layouts.Base')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">TOTAL MODAL</small>
            <h4 id="txt_modal" class="fw-bold text-primary">Rp 0</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">TOTAL OMZET</small>
            <h4 id="txt_omzet" class="fw-bold text-success">Rp 0</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">KEUNTUNGAN</small>
            <h4 id="txt_profit" class="fw-bold text-info">Rp 0</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <small class="text-muted fw-bold">STOK KRITIS</small>
            <h4 id="txt_reorder" class="fw-bold text-danger">0 Produk</h4>
        </div>
    </div>
</div>

<hr>
<h5 class="fw-bold mb-3"><i class="fa fa-chart-pie me-2"></i> Detail Analisis Stok Per Produk</h5>

<div class="row" id="chartContainer">
    <div class="col-12 text-center py-5" id="loader">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 text-muted">Memuat data analisis...</p>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $.get('/v1/dashboard/chart-eoq', function(res) {
        $('#loader').remove(); // Hapus loading

        // 1. Update Widget Atas
        const summary = res.summary || {};
        $('#txt_modal').text('Rp ' + parseFloat(summary.modal || 0).toLocaleString('id-ID'));
        $('#txt_omzet').text('Rp ' + parseFloat(summary.omzet || 0).toLocaleString('id-ID'));
        $('#txt_profit').text('Rp ' + parseFloat(summary.keuntungan || 0).toLocaleString('id-ID'));
        $('#txt_reorder').text((summary.perlu_reorder || 0) + ' Produk');

        // 2. Loop Setiap Produk untuk Dibuatkan Chart Sendiri
        const eoqData = res.chart_eoq || [];

        eoqData.forEach((item, index) => {
            const chartId = `chart_item_${index}`;
            const statusColor = item.stok_sekarang <= item.rop ? 'border-danger' : 'border-success';
            const badgeStatus = item.stok_sekarang <= item.rop ? '<span class="badge bg-danger">REORDER</span>' : '<span class="badge bg-success">AMAN</span>';

            // Tambahkan HTML Card untuk tiap produk
            $('#chartContainer').append(`
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 ${statusColor}" style="border-left: 5px solid !important;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0">${item.nama}</h6>
                                    <small class="text-muted">Stok: ${item.stok_sekarang} Kg</small>
                                </div>
                                ${badgeStatus}
                            </div>
                            <div style="height: 200px;">
                                <canvas id="${chartId}"></canvas>
                            </div>
                            <div class="mt-3 pt-2 border-top">
                                <small class="d-block">Saran Belanja (EOQ): <b>${item.eoq} Kg</b></small>
                                <small class="d-block">Batas Reorder (ROP): <b>${item.rop} Kg</b></small>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            // Buat Chart Batang Tunggal untuk produk ini
            const ctx = document.getElementById(chartId).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Stok', 'ROP', 'EOQ'],
                    datasets: [{
                        label: 'Jumlah (Kg)',
                        data: [item.stok_sekarang, item.rop, item.eoq],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)', // Biru (Stok)
                            'rgba(255, 159, 64, 0.7)', // Oranye (ROP)
                            'rgba(75, 192, 192, 0.7)'  // Hijau (EOQ)
                        ],
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        });
    });
});
</script>
@endsection
