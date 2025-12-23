@extends('Ui.master')
@section('content')
    @php
        $userId = auth()->user()->id;
    @endphp
    <div class="bg-red-50">
        <div class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-red-100">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-red-700">
                        Profil Pengguna
                    </h1>
                </div>
                <!-- Navbar Profile -->
                <div class="border-b border-red-200 mb-6">
                    @include('Ui.navbar-profile')
                </div>

                <!-- Form -->
                <div class="bg-red-50 p-6 rounded-xl shadow-inner">
                    <h3 class="text-xl font-bold text-red-700 mb-2">
                        🌶️ Form Permintaan
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Pilih data dan tambahkan catatan permintaan Anda
                    </p>

                    <form method="POST" id="form-data">
                        @csrf

                        <!-- Master Data -->
                        <div class="mb-6">
                            <label for="master_data_id" class="block text-gray-700 font-medium mb-1">
                                Jenis Cabai
                            </label>
                            <select name="master_data_id" id="master_data_id"
                                class="w-full p-3 border border-gray-300 rounded-xl shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                                <option value="" selected disabled>-- Pilih Jenis Cabai --</option>
                                {{-- contoh --}}
                                {{-- <option value="1">Cabai Rawit</option> --}}
                                {{-- <option value="2">Cabai Merah Keriting</option> --}}
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-6">
                            <label for="catatan" class="block text-gray-700 font-medium mb-1">
                                Catatan Tambahan
                            </label>
                            <textarea name="catatan" id="catatan" rows="4"
                                class="w-full p-3 border border-gray-300 rounded-xl shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                                placeholder="Contoh: butuh cabai segar, kirim hari ini"></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="text-right">
                            <button type="submit" id="submitBtn"
                                class="bg-red-600 hover:bg-red-700 transition text-white px-6 py-2
                       rounded-xl shadow-md font-semibold">
                                🚀 Simpan Permintaan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

