@extends('Ui.master')
@section('content')
@php
    $userId = auth()->user()->id;
@endphp
<div class="bg-gray-50">
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

            <div class="bg-gray-50 p-6 rounded-xl shadow-inner">
                <h3 class="text-xl font-bold text-gray-700 mb-4">🛒 Form data</h3>
                <p class="text-gray-500 mb-6">Ajukan permintaan anda dengan mengisi form dibawah ini</p>
                <form method="POST" id="form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="product_id" class="block text-gray-700 font-medium mb-1">Nama Produk</label>
                        <select name="product_id" id="product_id" class="w-full mt-1 p-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                            <option value="" selected disabled>-- Pilih Produk --</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="quantity" class="block text-gray-700 font-medium mb-1">Kuantitas (Liter)</label>
                        <input type="number" name="quantity" id="quantity" class="w-full mt-1 p-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition" >
                    </div>

                    <div class="mb-6">
                        <label for="end_time" class="block text-gray-700 font-medium mb-1">Batas Waktu Permintaan</label>
                        <input type="date" name="end_time" id="end_time" class="w-full mt-1 p-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition" >
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 font-medium mb-1">Deskripsi Tambahan</label>
                        <textarea name="description" id="description" rows="3" class="w-full mt-1 p-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition"></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" id="submitBtn" class="bg-green-600 hover:bg-green-700 transition text-white px-6 py-2 rounded-xl shadow-md font-semibold">
                            💾 Ajukan Permintaan Permintaan
                        </button>
                    </div>
                </form>

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
            url: '/v1/product/',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                console.log(response);

                if (Array.isArray(response.data)) {
                    let productSelect = $('#product_id');
                    response.data.forEach(function (product) {
                        productSelect.append(
                            $('<option>', {
                                value: product.id,
                                text: product.product_name
                            })
                        );
                    });
                } else {
                    console.warn('Data produk tidak dalam format array:', response);
                }
            },
            error: function (xhr, status, error) {
                console.error('Gagal mengambil data produk:', error);
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

        function sendAjaxRequest() {
            const formData = {
                _token: $('input[name="_token"]').val(),
                product_id: $('#product_id').val(),
                quantity: $('#quantity').val(),
                end_time: $('#end_time').val(),
                description: $('#description').val()
            };

            const btn = $('#submitBtn');
            const originalText = btn.html();
            btn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin mr-2"></i>Loading...`);

            $.ajax({
                url: "{{ url('v1/oil/create') }}",
                method: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.code === 200) {
                        showAlert('Berhasil membuat permintaan!', 'success');
                        $('#form-data')[0].reset();
                        $('.border-green-500, .border-red-500').removeClass('border-green-500 border-red-500');
                    } else if (response.code === 422) {
                        let msg = '';
                        $.each(response.errors, function (key, val) {
                            msg += val.join('<br>') + '<br>';
                        });
                        showAlert(msg.trim(), 'warning');
                    } else {
                        showAlert('Terjadi kesalahan pada sistem.', 'error');
                    }
                },
                error: function () {
                    showAlert('Terjadi kesalahan server.', 'error');
                },
                complete: function () {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        }


        $('#form-data').validate({
            rules: {
                product_id: {
                    required: true
                },
                quantity: {
                    required: true,
                    number: true,
                    min: 1
                },
                end_time: {
                    required: true,
                    date: true
                },
                description: {
                    maxlength: 255
                }
            },
            messages: {
                product_id: {
                    required: "Silakan pilih produk."
                },
                quantity: {
                    required: "Kuantitas wajib diisi.",
                    number: "Harus berupa angka.",
                    min: "Minimal 1 liter."
                },
                end_time: {
                    required: "Batas waktu wajib diisi.",
                    date: "Format tanggal tidak valid."
                },
                description: {
                    maxlength: "Deskripsi maksimal 255 karakter."
                }
            },
            errorClass: 'border-red-500',
            validClass: 'border-green-500',
            highlight: function (element) {
                $(element).removeClass('border-green-500').addClass('border-red-500');
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-500').addClass('border-green-500');
            },
            errorPlacement: function (error, element) {
                error.addClass('text-sm text-red-500 mt-1');
                if (element.parent('.relative').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function () {
                sendAjaxRequest();
                return false;
            }
        });

    });
</script>
@endsection
