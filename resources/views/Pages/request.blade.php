@extends('Ui.master')
@section('content')
  <div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2 md:gap-4">


    <div class="relative w-full md:w-auto">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-green-500">
            <i class="fas fa-filter"></i>
        </span>

        <select
            id="statusFilter"
            class="appearance-none pl-12 pr-10 py-3 rounded-xl border border-gray-200 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-full md:w-auto transition duration-200"
        >
            <option value="">🔄 Semua </option>
            <option value="fulfilled">✅ Terpenuhi</option>
            <option value="open">📂 Buka</option>
            <option value="close">❌ Ditutup</option>
        </select>

        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
            <i class="fas fa-chevron-down"></i>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

</div>
 <!-- Modal Konfirmasi Delete -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Konfirmasi!</h2>
        <p id="confirmText" class="text-gray-700 text-sm mb-4">Apakah Anda yakin?</p>
        <div class="flex justify-end space-x-3">
            <button id="cancelBtn" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
            <button id="confirmBtn" type="button" class="px-4 py-2 rounded-md bg-green-500 hover:bg-green-600 cursor-pointer text-white">Penuhi</button>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>


    $(document).ready(function () {
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
        const userRole = "{{ Auth::check() ? Auth::user()->role : '' }}";
        let userId = '{{ auth()->check() ? auth()->user()->id : '' }}';
        $('#statusFilter').on('change', function () {
            let status = $(this).val();
            let url = '/v1/oil/';

            if (status) {
                url = '/v1/oil/filter?status_request=' + status;
            }

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    let container = $('.grid');
                    container.html(''); // Kosongkan kontainer

                    let data = response.data;
                    if (!data || data.length === 0) {
                        container.html('<p class="text-gray-500 text-sm italic">Tidak ada permintaan tersedia.</p>');
                        return;
                    }

                    data.forEach(item => {
                        let productName = item.product?.product_name ?? 'Produk Tidak Diketahui';
                        let unit = item.product?.unit ?? 'Liter';
                        let quantity = item.quantity + ' ' + unit;
                        let endTime = item.end_time;
                        let requestDate = item.request_date;
                        let description = item.description ?? 'Tidak ada deskripsi.';
                        let marketName = item.market?.name_market ?? 'Pasar Tidak Diketahui';
                        let marketUser = item.market?.name ?? 'Pengguna Tidak Diketahui';

                        let supplierCount = item.suplly_offers?.length ?? 0;

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

                        let hasSubmitted = item.suplly_offers?.some(offer => offer.supplier_id == userId);
                        let isButtonDisabled = (!isLoggedIn || userRole !== 'supplier' || hasSubmitted) ? 'disabled' : '';
                        let buttonLabel = hasSubmitted ? 'Sudah Mengajukan' : 'Penuhi Permintaan';
                        let buttonClass = (!isLoggedIn || userRole !== 'supplier' || hasSubmitted)
                            ? 'bg-gray-300 cursor-not-allowed'
                            : 'bg-green-500 hover:bg-green-600 cursor-pointer';

                        let button = `
                            <button
                                class="${buttonClass} text-white px-4 py-1.5 rounded text-sm btn-penuhi"
                                data-id="${item.id}"
                                ${isButtonDisabled}
                            >
                                ${buttonLabel}
                            </button>
                        `;

                        let card = `
                            <div class="bg-white p-4 rounded shadow text-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <p class="text-xs text-gray-500">${marketUser} <strong>${marketName}</strong></p>
                                        <h2 class="font-bold text-base md:text-lg">${productName}</h2>
                                    </div>
                                    ${statusLabel}
                                </div>
                                <p class="mb-1">Penawaran: <span class="font-semibold">${supplierCount}</span></p>
                                <p class="mb-1"><i class="fas fa-shopping-cart"></i> Jumlah: <strong>${quantity}</strong></p>
                                <p class="mb-1"><i class="fas fa-calendar-alt"></i> Batas Waktu: <strong>${endTime}</strong></p>
                                <p class="mb-2 text-gray-600 text-xs">${description}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <div>
                                        <p class="text-gray-500 text-xs">Diposting: ${requestDate}</p>
                                    </div>
                                    ${button}
                                </div>
                            </div>
                        `;

                        container.append(card);
                    });
                },
                error: function () {
                    $('.grid').html('<p class="text-red-500">Gagal memuat data dari server.</p>');
                }
            });
        });

        $.ajax({
            url: '/v1/oil/',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                let container = $('.grid');
                let data = response.data;
                console.log(data);


                data.forEach(item => {
                    let productName = item.product?.product_name ?? 'Produk Tidak Diketahui';
                    let unit = item.product?.unit ?? 'Liter';
                    let quantity = item.quantity + ' ' + unit;
                    let endTime = item.end_time;
                    let requestDate = item.request_date;
                    let description = item.description ?? 'Tidak ada deskripsi.';
                    let marketName = item.market?.name_market ?? 'Pasar Tidak Diketahui';
                    let marketUser = item.market?.name ?? 'Pengguna Tidak Diketahui';

                    let supplierCount = item.suplly_offers?.length ?? 0;


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
                    let hasSubmitted = item.suplly_offers?.some(offer => offer.supplier_id == userId);

                    let isButtonDisabled = (!isLoggedIn || userRole !== 'supplier' || hasSubmitted) ? 'disabled' : '';
                    let buttonLabel = hasSubmitted
                        ? 'Sudah Mengajukan'
                        : 'Penuhi Permintaan';
                    let buttonClass = (!isLoggedIn || userRole !== 'supplier' || hasSubmitted)
                        ? 'bg-gray-300 cursor-not-allowed'
                        : 'bg-green-500 hover:bg-green-600 cursor-pointer';

                    let button = `
                        <button
                            class="${buttonClass} text-white px-4 py-1.5 rounded text-sm btn-penuhi"
                            data-id="${item.id}"
                            ${isButtonDisabled}
                        >
                            ${buttonLabel}
                        </button>
                    `;



                    let card = `
                        <div class="bg-white p-4 rounded shadow text-sm">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="text-xs text-gray-500">${marketUser} <strong>${marketName}</strong></p>
                                    <h2 class="font-bold text-base md:text-lg">${productName}</h2>
                                </div>
                                ${statusLabel}
                            </div>
                            <p class="mb-1">Penawaran: <span class="font-semibold">${supplierCount}</span></p>
                            <p class="mb-1"><i class="fas fa-shopping-cart"></i> Jumlah: <strong>${quantity}</strong></p>
                            <p class="mb-1"><i class="fas fa-calendar-alt"></i> Batas Waktu: <strong>${endTime}</strong></p>
                            <p class="mb-2 text-gray-600 text-xs">${description}</p>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p class="text-gray-500 text-xs">Diposting: ${requestDate}</p>
                                </div>
                                ${button}
                            </div>
                        </div>
                    `;

                    container.append(card);
                });
            },
            error: function () {
                alert("Gagal memuat data permintaan.");
            }
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        let selectId = null;

        $(document).on('click', '.btn-penuhi', function () {
            selectId = $(this).data('id');
            console.log(selectId);

            $('#confirmModal').removeClass('hidden');
        });

        $('#cancelBtn').on('click', function () {
            $('#confirmModal').addClass('hidden');
            selectId = null;
        });
        $(document).on('click', '#confirmBtn', function() {
            let request_id = selectId;

            $.ajax({
                url: 'v1/supply/create',
                method: 'POST',
                data: {
                    request_id: request_id
                },
                dataType: "json",
                success: function (response) {
                    console.log('Response diterima:', response);

                    if (response.code === 200) {
                        showAlert('Penawaran berhasil dikirim!', 'success');
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


