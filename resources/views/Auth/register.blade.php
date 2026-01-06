@extends('Ui.master')

@section('content')
    <div id="alert-container"></div>

    <div class="bg-gradient-to-br from-red-100 via-white to-orange-50 flex items-center justify-center min-h-screen py-10">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-3xl border border-red-200">
            <h1 class="text-3xl font-bold mb-1 text-red-700 text-center">
                Daftar Akun
            </h1>
            <p class="text-gray-600 mb-6 text-center">
                Buat akun baru untuk mulai menggunakan <strong>TaniCabai</strong> 🌶️
            </p>

            <form id="form-data">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <input
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap">
                        <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <input
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="email" id="email" name="email" placeholder="email@contoh.com">
                        <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- No Telepon -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">No. Telepon</label>
                    <div class="relative">
                        <input
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="text" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx">
                        <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Alamat</label>
                    <div class="relative">
                        <textarea class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            id="alamat" name="alamat" placeholder="Alamat lengkap (opsional)"></textarea>
                        <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>
                <input type="hidden" id="role" name="role" value="pembeli">

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input
                            class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="password" id="password" name="password" placeholder="********">
                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-semibold">
                    Daftar
                </button>
            </form>


            <p class="text-center text-gray-600 mt-4">
                Sudah punya akun?
                <a href="{{ url('/login') }}" class="text-red-600 hover:underline font-medium">
                    Masuk
                </a>
            </p>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            function sendAjaxRequest() {
                const formData = {
                    _token: $('input[name="_token"]').val(),
                    nama: $('#nama').val(),
                    email: $('#email').val(),
                    no_hp: $('#no_hp').val(),
                    alamat: $('#alamat').val(),
                    password: $('#password').val(),
                    role: $('#role').val()
                };
                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

                $.ajax({
                    url: '/v1/user/create',
                    type: 'POST', // ✅ pakai type (paling aman)
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);

                        if (response.code === 200) {
                            showAlert('Registrasi berhasil! Silahkan Login 🌶️', 'success');
                            $('#form-data')[0].reset();

                            setTimeout(() => {
                                window.location.href = '/login'
                            }, 1000);

                        } else if (response.code === 422) {
                            let msg = '';
                            $.each(response.errors, function(key, val) {
                                msg += val.join('<br>') + '<br>';
                            });
                            showAlert(msg.trim(), 'warning');
                        } else {
                            showAlert('Terjadi kesalahan pada sistem.', 'error');
                        }
                    },
                    error: function(xhr) {
                        showAlert('Terjadi kesalahan server.', 'error');
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            }

            $('#form-data').validate({
                rules: {
                    nama: {
                        required: true,
                        maxlength: 50
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    no_hp: {
                        required: true,
                        digits: true
                    },
                    alamat: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    }
                },
                messages: {
                    nama: {
                        required: "Nama wajib diisi",
                        maxlength: "Nama maksimal 50 karakter"
                    },
                    email: {
                        required: "Email wajib diisi",
                        email: "Format email tidak valid"
                    },
                    no_hp: {
                        required: "Nomor HP wajib diisi",
                        digits: "Nomor HP harus berupa angka"
                    },
                    alamat: {
                        required: "Alamat wajib diisi"
                    },
                    password: {
                        required: "Password wajib diisi",
                        minlength: "Password minimal 8 karakter"
                    }
                },
                errorClass: 'border-red-500',
                validClass: 'border-red-600',
                highlight: function(element) {
                    $(element).removeClass('border-red-600').addClass('border-red-500');
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-red-500').addClass('border-red-600');
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-sm text-red-500 mt-1');
                    if (element.parent('.relative').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                    sendAjaxRequest();
                    return false;
                }
            });
        });
    </script>
@endsection
