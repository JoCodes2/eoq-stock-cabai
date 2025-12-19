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
