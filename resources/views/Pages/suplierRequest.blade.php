@extends('Ui.master')
@section('content')
@php
    $userId = auth()->user()->id;
@endphp
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-green-700">Profil</h1>
            </div>

            <div class="flex items-center mb-6">
                @include('Ui.profile-user')
            </div>

            <div class="border-b border-gray-200 mb-6">
                @include('Ui.navbar-profile')
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2 text-gray-700">📜 Penawaran</h3>
                <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200">
                    <table class="min-w-full bg-white text-sm text-left text-gray-600"  id="oilTable">
                        <thead class="bg-green-50 text-green-700 font-semibold uppercase tracking-wide text-xs">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Supplier</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div id="modalDetail" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-2xl overflow-hidden animate-fade-in">
            <div class="bg-green-600 text-white px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold">Detail Penawaran Suplier</h2>
                <button id="closeModal" class="text-white text-lg hover:text-gray-200">&times;</button>
            </div>
            <div class="px-6 py-4 space-y-6">
                <!-- Informasi Supplier -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">📦 Informasi Suplier</h3>
                    <div class="grid grid-cols-2 gap-4 text-gray-600 text-sm">
                        <div><strong>Nama:</strong> <span id="supplierName"></span></div>
                        <div><strong>Nama Toko:</strong> <span id="supplierMarket"></span></div>
                        <div><strong>Email:</strong> <span id="supplierEmail"></span></div>
                        <div><strong>No. HP:</strong> <span id="supplierPhone"></span></div>
                        <div><strong>Alamat:</strong> <span id="supplierAddress"></span></div>
                    </div>
                </div>

                <!-- Informasi Permintaan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">🛒 Detail Permintaan</h3>
                    <div class="grid grid-cols-2 gap-4 text-gray-600 text-sm">
                        <div><strong>Produk:</strong> <span id="productName"></span></div>
                        <div><strong>Jumlah:</strong> <span id="quantity"></span></div>
                        <div><strong>Tanggal Permintaan:</strong> <span id="requestDate"></span></div>
                        <div><strong>Waktu Berakhir:</strong> <span id="endTime"></span></div>
                        <div class="col-span-2"><strong>Deskripsi:</strong> <span id="description"></span></div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">📌 Status</h3>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                            Status Penawaran: <span id="statusPenawaran" class="font-medium"></span>
                        </div>
                        <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full">
                            Status Permintaan: <span id="statusPermintaan" class="font-medium"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-100 px-6 py-3 text-right">
                <button id="closeModalFooter" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Tutup</button>
            </div>
        </div>
    </div>


    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Konfirmasi!</h2>
            <p id="confirmText" class="text-gray-700 text-sm mb-4">Apakah Anda yakin?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelBtn" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
                <button id="confirmBtn" class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white">Ya</button>
            </div>
        </div>
    </div>

