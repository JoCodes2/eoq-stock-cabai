@extends('Ui.master')

@section('content')
    @php
        $userId = auth()->user()->id;
    @endphp

    <div class="bg-gray-50">
        <div class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-green-700">Profil</h1>
                </div>

                <!-- Avatar dan Info User -->
                <div class="flex items-center mb-6">
                    @include('Ui.profile-user')
                </div>

                <!-- Navigasi Profil -->
                <div class="border-b border-gray-200 mb-6">
                    @include('Ui.navbar-profile')
                </div>

                <!-- Form Profil -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-inner">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">🛒 Informasi</h3>
                    <p class="text-gray-500 mb-6">Perbarui informasi profil Anda</p>

                    <form id="form-data">
                        @csrf
                        @method('POST') <!-- Tambahkan method PUT jika menggunakan update -->

                        <!-- Input ID User (Hidden) -->
                        <input type="hidden" id="id" name="id" value="{{ $userId }}">

                        <!-- Nama Lengkap -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="name">Nama Lengkap</label>
                            <div class="relative">
                                <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span id="name-error" class="text-danger text-sm"></span> <!-- Error message -->
                        </div>

                        <!-- Nama Toko -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="name_market">Nama Toko</label>
                            <div class="relative">
                                <input type="text" id="name_market" name="name_market" placeholder="Masukkan nama toko"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-store absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span id="name_market-error" class="text-danger text-sm"></span> <!-- Error message -->
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="email">Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email" placeholder="nama@perusahaan.com"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span id="email-error" class="text-danger text-sm"></span> <!-- Error message -->
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="phone_number">No. Telepon</label>
                            <div class="relative">
                                <input type="text" id="phone_number" name="phone_number" placeholder="08xxxxxxxxxx"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span id="phone_number-error" class="text-danger text-sm"></span> <!-- Error message -->
                        </div>

                        <!-- Alamat -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="address">Alamat</label>
                            <div class="relative">
                                <textarea id="address" name="address" placeholder="Masukkan alamat lengkap"
                                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                                <i class="fas fa-map-marker-alt absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            <span id="address-error" class="text-danger text-sm"></span> <!-- Error message -->
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="password">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" placeholder="********"
                                    class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>

                            </div>
                            <span id="password-error" class="text-danger text-sm"></span> <!-- Error message -->
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2" for="password_confirmation">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="********"
                                    class="w-full pl-10 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>

                            </div>
                            <span id="password_confirmation-error" class="text-danger text-sm"></span>
                            <!-- Error message -->
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="text-right">
                            <button type="submit" id="submitBtn"
                                class="bg-green-600 hover:bg-green-700 transition text-white px-6 py-2 rounded-xl shadow-md font-semibold">
                                💾 Simpan Perubahan
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

        $(document).ready(function() {
            // Ambil data user saat halaman dimuat
            $.ajax({
                url: `/v1/user/get/${userId}`,
                method: "GET",
                dataType: "json",
                success: function(res) {
                    const user = res.data;
                    const initials = user.name_market.substring(0, 2).toUpperCase();

                    $('#avatar').text(initials);
                    $('#name-market').text(user.name_market);
                    $('#phone_number-user').text(user.phone_number);
                    $('#email-user').text(user.email);
                    $('#role-user').text(user.role);

                    $('#id').val(user.id);
                    $('#name').val(user.name);
                    $('#name_market').val(user.name_market);
                    $('#email').val(user.email);
                    $('#phone_number').val(user.phone_number ?? '');
                    $('#address').val(user.address ?? '');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat data profil!'
                    });
                }
            });
        });


        // Submit form update
        $(document).on('submit', '#form-data', function(e) {
            e.preventDefault();
            $('.text-danger').text('');

            let id = $('#id').val();
            let formData = new FormData(this);
            let url = `/v1/user/update/${id}`;

            // Cek role pengguna yang sedang login
            const currentUserRole = "{{ auth()->user()->role }}";

            // Jika bukan admin, hilangkan field role
            if (currentUserRole !== 'admin') {
                formData.delete('role'); // Hapus field role jika pengguna bukan admin
            }

            $('#submitBtn').prop('disabled', true).text('⏳ Menyimpan...');
            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);

                    Swal.close();

                    if (response.code === 422) {
                        let errors = response.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key + '-error').text(value[0]);
                        });
                    } else if (response.code === 200 || response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message ?? 'Profil berhasil diperbarui!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Reset password field
                        $('#password').val('');
                        $('#password_confirmation').val('');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message ?? 'Terjadi kesalahan saat menyimpan data.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal menyimpan data. Silakan coba lagi.'
                    });
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false).text('💾 Simpan Perubahan');
                }
            });
        });
    </script>
@endsection
