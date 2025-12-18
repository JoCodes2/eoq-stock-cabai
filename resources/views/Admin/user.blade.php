@extends('Layouts.Base')

@section('content')
    <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h3 class="m-0 font-weight-bold">
                <i class="fa-solid fa-book pr-2"></i> Pengguna
            </h3>
            <button class="btn btn-primary btn-sm" id="myBtn"><i class="fas fa-plus"></i> Tambah Pengguna</button>
        </div>

        <div class="card-body py-2">
            <div class="py-3">
                <h6>Daftar Pengguna</h6>
                <table id="dataUser" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemilik</th>
                            <th>Email</th>
                            <th>Hak Akses</th>
                            <th>Password</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td colspan="8" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upsertDataModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="upsertDataForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">

                                <!-- Nama -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama">
                                    <small id="nama-error" class="text-danger"></small>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                    <small id="email-error" class="text-danger"></small>
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label">Hak Akses</label>
                                    <select class="form-control" name="role" id="role">
                                        <option value="">Pilih Akses</option>
                                        <option value="admin">Admin</option>
                                        <option value="pembeli">Pembeli</option>
                                    </select>
                                    <small id="role-error" class="text-danger"></small>
                                </div>

                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                    <small id="password-error" class="text-danger"></small>
                                </div>

                                <!-- No HP -->
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">No. HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+62</span>
                                        <input type="text" class="form-control" name="no_hp" id="no_hp"
                                            placeholder="8123456789">
                                    </div>
                                    <small id="no_hp-error" class="text-danger"></small>
                                </div>

                                <!-- Alamat -->
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" id="alamat" rows="3"></textarea>
                                    <small id="alamat-error" class="text-danger"></small>
                                </div>

                            </div>
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

            // Ambil data user
            function getData() {
                $.ajax({
                    url: `/v1/user`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        let tableBody = "";
                        $.each(response.data, function(index, item) {
                            tableBody += "<tr>";
                            tableBody += "<td>" + (index + 1) + "</td>";
                            tableBody += "<td>" + item.nama + "</td>";
                            tableBody += "<td>" + item.email + "</td>";
                            tableBody += "<td>" + item.role + "</td>";
                            tableBody += "<td>****</td>"; // disembunyikan
                            tableBody += "<td>" + item.no_hp + "</td>";
                            tableBody += "<td>" + item.alamat + "</td>";

                            tableBody += "<td>";
                            // tableBody +=
                            //     "<button type='button' class='btn btn-outline-primary btn-sm edit-btn' data-id='" +
                            //     item.id + "'><i class='fas fa-edit'></i></button> ";
                            tableBody +=
                                "<button type='button' class='btn btn-outline-danger btn-sm delete-confirm' data-id='" +
                                item.id + "'><i class='fas fa-trash'></i></button>";
                            tableBody += "</td>";
                            tableBody += "</tr>";
                        });

                        $("#dataUser tbody").html(tableBody);

                        $('#dataUser').DataTable({
                            destroy: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            order: []
                        });
                    },
                    error: function() {
                        console.log("Gagal mengambil data dari server");
                    }
                });
            }

            getData();

            // create
            $(document).on('click', '#simpanData', function(e) {
                $('.text-danger').text('');
                e.preventDefault();

                let id = $('#id').val();
                let formData = new FormData($('#upsertDataForm')[0]);
                let url = id ? `/v1/user/update/${id}` : '/v1/user/create';
                let method = id ? 'POST' : 'POST';

                loadingAllert();

                $.ajax({
                    type: method,
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        Swal.close();

                        if (response.code === 422) { // Jika validasi gagal
                            let errors = response.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key + '-error').text(value[0]);
                            });
                        } else if (response.code === 200 || response.status === "success") {
                            successAlert('Data berhasil disimpan!');
                            reloadBrowsers();
                        } else {
                            errorAlert();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.close();
                        errorAlert();
                    }
                });
            });

            // // Edit data button click handler
            // $(document).on('click', '.edit-btn', function() {
            //     let id = $(this).data('id');
            //     $.ajax({
            //         url: `/v1/user/get/${id}`,
            //         method: "GET",
            //         dataType: "json",
            //         success: function(response) {
            //             console.log(response);
            //             $('#upsertDataModal').modal('show');
            //             $('#id').val(response.data.id);
            //             $('#name').val(response.data.name);
            //             $('#name_market').val(response.data.name_market);
            //             $('#email').val(response.data.email);
            //             $('#role').val(response.data.role);

            //             $('#passwordLabel').text('Masukan password baru')

            //             $('#address').val(response.data.address);
            //             $('#phone_number').val(response.data.phone_number);

            //         },
            //         error: function(xhr, status, error) {
            //             console.error('Error fetching data for edit:', error);
            //         }
            //     });
            // });

            // Delete data button click handler
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                // Function to delete data
                function deleteData() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/user/delete/${id}`,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response);
                            if (response.code === 200 || response.status === "success") {
                                successAlert('Data berhasil dihapus!');

                                // Tunggu sebentar sebelum reload
                                setTimeout(function() {
                                    location
                                        .reload(); // Reload browser setelah data terhapus
                                }, 1500);
                            } else {
                                errorAlert();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', xhr.responseText);
                            errorAlert();
                        }
                    });
                }

                // Show confirmation alert
                confirmAlert('Apakah Anda yakin ingin menghapus data?', deleteData);
            });


            // messeage alert
            // alert success message
            function successAlert(message) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000,
                })
            }

            // alert error message
            function errorAlert() {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan!',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1000,
                });
            }

            function reloadBrowsers() {
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }


            function confirmAlert(message, callback) {
                Swal.fire({
                    title: '<span style="font-size: 22px"> Konfirmasi!</span>',
                    html: message,
                    showCancelButton: true,
                    showConfirmButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Ya',
                    reverseButtons: true,
                    confirmButtonColor: '#48ABF7',
                    cancelButtonColor: '#EFEFEF',
                    customClass: {
                        cancelButton: 'text-dark'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        callback();
                    }
                });
            }

            // loading alert
            function loadingAllert() {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            // Tampilkan modal tambah
            $(document).on('click', '#myBtn', function() {
                $('#upsertDataForm')[0].reset(); // reset form
                $('#id').val('');
                $('#upsertDataModal').modal('show');
            });

            // Reset saat modal ditutup
            $('#upsertDataModal').on('hidden.bs.modal', function() {
                $('#upsertDataForm')[0].reset();
                $('#id').val('');
            });

        });
    </script>
@endsection
