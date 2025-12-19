@extends('Layouts.Base')

@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-book pr-2"></i> Produk
            </h3>
            <button class="btn btn-primary btn-sm" id="myBtn"><i class="fas fa-plus"></i> Tambah Produk</button>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <h6>Daftar Produk</h6>
                <table id="dataMaster" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="upsertDataModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="upsertDataForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <!-- Input Nama Produk -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" id="nama" name="nama" class="form-control">
                            <small id="nama-error" class="text-danger"></small>
                        </div>

                        <!-- Input Satuan -->
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" id="satuan" name="satuan" class="form-control">
                            <small id="satuan-error" class="text-danger"></small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="simpanData" class="btn btn-primary">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            /* ===============================
               1. INISIALISASI DATATABLE
            =============================== */
            let table = $('#dataMaster').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                order: []
            });

            /* ===============================
               2. AMBIL DATA MASTER
            =============================== */
            function getData() {
                $.ajax({
                    url: '/v1/master',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        table.clear();

                        $.each(response.data, function(index, item) {
                            table.row.add([
                                index + 1,
                                item.nama,
                                item.satuan,
                                item.jumlah ?? 0, // 🔥 INI PENTING

                                `
                        <button class="btn btn-outline-primary btn-sm edit-btn" data-id="${item.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm delete-confirm" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                        `
                            ]);
                        });

                        table.draw(false); // pagination tetap
                    },
                    error: function() {
                        console.log('Gagal mengambil data master');
                    }
                });
            }

            // Load pertama kali
            getData();

            /* ===============================
               3. SIMPAN (CREATE & UPDATE)
            =============================== */
            $(document).on('click', '#simpanData', function(e) {
                e.preventDefault();
                $('.text-danger').text('');

                let id = $('#id').val();
                let url = id ?
                    `/v1/master/update/${id}` :
                    '/v1/master/create';

                let formData = new FormData($('#upsertDataForm')[0]);

                loadingAllert();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close();

                        // VALIDASI
                        if (response.code === 422) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key + '-error').text(value[0]);
                            });
                            return;
                        }

                        // BERHASIL
                        if (response.code === 200 || response.status === 'success') {
                            successAlert('Data berhasil disimpan!');
                            $('#upsertDataModal').modal('hide');
                            $('#upsertDataForm')[0].reset();
                            $('#id').val('');
                            getData(); // 🔥 refresh tabel
                            return;
                        }

                        errorAlert();
                    },
                    error: function() {
                        Swal.close();
                        errorAlert();
                    }
                });
            });

            /* ===============================
               4. EDIT DATA
            =============================== */
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: `/v1/master/get/${id}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#upsertDataModal').modal('show');
                        $('#id').val(response.data.id);
                        $('#nama').val(response.data.nama);
                        $('#satuan').val(response.data.satuan);
                    }
                });
            });

            /* ===============================
               5. DELETE DATA
            =============================== */
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                confirmAlert('Apakah Anda yakin ingin menghapus data?', function() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/master/delete/${id}`,
                        dataType: 'json',
                        success: function(response) {
                            if (response.code === 200 || response.status ===
                                'success') {
                                successAlert('Data berhasil dihapus!');
                                getData(); // 🔥 refresh tabel
                            } else {
                                errorAlert();
                            }
                        },
                        error: function() {
                            errorAlert();
                        }
                    });
                });
            });

            /* ===============================
               6. MODAL TAMBAH
            =============================== */
            $(document).on('click', '#myBtn', function() {
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
                $('.text-danger').text('');
                $('#upsertDataModal').modal('show');
            });

            $('#upsertDataModal').on('hidden.bs.modal', function() {
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
            });

            /* ===============================
               7. ALERT UTILITIES
            =============================== */
            function successAlert(message) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1200
                });
            }

            function errorAlert() {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1200
                });
            }

            function confirmAlert(message, callback) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) callback();
                });
            }

            function loadingAllert() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            }

        });
    </script>
@endsection
