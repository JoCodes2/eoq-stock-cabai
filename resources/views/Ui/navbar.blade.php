<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-green-700">OillyKampoeng</div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex space-x-4 items-center">
            <a href="/" class="{{ Request::is('/') ? 'text-green-700 font-semibold' : 'text-gray-700 hover:text-green-700' }}">Beranda</a>
            <a href="{{ url('/request-oil') }}" class="{{ Request::is('request-oil') ? 'text-green-700 font-semibold' : 'text-gray-700 hover:text-green-700' }}">Permintaan</a>

            @auth
                <a href="{{ url('/profile') }}" class="{{ Request::is('profile') ? 'text-green-700 font-semibold' : 'text-gray-700 hover:text-green-700' }}">Profile</a>
            @endauth

            <div class="flex space-x-2 ml-4">
                @guest
                    <a href="{{ url('/login') }}" class="bg-gray-400 text-white px-4 py-2 rounded-2xl">Masuk</a>
                    <a href="{{ url('/auth-register') }}" class="bg-green-700 text-white px-4 py-2 rounded-2xl">Daftar</a>
                @else
                    <button type="button" id="btnLogout" class="w-full bg-green-700 text-white px-4 py-2 rounded-2xl">Logout</button>
                @endguest
            </div>
        </nav>

        <div class="md:hidden">
            <button id="menu-button" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 space-y-2">
        <a href="/" class="block py-2 {{ Request::is('/') ? 'text-green-700 font-semibold' : 'text-gray-700 hover:text-green-700' }}">Beranda</a>
        <a href="{{ url('/request-oil') }}" class="block py-2 {{ Request::is('request-oil') ? 'text-green-700 font-semibold' : 'text-gray-700 hover:text-green-700' }}">Permintaan</a>

        @auth
            <a href="{{ url('/profile') }}" class="block py-2 {{ Request::is('profile') ? 'text-green-700 font-semibold' : 'text-gray-700 hover:text-green-700' }}">Profile</a>
        @endauth

        <div class="flex space-x-2 mt-2">
            @guest
                <a href="{{ url('/login') }}" class="w-1/2 text-center bg-gray-400 text-white px-4 py-2 rounded-2xl">Masuk</a>
                <a href="{{ url('/auth-register') }}" class="w-1/2 text-center bg-green-700 text-white px-4 py-2 rounded-2xl">Daftar</a>
            @else
                <button type="button" id="btnLogout" class="w-full bg-red-500 text-white px-4 py-2 rounded-2xl">Logout</button>
            @endguest
        </div>
    </div>
    <div id="confirmModalLogout" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Konfirmasi!</h2>
            <p id="confirmText" class="text-gray-700 text-sm mb-4">Apakah Anda yakin?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelBtnLogout" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">Batal</button>
                <button id="confirmBtnLogout" class="px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 text-white">Ya</button>
            </div>
        </div>
    </div>
</header>
