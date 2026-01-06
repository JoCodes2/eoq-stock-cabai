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
                                <th class="border-0">Satuan</th>
                                <th class="border-0">Pembeli</th>
                                <th class="border-0">Total Harga</th>
                                <th class="border-0">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Fungsi untuk memformat tanggal
        function formatDateTime(dateTimeString) {
            const date = new Date(dateTimeString);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        // Fungsi untuk memformat angka ke format Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Fungsi untuk mengambil dan menampilkan data
        function getData() {
            $.ajax({
                url: `${appUrl}/v1/permintaan/stock-out/`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {

                    if (response.code === 200 && response.data && response.data.length > 0) {
                        populateTable(response.data);
                    } else {
                        showEmptyTable();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Gagal mengambil data stok keluar:', error);
                    showErrorTable();
                }
            });
        }

        // Fungsi untuk mengisi tabel dengan data
        function populateTable(data) {
            const tbody = $('#tableStokKeluar tbody');
            tbody.empty(); // Kosongkan tabel terlebih dahulu

            data.forEach((item, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${formatDateTime(item.dikeluarkan_pada)}</td>
                        <td>
                            <span class="badge bg-primary">${item.permintaan.nomor_permintaan}</span>
                        </td>
                        <td>
                            <strong>${item.master_data.nama}</strong><br>
                            <small class="text-muted">Kode: ${item.master_data.kode}</small>
                        </td>
                        <td>
                            <span class="badge bg-danger">${item.jumlah}</span>
                        </td>
                        <td>${item.master_data.satuan}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <strong>${item.permintaan.user.nama}</strong>
                                <small class="text-muted">${item.permintaan.user.email}</small>
                                <small class="text-muted">${item.permintaan.user.no_hp}</small>
                            </div>
                        </td>
                        <td>
                            <strong>${formatRupiah(item.permintaan.total_harga_nota)}</strong>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>${item.keterangan}</span>
                                <small class="text-muted">
                                    Status:
                                    <span class="badge ${item.permintaan.status === 'selesai' ? 'bg-success' : 'bg-warning'}">
                                        ${item.permintaan.status}
                                    </span>
                                </small>
                                ${item.permintaan.catatan ?
                                    `<small class="text-muted">Catatan: ${item.permintaan.catatan}</small>` :
                                    ''}
                            </div>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });

            // Inisialisasi DataTable jika diperlukan
            initializeDataTable();
        }

        // Fungsi untuk menampilkan tabel kosong
        function showEmptyTable() {
            const tbody = $('#tableStokKeluar tbody');
            tbody.empty();
            tbody.append(`
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data stok keluar</h5>
                            <p class="text-muted">Belum ada transaksi stok keluar yang tercatat</p>
                        </div>
                    </td>
                </tr>
            `);
        }

        // Fungsi untuk menampilkan error
        function showErrorTable() {
            const tbody = $('#tableStokKeluar tbody');
            tbody.empty();
            tbody.append(`
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h5 class="text-danger">Gagal memuat data</h5>
                            <p class="text-muted">Terjadi kesalahan saat mengambil data stok keluar</p>
                            <button onclick="getData()" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-redo"></i> Coba Lagi
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        }

        // Fungsi untuk menginisialisasi DataTable
        function initializeDataTable() {
            if ($.fn.DataTable.isDataTable('#tableStokKeluar')) {
                $('#tableStokKeluar').DataTable().destroy();
            }

            $('#tableStokKeluar').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                },
                order: [[1, 'desc']], // Urutkan berdasarkan waktu keluar (terbaru)
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>tip',
                initComplete: function() {
                    $('.dataTables_filter input').attr('placeholder', 'Cari...');
                }
            });
        }

        // Fungsi untuk reload tabel
        function reloadTable() {
            if ($.fn.DataTable.isDataTable('#tableStokKeluar')) {
                $('#tableStokKeluar').DataTable().destroy();
            }
            $('#tableStokKeluar tbody').empty();
            getData();

            // Tampilkan notifikasi refresh
            toastr.info('Memperbarui data stok keluar...');
        }

        // Panggil fungsi getData saat halaman dimuat
        getData();
    });
</script>

<style>
    #tableStokKeluar th {
        font-weight: 600;
        background-color: #f8f9fa;
        color: #495057;
    }

    #tableStokKeluar td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>
@endsection
