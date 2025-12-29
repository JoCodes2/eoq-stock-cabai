<nav class="flex flex-col md:flex-row gap-2 md:gap-6 text-sm md:text-base overflow-x-auto whitespace-nowrap px-2 md:px-0">
    <a href="{{ url('/form-request') }}"
        class="py-2 px-4 rounded-lg {{ Request::is('form-request') ? 'text-red-600 font-semibold border-b-2 border-red-600' : 'text-gray-500 hover:text-red-600' }} transition">
        Ajukan Permintaan
    </a>
    <a href="{{ url('/data-request') }}"
        class="py-2 px-4 rounded-lg {{ Request::is('data-request') ? 'text-red-600 font-semibold border-b-2 border-red-600' : 'text-gray-500 hover:text-red-600' }} transition">
        Riwayat
    </a>
</nav>
