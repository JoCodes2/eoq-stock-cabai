<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Brand -->
        <div class="text-2xl font-bold text-red-700">
            TaniCabai
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex space-x-4 items-center">
            <a href="/"
                class="{{ Request::is('/') ? 'text-red-700 font-semibold' : 'text-gray-700 hover:text-red-700' }}">
                Beranda
            </a>
            @auth
            @if(auth()->user()->role === 'pembeli')
                <a href="{{ url('/form-request') }}"
                    class="{{ Request::is('form-request') ? 'text-red-700 font-semibold' : 'text-gray-700 hover:text-red-700' }}">
                    Ajukan Permintaan
                </a>
            @endif
            @endauth

            <div class="flex space-x-2 ml-4">
                @guest
                    <a href="{{ url('/login') }}"
                        class="bg-red-100 text-red-700 px-4 py-2 rounded-2xl hover:bg-red-200 transition">
                        Masuk
                    </a>
                    <a href="{{ url('/register') }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-2xl transition">
                        Daftar
                    </a>
                @else
                    <button type="button" id="btnLogout"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-2xl transition">
                        Logout
                    </button>
                @endguest
            </div>
        </nav>

        <!-- Mobile Button -->
        <div class="md:hidden">
            <button id="menu-button" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden px-4 pb-4 space-y-2">
        <a href="/"
            class="block py-2 {{ Request::is('/') ? 'text-red-700 font-semibold' : 'text-gray-700 hover:text-red-700' }}">
            Beranda
        </a>
        @auth
            @if(auth()->user()->role === 'pembeli')
                <a href="{{ url('/form-request') }}"
                    class="{{ Request::is('form-request') ? 'text-red-700 font-semibold' : 'text-gray-700 hover:text-red-700' }}">
                    Ajukan Permintaan
                </a>
            @endif
        @endauth

        <div class="flex space-x-2 mt-2">
            @guest
                <a href="{{ url('/login') }}" class="w-1/2 text-center bg-red-100 text-red-700 px-4 py-2 rounded-2xl">
                    Masuk
                </a>
                <a href="{{ url('/register') }}"
                    class="w-1/2 text-center bg-red-600 text-white px-4 py-2 rounded-2xl">
                    Daftar
                </a>
            @else
                <button type="button" id="btnLogout"
                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-2xl">
                    Logout
                </button>
            @endguest
        </div>
    </div>

    <!-- Modal Logout -->
    <div id="confirmModalLogout"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">
                Konfirmasi Logout
            </h2>
            <p id="confirmText" class="text-gray-700 text-sm mb-4">
                Apakah Anda yakin ingin keluar dari akun?
            </p>
            <div class="flex justify-end space-x-3">
                <button id="cancelBtnLogout" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700">
                    Batal
                </button>
                <button id="confirmBtnLogout" class="px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>
</header>
