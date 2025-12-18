<nav class="flex flex-col md:flex-row gap-2 md:gap-6 text-sm md:text-base overflow-x-auto whitespace-nowrap px-2 md:px-0">
    <a href="{{ url('/profile') }}"
        class="py-2 px-4 rounded-lg {{ Request::is('profile') ? 'text-green-600 font-semibold border-b-2 border-green-600' : 'text-gray-500 hover:text-green-600' }} transition">
        Profil
    </a>

    @if (Auth::check() && Auth::user()->role === 'supplier')
        <a href="{{ url('/offers') }}"
            class="py-2 px-4 rounded-lg {{ Request::is('offers') ? 'text-green-600 font-semibold border-b-2 border-green-600' : 'text-gray-500 hover:text-green-600' }} transition">
            Penawaran Anda
        </a>
    @endif
    @if (Auth::check() && Auth::user()->role === 'market')
    <a href="{{ url('/form-request') }}"
        class="py-2 px-4 rounded-lg {{ Request::is('form-request') ? 'text-green-600 font-semibold border-b-2 border-green-600' : 'text-gray-500 hover:text-green-600' }} transition">
        Ajukan Permintaan
    </a>

    <a href="{{ url('/data-request-suplier') }}"
        class="py-2 px-4 rounded-lg {{ Request::is('data-request-suplier') ? 'text-green-600 font-semibold border-b-2 border-green-600' : 'text-gray-500 hover:text-green-600' }} transition">
        Penawaran
    </a>

    <a href="{{ url('/data-request-market') }}"
        class="py-2 px-4 rounded-lg {{ Request::is('data-request-market') ? 'text-green-600 font-semibold border-b-2 border-green-600' : 'text-gray-500 hover:text-green-600' }} transition">
        Riwayat
    </a>
    @endif
</nav>
