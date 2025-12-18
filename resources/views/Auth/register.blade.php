@extends('Ui.master')

@section('content')
<div id="alert-container"></div>
<div class="bg-gradient-to-br from-green-100 via-white to-green-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-3xl border border-green-200">
        <h1 class="text-3xl font-bold mb-1 text-green-700 text-center">Daftar</h1>
        <p class="text-gray-600 mb-6 text-center">Buat akun baru untuk mulai menggunakan <strong>OillyKampoeng</strong></p>

        <form id="form-data">
            @csrf
            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="name">Nama Lengkap</label>
                <div class="relative">
                    <input class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" type="text" id="name" name="name" placeholder="Masukkan nama lengkap">
                    <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Nama Toko -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="name_market">Nama Toko</label>
                <div class="relative">
                    <input class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" type="text" id="name_market" name="name_market" placeholder="Masukkan nama toko">
                    <i class="fas fa-store absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Email</label>
                <div class="relative">
                    <input class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" type="email" id="email" name="email" placeholder="xxxx@gmail.com">
                    <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>
            <!-- No. Telepon -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="phone_number">No. Telepon</label>
                <div class="relative">
                    <input class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" type="text" id="phone_number" name="phone_number" placeholder="08xxxxxxxxxx">
                    <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="address">Alamat</label>
                <div class="relative">
                    <textarea class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" id="address" name="address" placeholder="Masukkan alamat lengkap"></textarea>
                    <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password">Password</label>
                <div class="relative">
                    <input class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" type="password" id="password" name="password" placeholder="********">
                    <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>

                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password_confirmation">Konfirmasi Password</label>
                <div class="relative">
                    <input class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" type="password" id="password_confirmation" name="password_confirmation" placeholder="********">
                    <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>

                </div>
            </div>

            <!-- Jenis Pengguna -->
            <div class="mb-6">
                <label for="role" class="block text-gray-700 mb-2">Jenis Pengguna</label>
                <select id="role" name="role" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="" selected disabled>-- Pilih Jenis Pengguna --</option>
                    <option value="supplier">Suplier Minyak Kelapa</option>
                    <option value="market">Market / Pembeli</option>
                </select>
            </div>

            <!-- Tombol -->
            <button type="submit" id="submitBtn" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition duration-300 font-semibold shadow">
                Daftar
            </button>

        </form>

        <p class="text-center text-gray-600 mt-4">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="text-green-600 hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $.validator.addMethod("startsWith08", function (value, element) {
        return this.optional(element) || /^08\d{8,11}$/.test(value);
    }, "Nomor telepon harus dimulai dengan 08 dan panjang 10–13 digit");

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
            name: $('#name').val(),
            name_market: $('#name_market').val(),
            email: $('#email').val(),
            address: $('#address').val(),
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val(),
            phone_number: $('#phone_number').val(),
            role: $('#role').val(),
            _token: $('input[name="_token"]').val()
        };

        const btn = $('#submitBtn');
        const originalText = btn.html();
        btn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin mr-2"></i>Loading...`);

        $.ajax({
            url: "{{ url('v1/user/create') }}",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.code === 200) {
                    showAlert('Akun berhasil didaftarkan!', 'success');
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
            name: { required: true, maxlength: 50 },
            name_market: { required: true, maxlength: 100 },
            email: { required: true, email: true },
            password: { required: true, minlength: 8 },
            password_confirmation: { required: true, equalTo: "#password" },
            address: { required: true },
            phone_number: { required: true, digits: true, minlength: 10, maxlength: 13, startsWith08: true },
            role: { required: true }
        },
        messages: {
            name: { required: "Nama lengkap wajib diisi", maxlength: "Maksimal 50 karakter" },
            name_market: { required: "Nama toko wajib diisi", maxlength: "Maksimal 100 karakter" },
            email: { required: "Email wajib diisi", email: "Format email tidak valid" },
            password: { required: "Password wajib diisi", minlength: "Password minimal 8 karakter" },
            password_confirmation: { required: "Konfirmasi password wajib diisi", equalTo: "Konfirmasi password tidak cocok" },
            address: { required: "Alamat wajib diisi" },
            phone_number: {
                required: "Nomor telepon wajib diisi",
                digits: "Harus berupa angka tanpa spasi atau simbol",
                minlength: "Minimal 10 digit",
                maxlength: "Maksimal 13 digit",
                startsWith08: "Nomor harus dimulai dengan 08"
            },
            role: { required: "Pilih jenis pengguna" }
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


