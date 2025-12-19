@extends('Ui.master')

@section('content')
    <!-- HERO SECTION -->
    <section class="bg-red-50 py-16">
        <div class="container mx-auto px-4 flex justify-center items-center">
            <div class="text-center max-w-3xl">
                <h1 class="text-4xl font-bold text-red-700 mb-4">
                    Menghubungkan Petani & Distributor Cabai
                </h1>
                <p class="text-gray-700 mb-6">
                    Platform digital untuk mempertemukan petani cabai, distributor, dan pasar.
                    Kelola permintaan, tawarkan pasokan, dan bangun rantai distribusi cabai yang
                    adil, cepat, dan transparan.
                </p>
                <div class="space-x-4">
                    <a href="#"
                        class="bg-red-600 hover:bg-red-700 transition text-white px-6 py-3 rounded-lg font-semibold">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CARA KERJA -->
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-red-700 mb-8">
                Bagaimana Platform Distributor Cabai Bekerja
            </h2>
            <p class="text-gray-700 mb-12">
                Sistem kami membantu distribusi cabai dari petani ke pasar dengan proses yang
                lebih efisien dan terkontrol.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- STEP 1 -->
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <i class="fas fa-seedling text-red-600 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold text-red-700 mb-2">
                        Petani
                    </h3>

                </div>

                <!-- STEP 2 -->
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <i class="fas fa-truck text-red-600 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold text-red-700 mb-2">
                        Distributor Menawar
                    </h3>

                </div>

                <!-- STEP 3 -->
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <i class="fas fa-store text-red-600 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold text-red-700 mb-2">
                        Pasar Menerima Pasokan
                    </h3>

                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="bg-red-50 py-16">
        <div class="container mx-auto px-4 text-center">
            <div class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded-full mb-8">
                <i class="fas fa-check-circle mr-2"></i>
                Transparan & Terpercaya
            </div>

            <h2 class="text-3xl font-bold text-red-700 mb-8">
                Stabilkan Harga & Distribusi Cabai Bersama Kami
            </h2>

            <p class="text-gray-700 mb-12 max-w-2xl mx-auto">
                Baik Anda petani, distributor, maupun pemilik pasar,
                platform ini membantu menciptakan ekosistem distribusi cabai
                yang adil, efisien, dan berkelanjutan.
            </p>

            <a href="#" class="bg-red-600 hover:bg-red-700 transition text-white px-8 py-3 rounded-lg font-semibold">
                Mulai Sekarang
            </a>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
