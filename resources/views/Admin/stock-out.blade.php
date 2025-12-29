@extends('Layouts.Base')

@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-box-open pr-2 text-primary"></i> Riwayat Stok Keluar
            </h3>
             <button onclick="reloadTable()" class="btn btn-sm btn-light border">
                <i class="fa-solid fa-sync"></i> Refresh
            </button>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <h6>Daftar Stok Keluar</h6>
                <div class="table-responsive">
                   <table id="tableStokKeluar" class="table table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">No</th>
                                <th class="border-0">Waktu Keluar</th>
                                <th class="border-0">No. Nota</th>
                                <th class="border-0">Nama Produk</th>
                                <th class="border-0">Qty Keluar</th>
                                <th class="border-0">Pembeli</th>
                                <th class="border-0">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const table = $('#tableStokKeluar').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: `${appUrl}/v1/permintaan/stock-out/`,
                type: 'GET'
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'dikeluarkan_pada',
                    render: (data) => {
                        return `<small class="fw-bold">${new Date(data).toLocaleDateString('id-ID')}</small><br>` +
                               `<small class="text-muted">${new Date(data).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})} WIB</small>`;
                    }
                },
                {
                    data: 'permintaan.nomor_permintaan',
                    className: 'fw-bold text-primary',
                    defaultContent: '<span class="text-muted small">Manual</span>'
                },
                {
                    data: 'master_data.nama',
                    render: (data, type, row) => {
                        return `<span class="text-dark fw-bold">${data}</span>`;
                    }
                },
                {
                    data: 'jumlah',
                    render: (data, type, row) => {
                        return `<span class="text-danger fw-bold">-${parseFloat(data)}</span> <small class="text-muted">${row.master_data.satuan}</small>`;
                    }
                },
                {
                    data: 'permintaan.user.nama',
                    defaultContent: '<span class="text-muted">-</span>'
                },
                {
                    data: 'keterangan',
                    render: (data) => `<small class="text-muted">${data || '-'}</small>`
                }
            ],
            language: {
                emptyTable: "Belum ada riwayat stok keluar"
            },
            order: [[1, 'desc']] // Urutkan berdasarkan waktu terbaru
        });

        window.reloadTable = function() {
            table.ajax.reload();
        }
    });
</script>


@endsection
