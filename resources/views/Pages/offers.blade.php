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
                                <th class="px-6 py-3">Nama Pengaju</th>
                                <th class="px-6 py-3">Nama Toko</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">

                        </tbody>
                    </table>
                </div>
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
                                <td class="px-6 py-4">${item.request.market.name}</td>
                                <td class="px-6 py-4">${item.request.market.name_market}</td>
                                <td class="px-6 py-4">${item.request.product.product_name}</td>
                                <td class="px-6 py-4">${item.request.quantity} ${item.request.product.unit}</td>
                                <td class="px-6 py-4">${item.created_at}</td>
                                <td class="px-6 py-4">${statusLabel}</td>
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
        });
    </script>
@endsection
