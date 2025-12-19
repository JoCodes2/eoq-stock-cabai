@extends('Ui.master')

@section('content')
    <div class="bg-gradient-to-br from-red-100 via-white to-orange-50 flex items-center justify-center py-12 min-h-[80vh]">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-xl border border-red-200">
            <h1 class="text-3xl font-bold mb-1 text-red-700 text-center">
                Masuk
            </h1>
            <p class="text-gray-600 mb-6 text-center">
                Silakan login ke akun <strong>TaniCabai</strong> Anda 🌶️
            </p>

            <form id="form-data" method="POST">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="email">Email</label>
                    <div class="relative">
                        <input
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="email" id="email" name="email" placeholder="contoh@email.com">
                        <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2" for="password">Password</label>
                    <div class="relative">
                        <input
                            class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="password" id="password" name="password" placeholder="********">
                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Tombol -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition duration-300 font-semibold shadow">
                    Masuk
                </button>
            </form>

            <!-- Link ke Register -->
            <p class="text-center text-gray-600 mt-4">
                Belum punya akun?
                <a href="{{ url('/auth-register') }}" class="text-red-600 hover:underline font-medium">
                    Daftar
                </a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            function showAlert(message, type = 'success') {
                const alertId = "alert-" + new Date().getTime();
                let bgColor = "",
                    iconClass = "";

                switch (type) {
                    case 'success':
                        bgColor = "bg-red-600";
                        iconClass = "fas fa-check-circle";
                        break;
                    case 'warning':
                        bgColor = "bg-orange-500";
                        iconClass = "fas fa-exclamation-triangle";
                        break;
                    case 'error':
                        bgColor = "bg-red-700";
                        iconClass = "fas fa-times-circle";
                        break;
                    default:
                        bgColor = "bg-gray-500";
                        iconClass = "fas fa-info-circle";
                }

                const alertDiv = $(`
            <div id="${alertId}"
                class="fixed top-5 right-5 px-4 py-3 rounded-lg shadow-md text-white text-sm ${bgColor}
                flex items-center space-x-2 z-50">
                <i class="${iconClass}"></i>
                <span>${message}</span>
            </div>
        `);

                $("body").append(alertDiv);
                setTimeout(() => alertDiv.fadeOut(500, () => alertDiv.remove()), 3000);
            }

            function sendAjaxRequest() {
                const formData = {
                    email: $('#email').val(),
                    password: $('#password').val(),
                    _token: $('input[name="_token"]').val()
                };

                let btn = $('#submitBtn');
                let originalText = btn.html();
                btn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

                $.ajax({
                    url: "{{ url('v1/login') }}",
                    method: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            showAlert('Login berhasil! Selamat datang 🌶️', 'success');
                            $('#form-data')[0].reset();

                            setTimeout(() => {
                                window.location.href = response.role === 'admin' ?
                                    '/home' :
                                    '/profile';
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
                        if (xhr.status === 401) {
                            showAlert('Email atau password salah.', 'error');
                        } else if (xhr.status === 404) {
                            showAlert('Akun tidak terdaftar.', 'warning');
                        } else {
                            showAlert('Terjadi kesalahan server.', 'error');
                        }
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            }

            $('#form-data').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    },
                },
                messages: {
                    email: {
                        required: "Email wajib diisi",
                        email: "Format email tidak valid"
                    },
                    password: {
                        required: "Password wajib diisi"
                    },
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
