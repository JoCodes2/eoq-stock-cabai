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
                <table id="dataStokmasuk" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Stok Masuk</th>
                            <th>Master Data Id</th>
                            <th>Jumlah</th>
                            <th>Harga Beli Satuan</th>
                            <th>Nama Supplier</th>
                            <th>Total Harga</th>
                            <th>No Invoice</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr>
                            <td colspan="9" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Stok Masuk -->
    <div class="modal fade" id="upsertDataModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="upsertDataForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Stok Masuk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">

                        <!-- Kode Stok Masuk -->
                        <div class="mb-3">
                            <label class="form-label">Kode Stok Masuk</label>
                            <input type="text" name="kode_stok_masuk" id="kode_stok_masuk" class="form-control">
                            <small class="text-danger" id="kode_stok_masuk-error"></small>
                        </div>

                        <!-- Produk -->
                        <div class="mb-3">
                            <label class="form-label">Produk</label>
                            <select name="master_data_id" id="master_data_id" class="form-select">
                                <option value="">-- Pilih Produk --</option>
                                {{-- looping produk --}}
                            </select>
                            <small class="text-danger" id="master_data_id-error"></small>
                        </div>

                        <div class="row">
                            <!-- Jumlah -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah</label>
                                <div class="input-group">
                                    <input type="number" name="jumlah" id="jumlah" class="form-control" min="0"
                                        step="0.01">
                                    <span class="input-group-text">kg</span>
                                </div>
                                <small class="text-danger" id="jumlah-error"></small>
                            </div>


                            <!-- Harga Beli Satuan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Beli Satuan</label>
                                <input type="text" name="harga_beli_satuan" id="harga_beli_satuan" class="form-control"
                                    autocomplete="off">

                                <small class="text-danger" id="harga_beli_satuan-error"></small>
                            </div>
                        </div>


                        <!-- Nama Supplier -->
                        <div class="mb-3">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" name="nama_supplier" id="nama_supplier" class="form-control">
                            <small class="text-danger" id="nama_supplier-error"></small>
                        </div>

                        <!-- No Invoice -->
                        <div class="mb-3">
                            <label class="form-label">No Invoice</label>
                            <input type="text" name="no_invoice" id="no_invoice" class="form-control"
                                placeholder="Contoh: INV-2025-001">
                            <small class="text-danger" id="no_invoice-error"></small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" id="simpanData" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            function formatRupiah(angka) {
                if (!angka) return '0';
                return parseInt(angka).toLocaleString('id-ID');
            }

            function formatRupiahInput(angka) {
                return angka.replace(/\D/g, '')
                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // realtime format Rp
            $('#harga_beli_satuan').on('input', function() {
                let value = $(this).val();
                $(this).val(formatRupiahInput(value));
            });

            $(document).on('change', '#master_data_id', function() {
                let satuan = $(this).find(':selected').data('satuan');
                $('#satuanLabel').text(satuan ? satuan : 'kg');
            });




            // Ambil data
            function getData() {
                $.ajax({
                    url: `/v1/stokmasuk`,
                    method: "GET",
                    dataType: "json",
                    success: function(response) {
                        let tableBody = "";

                        $.each(response.data, function(index, item) {
                            tableBody += `<tr>
                    <td>${index + 1}</td>
                    <td>${item.kode_stok_masuk ?? '-'}</td>
                    <td>${item.master_data?.nama ?? '-'}</td>
                    <td>${item.jumlah} ${item.master_data?.satuan ?? ''}</td>
                    <td>Rp ${formatRupiah(item.harga_beli_satuan)}</td>
                    <td><strong>Rp ${formatRupiah(item.total_harga)}</strong></td>
                    <td>${item.nama_supplier}</td>
                    <td>${item.no_invoice ?? '-'}</td>
                    <td>
                        <button type="button"
                            class="btn btn-outline-danger btn-sm delete-confirm"
                            data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                        });

                        $("#dataStokmasuk tbody").html(tableBody);

                        $('#dataStokmasuk').DataTable({
                            destroy: true,
                            paging: true,
                            searching: true,
                            ordering: true,
                            info: true,
                            order: []
                        });
                    },
                    error: function() {
                        console.error("Gagal mengambil data stok masuk");
                    }
                });
            }

            getData();

            function loadMasterData() {
                $.ajax({
                    url: '/v1/master', // endpoint master_data
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let options = `<option value="">-- Pilih Produk --</option>`;

                        $.each(response.data, function(i, item) {
                            options += `
                    <option value="${item.id}" data-satuan="${item.satuan}">
    ${item.nama} (${item.satuan})
</option>

                `;
                        });

                        $('#master_data_id').html(options);
                    },
                    error: function() {
                        console.error('Gagal mengambil data master');
                    }
                });
            }


            // CREATE STOK MASUK
            $(document).on('click', '#simpanData', function(e) {
                e.preventDefault();
                $('.text-danger').text('');

                // bersihkan format rupiah
                let harga = $('#harga_beli_satuan').val().replace(/\./g, '');
                $('#harga_beli_satuan').val(harga);

                let formData = new FormData($('#upsertDataForm')[0]);

                loadingAllert();

                $.ajax({
                    type: 'POST',
                    url: '/v1/stokmasuk/create',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close();

                        // VALIDASI GAGAL (422)
                        if (response.code === 422) {
                            $.each(response.errors, function(key, value) {
                                $('#' + key + '-error').text(value[0]);
                            });
                            return;
                        }

                        // BERHASIL
                        if (response.code === 200 || response.status === 'success') {
                            successAlert('Stok masuk berhasil disimpan!');

                            $('#upsertDataModal').modal('hide');
                            $('#upsertDataForm')[0].reset();

                            // reload tabel
                            getData();

                            // AUTO RELOAD PAGE (opsional delay)
                            setTimeout(function() {
                                location.reload();
                            }, 1200);

                            return;
                        }


                        // ERROR LAIN
                        errorAlert();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.close();
                        errorAlert();
                    }
                });
            });




            // Delete data button click handler
            $(document).on('click', '.delete-confirm', function() {
                let id = $(this).data('id');

                // Function to delete data
                function deleteData() {
                    $.ajax({
                        type: 'DELETE',
                        url: `/v1/stokmasuk/delete/${id}`,
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
                $('#upsertDataForm')[0].reset();
                $('.text-danger').text('');
                loadMasterData(); // 🔥 PENTING
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
