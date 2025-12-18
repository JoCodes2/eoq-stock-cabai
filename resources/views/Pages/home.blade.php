@extends('Ui.master')
@section('content')
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4 flex justify-center items-center">
          <div>
            <h1 class="text-4xl font-bold text-green-700 mb-4 items-center">
              Hubungkan Penjual dan Suplier Minyak Kelapa
            </h1>
            <p class="text-gray-700 mb-6 items-center">
              Platform digital yang menghubungkan pemasok minyak kelapa dengan pasar yang membutuhkan. Posting permintaan, tawarkan pasokan, dan jalin kemitraan bisnis yang menguntungkan.
            </p>
            <div class="space-x-4 items-center">
              <a href="#" class="bg-green-700 text-white px-4 py-2 rounded">Daftar Sekarang</a>
            </div>
          </div>
        </div>
    </section>
    <section class="py-16">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-green-700 mb-8">Bagaimana OillyKampoeng Bekerja</h2>
        <p class="text-gray-700 mb-12">
          Platform kami dirancang untuk memudahkan transaksi dan kemitraan antara pemasok minyak kelapa dan pasar yang membutuhkan pasokan.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white p-6 rounded shadow">
            <i class="fas fa-edit text-green-700 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-green-700 mb-2">Posting Kebutuhan</h3>
            <p class="text-gray-700">
              Market dapat dengan mudah memposting detail kebutuhan minyak kelapa mereka dalam platform.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <i class="fas fa-handshake text-green-700 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-green-700 mb-2">Penawaran Suplier</h3>
            <p class="text-gray-700">
              Suplier minyak kelapa dapat melihat permintaan suplier untuk memberikan penawaran terbaik mereka.
            </p>
          </div>
          <div class="bg-white p-6 rounded shadow">
            <i class="fas fa-check-circle text-green-700 text-3xl mb-4"></i>
            <h3 class="text-xl font-bold text-green-700 mb-2">Pilih Suplier Terbaik</h3>
            <p class="text-gray-700">
              Market dapat memilih dari berbagai penawaran suplier untuk mendapatkan produk berkualitas dengan harga terbaik.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-gray-100 py-16">
      <div class="container mx-auto px-4 text-center">
        <div class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full mb-8">
          <i class="fas fa-check-circle mr-2"></i>
          Mudah Digunakan
        </div>
        <h2 class="text-3xl font-bold text-green-700 mb-8">
          Kembangkan Bisnis Minyak Kelapa Anda Bersama Kami
        </h2>
        <p class="text-gray-700 mb-12">
          Baik Anda seorang pemasok atau pemilik pasar, platform kami membantu Anda mengembangkan bisnis dengan menghubungkan Anda dengan mitra yang tepat.
        </p>
      </div>
    </section>
@endsection
@section('scripts')

@endsection
