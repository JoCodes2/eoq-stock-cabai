@extends('Layouts.Base')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold text-primary"><i class="fa-solid fa-circle-info me-2"></i> Panduan Parameter EOQ</h5>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <small class="fw-bold d-block text-dark">Biaya Pemesanan (S)</small>
                        <small class="text-muted">Total biaya operasional setiap kali Anda belanja (Bensin, Ongkir, Admin).</small>
                    </div>
                    <div class="col-md-3">
                        <small class="fw-bold d-block text-dark">Biaya Penyimpanan (H)</small>
                        <small class="text-muted">Biaya simpan per kg dalam 1 tahun (Listrik kulkas, risiko layu/busuk).</small>
                    </div>
                    <div class="col-md-3">
                        <small class="fw-bold d-block text-dark">Lead Time</small>
                        <small class="text-muted">Jeda waktu (hari) dari saat Anda memesan hingga barang datang.</small>
                    </div>
                    <div class="col-md-3">
                        <small class="fw-bold d-block text-dark">Safety Stock</small>
                        <small class="text-muted">Jumlah stok cadangan yang harus ada di gudang untuk kondisi darurat.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                 <i class="fa-solid fa-square-root-variable pr-2 text-primary"></i> Analisis & Pengaturan EOQ
            </h3>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <div class="table-responsive">
                      <table id="tableEOQ" class="table table-hover w-100 mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">No</th>
                                    <th>Nama Cabai</th>
                                    <th>Biaya Pesan (S)</th>
                                    <th>Biaya Simpan (H)</th>
                                    <th>
                                        Hasil EOQ (Q)
                                        <i class="fa-solid fa-circle-info text-info" title="Jumlah pembelian paling ekonomis setiap kali Anda belanja"></i>
                                        <br><small class="text-muted fw-normal" style="text-transform: none;">(Berapa banyak yang harus dibeli?)</small>
                                    </th>
                                    <th>
                                        Titik Pesan (ROP)
                                        <i class="fa-solid fa-circle-info text-warning" title="Batas minimal stok di gudang untuk melakukan pemesanan ulang"></i>
                                        <br><small class="text-muted fw-normal" style="text-transform: none;">(Kapan harus beli lagi?)</small>
                                    </th>
                                    <th>Status Stok</th>
                                    <th class="text-center px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark"></tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="modalEditEOQ" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold">Pengaturan Parameter Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditEOQ">
                @csrf
                <input type="hidden" id="edit_id">
                <div class="modal-body p-4">
                    <div class="alert alert-info small border-0">
                        <i class="fa-solid fa-lightbulb me-1"></i> Masukkan estimasi biaya tahunan untuk perhitungan yang akurat.
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">BIAYA PEMESANAN (S) <i class="fa-solid fa-question-circle" title="Biaya tetap setiap kali melakukan satu kali order"></i></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" class="form-control" name="biaya_pemesanan" id="edit_s" placeholder="Contoh: 50000" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">BIAYA PENYIMPANAN (H) <i class="fa-solid fa-question-circle" title="Biaya menyimpan 1kg barang selama 1 tahun"></i></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" class="form-control" name="biaya_penyimpanan" id="edit_h" placeholder="Contoh: 5000" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">LEAD TIME <i class="fa-solid fa-question-circle" title="Berapa hari barang sampai setelah dipesan"></i></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="waktu_tunggu_hari" id="edit_lt" placeholder="Hari" required>
                                <span class="input-group-text bg-white">Hari</span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-muted">SAFETY STOCK <i class="fa-solid fa-question-circle" title="Stok cadangan minimal di gudang"></i></label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="stok_aman" id="edit_ss" placeholder="Kg" required>
                                <span class="input-group-text bg-white">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Update & Hitung Manual Nanti</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // --- Alert Functions ---
        function successAlert(message) {
            Swal.fire({ title: 'Berhasil!', text: message, icon: 'success', showConfirmButton: false, timer: 1500 });
        }
        function errorAlert(message = 'Terjadi kesalahan!', icon = 'error') {
            Swal.fire({ title: 'Error', text: message, icon: icon, confirmButtonColor: '#48ABF7' });
        }
        function loadingAllert() {
            Swal.fire({ title: 'Proses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
        }
        function confirmAlert(message, callback) {
            Swal.fire({
                title: 'Konfirmasi',
                html: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hitung!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#48ABF7'
            }).then((result) => { if (result.isConfirmed) callback(); });
        }

        // --- DataTable ---
        const table = $('#tableEOQ').DataTable({
            processing: true,
            serverSide: false, // Sesuaikan dengan controller Anda
            ajax: { url: '/v1/eoq/config', type: 'GET' },
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + 1, className: 'px-4' },
                {
                    data: 'master_data.nama',
                    render: (data, type, row) => `<strong>${data}</strong><br><small class="text-muted">Stok: ${row.master_data.jumlah} ${row.master_data.satuan}</small>`
                },
                { data: 'biaya_pemesanan', render: (d) => `Rp ${parseFloat(d || 0).toLocaleString('id-ID')}` },
                { data: 'biaya_penyimpanan', render: (d) => `Rp ${parseFloat(d || 0).toLocaleString('id-ID')}` },
                {
                    data: 'nilai_eoq',
                    render: (d, type, row) => {
                        let val = (d && !isNaN(d) && d > 0) ? parseFloat(d).toFixed(2) : "0.00";
                        return `<span class="badge bg-info p-2">${val} ${row.master_data.satuan}</span>`;
                    }
                },
                {
                    data: 'titik_pemesanan_ulang',
                    render: (d, type, row) => {
                        let val = (d && !isNaN(d) && d > 0) ? parseFloat(d).toFixed(2) : "0.00";
                        return `<span class="badge bg-warning text-dark p-2">${val} ${row.master_data.satuan}</span>`;
                    }
                },
                {
                    data: 'status_stok',
                    render: function(data) {
                        let val = data || 'BELUM DIHITUNG';
                        let cls = 'bg-success';
                        if(val === 'REORDER') cls = 'bg-warning text-dark';
                        if(val === 'KRITIS') cls = 'bg-danger';
                        if(val === 'BELUM DIHITUNG') cls = 'bg-secondary';
                        return `<span class="badge ${cls} text-uppercase" style="font-size: 10px;">${val}</span>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center px-4',
                    render: (id, type, row) => `
                        <div class="btn-group">
                            <button title="Ubah Parameter" class="btn btn-sm btn-outline-dark" onclick="editModal('${id}', ${row.biaya_pemesanan}, ${row.biaya_penyimpanan}, ${row.waktu_tunggu_hari}, ${row.stok_aman})"><i class="fa fa-cog"></i></button>
                            <button title="Hitung EOQ Sekarang" class="btn btn-sm btn-primary" onclick="calculate('${id}')"><i class="fa fa-calculator"></i></button>
                        </div>`
                }
            ]
        });

        // --- Action Functions ---
        window.editModal = function(id, s, h, lt, ss) {
            $('#edit_id').val(id);
            $('#edit_s').val(s || 0);
            $('#edit_h').val(h || 0);
            $('#edit_lt').val(lt || 0);
            $('#edit_ss').val(ss || 0);
            $('#modalEditEOQ').modal('show');
        }

        $('#formEditEOQ').on('submit', function(e) {
            e.preventDefault();
            const id = $('#edit_id').val();
            loadingAllert();
            $.post(`/v1/eoq/config/update/${id}`, $(this).serialize(), function(res) {
                Swal.close();
                successAlert(res.message);
                $('#modalEditEOQ').modal('hide');
                table.ajax.reload();
            }).fail(err => { Swal.close(); errorAlert('Gagal memperbarui data.'); });
        });

        window.calculate = function(id) {
            confirmAlert("Sistem akan menghitung EOQ berdasarkan total stok keluar tahun terakhir dan parameter biaya yang Anda masukkan.", () => {
                loadingAllert();
                $.get(`/v1/eoq/calculate/${id}`, function(res) {
                    Swal.close();
                    successAlert(res.message);
                    table.ajax.reload();
                }).fail(err => {
                    Swal.close();
                    errorAlert(err.responseJSON.message || 'Gagal menghitung EOQ.');
                });
            });
        }
    });
</script>

<style>
    .badge { font-weight: 600; letter-spacing: 0.5px; }
    #tableEOQ td { vertical-align: middle; }
    .table thead th { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
    .input-group-text { border-right: none; font-size: 13px; font-weight: bold; color: #6c757d; }
    .input-group .form-control { border-left: none; }
</style>
@endsection
