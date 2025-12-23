@extends('Layouts.Base')

@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-pepper-hot pr-2"></i> Master Data Cabai
            </h3>
            <button class="btn btn-primary btn-sm" id="myBtn"><i class="fas fa-plus"></i> Tambah Produk</button>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <h6>Daftar Stok & Harga Cabai</h6>
                <div class="table-responsive">
                    <table id="dataMaster" class="table table-bordered table-striped w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Satuan</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Min.</th>
                                <th>Harga Jual (per kg)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upsertDataModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="upsertDataForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">

                        <div class="mb-3">
                            <label for="nama" class="form-label font-weight-bold">Nama Produk</label>
                            <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: Cabai Merah Keriting">
                            <small id="nama-error" class="text-danger"></small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="satuan" class="form-label font-weight-bold">Satuan</label>
                                <input type="text" id="satuan" name="satuan" class="form-control" placeholder="kg">
                                <small id="satuan-error" class="text-danger"></small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stok_minimum" class="form-label font-weight-bold">Stok Minimum</label>
                                <input type="number" id="stok_minimum" name="stok_minimum" class="form-control" placeholder="0">
                                <small id="stok_minimum-error" class="text-danger"></small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="harga_jual" class="form-label font-weight-bold">Harga Jual ke Pembeli (Rp)</label>
                            <input type="number" id="harga_jual" name="harga_jual" class="form-control" placeholder="0">
                            <small id="harga_jual-error" class="text-danger"></small>
                        </div>

                        <hr>
                        <div id="info-harga-beli" style="display:none;">
                            <div class="alert alert-secondary py-2">
                                <label class="form-label mb-0 small text-uppercase">Harga Beli Terakhir (Modal dari Petani)</label>
                                <p id="harga_beli_info" class="font-weight-bold mb-0 text-dark">Rp 0</p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="simpanData" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            /* 1. INISIALISASI DATATABLE */
            let table = $('#dataMaster').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
            });

            /* 2. FORMAT RUPIAH */
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
            }

            /* 3. AMBIL DATA DARI API */
            function getData() {
                $.ajax({
                    url: '/v1/master',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        table.clear();
                        $.each(response.data, function(index, item) {

                            // --- PERBAIKAN DI SINI ---
                            // Tambahkan text-white untuk memastikan teks terlihat
                            let stokStatus = (parseFloat(item.jumlah) <= parseFloat(item.stok_minimum))
                                ? `<span class="badge bg-danger text-white p-2">${item.jumlah}</span>`
                                : `<span class="badge bg-success text-white p-2">${item.jumlah}</span>`;
                            // -------------------------

                            table.row.add([
                                index + 1,
                                item.nama,
                                item.satuan,
                                `<div class="text-center">${stokStatus}</div>`,
                                item.stok_minimum ?? 0,
                                formatRupiah(item.harga_jual || 0),
                                `
                                <div class="text-center">
                                    <button class="btn btn-outline-primary btn-sm edit-btn" data-id="${item.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-confirm" data-id="${item.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                `
                            ]);
                        });
                        table.draw(false);
                    },
                    error: function() {
                        console.error('Gagal mengambil data master');
                    }
                });
            }

            getData();

            /* 4. SIMPAN DATA (CREATE & UPDATE) */
            $(document).on('click', '#simpanData', function(e) {
                e.preventDefault();
                $('.text-danger').text('');

                let id = $('#id').val();
                let url = id ? `/v1/master/update/${id}` : '/v1/master/create';
                let formData = new FormData($('#upsertDataForm')[0]);

                loadingAllert();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        Swal.close();
                        if (response.code === 422) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key + '-error').text(value[0]);
                            });
                            return;
                        }
                        successAlert('Data berhasil diproses!');
                        $('#upsertDataModal').modal('hide');
                        setTimeout(function() {
                                location.reload();
                            }, 1200);
                    },
                    error: function() {
                        Swal.close();
                        errorAlert();
                    }
                });
            });

            /* 5. EDIT DATA */
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $('.text-danger').text('');

                $.ajax({
                    url: `/v1/master/get/${id}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#modalTitle').text('Edit Data Produk');
                        $('#upsertDataModal').modal('show');
                        $('#id').val(response.data.id);
                        $('#nama').val(response.data.nama);
                        $('#satuan').val(response.data.satuan);
                        $('#stok_minimum').val(response.data.stok_minimum);
                        $('#harga_jual').val(response.data.harga_jual);

                        // Tampilkan Info Harga Beli Terakhir
                        $('#info-harga-beli').show();
                        $('#harga_beli_info').text(formatRupiah(response.data.harga_beli_terakhir || 0));
                    }
                });
            });

            /* 6. HAPUS DATA */
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');
                confirmAlert('Data produk akan dihapus permanen!', function() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/master/delete/${id}`,
                        success: function() {
                            successAlert('Terhapus!');
                            setTimeout(function() {
                                location.reload();
                            }, 1200);
                        },
                        error: function() { errorAlert(); }
                    });
                });
            });

            /* 7. MODAL TAMBAH TRIGGER */
            $(document).on('click', '#myBtn', function() {
                $('#modalTitle').text('Tambah Produk Baru');
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
                $('#info-harga-beli').hide();
                $('.text-danger').text('');
                $('#upsertDataModal').modal('show');
            });

            /* 8. ALERTS UTILITIES */
            function successAlert(msg) {
                Swal.fire({ icon: 'success', title: msg, showConfirmButton: false, timer: 1500 });
            }
            function errorAlert() {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan sistem!' });
            }
            function confirmAlert(msg, callback) {
                Swal.fire({
                    title: 'Apakah anda yakin?', text: msg, icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                }).then((result) => { if (result.isConfirmed) callback(); });
            }
            function loadingAllert() {
                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            }
        });
    </script>
@endsection
