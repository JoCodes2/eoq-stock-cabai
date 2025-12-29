@extends('Ui.master')

@section('content')
    <section class="bg-red-50 py-20 border-b border-red-100">
        <div class="container mx-auto px-4 flex justify-center items-center">
            <div class="text-center max-w-3xl">
                <div class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded-full mb-6 text-sm font-bold uppercase tracking-widest">
                    🌶️ Pusat Distribusi Cabai Segar
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-red-700 mb-6 leading-tight">
                    Pesan Cabai Berkualitas <br>Langsung dari Gudang Kami
                </h1>
                <p class="text-gray-600 mb-10 text-lg leading-relaxed">
                    Sistem pemesanan praktis untuk kebutuhan pasar dan industri.
                    Kami memastikan stok cabai di gudang selalu tersedia dan siap kirim ke lokasi Anda.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        <a href="/form-request" class="bg-red-600 hover:bg-red-700 transition text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-lg shadow-red-200 flex items-center justify-center gap-2">
                            🚀 Buat Permintaan Sekarang
                        </a>
                    @else
                        <a href="/login" class="bg-red-600 hover:bg-red-700 transition text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-lg shadow-red-200">
                            Masuk & Pesan
                        </a>
                        <a href="/register" class="bg-white border-2 border-red-600 text-red-600 hover:bg-red-50 transition px-8 py-4 rounded-2xl font-bold text-lg">
                            Daftar Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-black text-red-700 mb-4 uppercase italic">Proses Pemesanan Mudah</h2>
            <p class="text-gray-500 mb-16 max-w-xl mx-auto">
                Alur sederhana dari tangan Gudang kami langsung ke tangan Anda.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <div class="hidden md:block absolute top-1/3 left-0 w-full h-0.5 bg-red-100 -z-10"></div>

                <div class="flex flex-col items-center group">
                    <div class="w-20 h-20 bg-red-600 text-white rounded-3xl flex items-center justify-center text-3xl mb-6 shadow-xl group-hover:scale-110 transition transform">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">1. Pilih Jenis Cabai</h3>
                    <p class="text-gray-500 text-sm leading-relaxed px-4">
                        Pilih berbagai jenis cabai yang tersedia di stok gudang kami dan tentukan beratnya.
                    </p>
                </div>

                <div class="flex flex-col items-center group">
                    <div class="w-20 h-20 bg-red-600 text-white rounded-3xl flex items-center justify-center text-3xl mb-6 shadow-xl group-hover:scale-110 transition transform">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">2. Verifikasi Gudang</h3>
                    <p class="text-gray-500 text-sm leading-relaxed px-4">
                        Admin gudang akan memproses permintaan Anda dan menyiapkan stok barang sesuai pesanan.
                    </p>
                </div>

                <div class="flex flex-col items-center group">
                    <div class="w-20 h-20 bg-red-600 text-white rounded-3xl flex items-center justify-center text-3xl mb-6 shadow-xl group-hover:scale-110 transition transform">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">3. Pengiriman Cepat</h3>
                    <p class="text-gray-500 text-sm leading-relaxed px-4">
                        Setelah pesanan siap, cabai segera dikirim ke alamat Anda dalam kondisi tetap segar.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-black text-red-700 mb-6 uppercase italic">Kenapa Menggunakan <br>Layanan Gudang Kami?</h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="text-red-600 mt-1"><i class="fas fa-check-circle text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Stok Selalu Update</h4>
                                <p class="text-gray-500 text-sm">Informasi ketersediaan barang di gudang dipantau secara real-time melalui sistem.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-red-600 mt-1"><i class="fas fa-check-circle text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Transparansi Harga</h4>
                                <p class="text-gray-500 text-sm">Total estimasi harga langsung terlihat saat Anda membuat daftar permintaan.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-red-600 mt-1"><i class="fas fa-check-circle text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-gray-800">Pantau Riwayat Pesanan</h4>
                                <p class="text-gray-500 text-sm">Cek status pesanan Anda mulai dari 'Menunggu' hingga 'Selesai' di halaman riwayat.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-red-200/50 rounded-3xl blur-2xl -z-10"></div>
                    <div class="bg-white p-8 rounded-3xl shadow-2xl border border-red-50 text-center">
                        <i class="fas fa-receipt text-red-600 text-6xl mb-6 opacity-20"></i>
                        <p class="text-gray-600 italic font-medium">"Sistem ini sangat membantu kami dalam mendata setiap permintaan cabai yang masuk, sehingga distribusi ke pembeli jadi lebih terorganisir."</p>
                        <div class="mt-6">
                            <p class="font-bold text-gray-800 uppercase tracking-tighter">Admin Gudang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-red-700 py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-white mb-8">
                Siap Melakukan Pemesanan Cabai Hari Ini?
            </h2>
            <p class="text-red-100 mb-12 max-w-2xl mx-auto opacity-80">
                Bergabunglah dengan mitra pasar kami lainnya. Nikmati kemudahan akses stok gudang
                dan proses pengiriman yang terintegrasi.
            </p>
            <a href="{{ auth()->check() ? '/form-request' : '/login' }}" class="bg-white text-red-700 hover:bg-red-50 transition px-10 py-4 rounded-2xl font-black text-lg shadow-2xl uppercase tracking-widest">
                {{ auth()->check() ? 'Buka Dashboard' : 'Mulai Sekarang' }}
            </a>
        </div>
    </section>
@endsection