</div>
@endsection
@section('scripts')

    <script>
        const userId = "{{ $userId }}";

        $(document).ready(function () {
            $.ajax({
                url: `/v1/user/get/${userId}`,
                method: "GET",
                dataType: "json",
                success: function (res) {
                    console.log(res);

                    const user = res.data;
                    const initials = user.name_market.substring(0, 2).toUpperCase();

                    $('#avatar').text(initials);
                    $('#name-market').text(user.name_market);
                    $('#phone_number-user').text(user.phone_number);
                    $('#email-user').text(user.email);
                    $('#role-user').text(user.role);

                    $('#name').val(user.name);
                    $('#name_market').val(user.name_market);
                    $('#email').val(user.email);
                    $('#phone_number').val(user.phone_number ?? '');
                    $('#address').val(user.address ?? '');
                },
                error: function () {
                    alert('Gagal memuat data profil!');
                }
            });
        });
        $(document).ready(function () {
            $.ajax({
                url: '/v1/supply/',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    let data = response.data;
                    let rows = '';

                    data.forEach(function (item, index) {
                        let statusLabel = '';
                        switch (item.status) {
                            case 'pending':
                                statusLabel = '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Menunggu</span>';
                                break;
                            case 'selected':
                                statusLabel = '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Terpilih</span>';
                                break;
                            default:
                                statusLabel = '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Tidak Diketahui</span>';
                        }

                        rows += `
                            <tr>
                                <td class="px-6 py-4">${index + 1}</td>
                                <td class="px-6 py-4">${item.supplier.name}</td>
                                <td class="px-6 py-4">${item.request.product.product_name}</td>
                                <td class="px-6 py-4">${item.request.quantity} ${item.request.product.unit}</td>
                                <td class="px-6 py-4">${item.created_at}</td>
                                <td class="px-6 py-4">${statusLabel}</td>
                                <td class="px-6 py-4 space-x-2">
                                    <button class="text-gray-600 hover:text-gray-800 btn-detail" data-id="${item.id}" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800 button-approved" data-id="${item.id}" title="Pilih">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#oilTable tbody').html(rows);
                },
                error: function (xhr, status, error) {
                    console.error('Gagal memuat data:', error);
                    $('#oilTable tbody').html(`
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4 text-red-500">Gagal memuat data</td>
                        </tr>
                    `);
                }
            });

            function showAlert(message, type = 'success') {
                const alertId = "alert-" + new Date().getTime();
                let bgColor = "", iconClass = "";

                switch (type) {
                    case 'success': bgColor = "bg-green-500"; iconClass = "fas fa-check-circle"; break;
                    case 'warning': bgColor = "bg-yellow-500"; iconClass = "fas fa-exclamation-triangle"; break;
                    case 'error': bgColor = "bg-red-500"; iconClass = "fas fa-times-circle"; break;
                    default: bgColor = "bg-gray-500"; iconClass = "fas fa-info-circle";
                }

                const alertDiv = $(`
                    <div id="${alertId}" class="fixed top-5 right-5 px-4 py-3 rounded shadow-md text-white text-sm ${bgColor} flex items-center space-x-2 z-50">
                        <i class="${iconClass} text-white"></i>
                        <span>${message}</span>
                    </div>
                `);
                $("body").append(alertDiv);
                setTimeout(() => alertDiv.fadeOut(500, () => alertDiv.remove()), 3000);
            }

            $(document).on('click', '.btn-detail', function () {
                let selectId = $(this).data('id');

                $.ajax({
                    url: `/v1/supply/get/${selectId}`,
                    method: "GET",
                    dataType: "json",
                    success: function (response) {
                        const data = response.data;

                        // Supplier info
                        $('#supplierName').text(data.supplier.name);
                        $('#supplierMarket').text(data.supplier.name_market);
                        $('#supplierEmail').text(data.supplier.email);
                        $('#supplierPhone').text(data.supplier.phone_number);
                        $('#supplierAddress').text(data.supplier.address);

                        // Request info
                        $('#productName').text(data.request.product.product_name);
                        $('#quantity').text(`${data.request.quantity} ${data.request.product.unit}`);
                        $('#requestDate').text(data.request.request_date);
                        $('#endTime').text(data.request.end_time);
                        $('#description').text(data.request.description);

                        // Status
                        $('#statusPenawaran').text(data.status); // 'pending' atau 'selected'
                        $('#statusPermintaan').text(data.request.status_request); // contoh: 'open', 'closed'

                        // Tampilkan modal
                        $('#modalDetail').removeClass('hidden');
                    },
                    error: function () {
                        alert('Gagal memuat data!');
                    }
                });
            });

            // Tutup Modal
            $('#closeModal, #closeModalFooter').on('click', function () {
                $('#modalDetail').addClass('hidden');
            });

            let selectId = null;

            $(document).on('click', '.button-approved', function () {
                selectId = $(this).data('id');
                $('#confirmModal').removeClass('hidden');
            });

            $('#cancelBtn').on('click', function () {
                $('#confirmModal').addClass('hidden');
                selectId = null;
            });
            $(document).on('click', '#confirmBtn', function() {

                $.ajax({
                    url: `/v1/supply/change/${selectId}`,
                    method: 'POST',
                    dataType: "json",
                    success: function (response) {
                        console.log('Response diterima:', response);

                        if (response.code === 200) {
                            showAlert('Sukses memilih supllier!', 'success');
                            location.reload();
                        } else {
                            showAlert('Terjadi kesalahan!', 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        showAlert('Terjadi kesalahan!', 'error');
                    },
                    complete: function () {
                        $('#confirmModal').addClass('hidden');
                        selectId = null;
                    }
                });

            })
        });
    </script>
@endsection
