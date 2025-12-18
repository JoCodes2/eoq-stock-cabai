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
                <h3 class="text-lg font-bold mb-2 text-gray-700">📜 Riwayat</h3>
                <div class="overflow-x-auto rounded-xl shadow-sm border border-gray-200">
                    <table class="min-w-full bg-white text-sm text-left text-gray-600"  id="oilTable">
                        <thead class="bg-green-50 text-green-700 font-semibold uppercase tracking-wide text-xs">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Batas Waktu</th>
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
    <!-- Modal Konfirmasi Delete -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Konfirmasi!</h2>
            <p id="confirmText" class="text-gray-700 text-sm mb-4">Apakah Anda yakin?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDeleteBtn" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
                <button id="confirmDeleteBtn" class="px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white">Ya</button>
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
                url: '/v1/get-oil-user',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    let data = response.data;
                    let rows = '';

                    data.forEach(function (item, index) {
                        let statusLabel = '';
                        switch (item.status_request) {
                            case 'open':
                                statusLabel = '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Buka</span>';
                                break;
                            case 'close':
                                statusLabel = '<span class="bg-yellow-100 text-red-700 px-2 py-1 rounded text-xs">Ditutup</span>';
                                break;
                            case 'fulfilled':
                                statusLabel = '<span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Terpenuhi</span>';
                                break;
                            default:
                                statusLabel = '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Tidak Diketahui</span>';
                        }
                        rows += `
                            <tr>
                                <td class="px-6 py-4">${index + 1}</td>
                                <td class="px-6 py-4">${item.product.product_name}</td>
                                <td class="px-6 py-4">${item.quantity} ${item.product.unit}</td>
                                <td class="px-6 py-4">${item.request_date}</td>
                                <td class="px-6 py-4">${item.end_time}</td>
                                <td class="px-6 py-4">
                                    ${statusLabel}
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <button class="text-gray-600 hover:text-gray-800 btn-close" data-id="${item.id}" title="Tutup">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 button-delete" data-id="${item.id}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
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
                            <td colspan="6" class="text-center px-6 py-4 text-red-500">Gagal memuat data</td>
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

            let selectedDeleteId = null;
            let actionType = null;


            $(document).on('click', '.button-delete', function () {
                selectedDeleteId = $(this).data('id');
                actionType = 'delete';
                $('#confirmText').text('Apakah Anda yakin ingin menghapus permintaan ini?');
                $('#confirmModal').removeClass('hidden');
            });

            $(document).on('click', '.btn-close', function () {
                selectedDeleteId = $(this).data('id');
                actionType = 'close';
                $('#confirmText').text('Apakah Anda yakin ingin menutup permintaan ini?');
                $('#confirmModal').removeClass('hidden');
            });


            $('#cancelDeleteBtn').on('click', function () {
                $('#confirmModal').addClass('hidden');
                selectedDeleteId = null;
                actionType = null;
            });

            $('#confirmDeleteBtn').on('click', function () {
                if (selectedDeleteId && actionType) {
                    let url = '';
                    let method = '';
                    let successMessage = '';

                    if (actionType === 'delete') {
                        url = `/v1/oil/delete/${selectedDeleteId}`;
                        method = 'DELETE';
                        successMessage = 'Berhasil menghapus data!';
                    } else if (actionType === 'close') {
                        url = `/v1/oil/change/${selectedDeleteId}`;
                        method = 'POST'; // sesuai permintaan
                        successMessage = 'Permintaan berhasil ditutup!';
                    }

                    $.ajax({
                        url: url,
                        method: method,
                        success: function (res) {
                            showAlert(successMessage, 'success');
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            showAlert('Terjadi kesalahan!', 'error');
                        },
                        complete: function () {
                            $('#confirmModal').addClass('hidden');
                            selectedDeleteId = null;
                            actionType = null;
                        }
                    });
                }
            });


        });
    </script>
@endsection
