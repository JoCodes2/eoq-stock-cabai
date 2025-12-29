        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="/home" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/assets/img/tanicabai.jpeg') }}" alt="Logo" class="img-fluid"
                            width="50" height="50">
                    </span>
                    <span class="text-start app-brand-text fw-bold ms-2 ">
                        <small>STOK CABAI</small><br>
                        <small>E O Q</small><br>
                    </span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <li class="menu-item {{ request()->is('home') ? 'active' : '' }}">
                    <a href="/home" class="menu-link">
                        <i class="menu-icon fa-solid fa-house"></i>
                        <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('user') ? 'active' : '' }}">
                    <a href="/user" class="menu-link">
                        <i class="menu-icon fa-solid fa-user"></i>
                        <div data-i18n="Analytics">Pengguna</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('master-data') ? 'active' : '' }}">
                    <a href="/master-data" class="menu-link">
                        <i class="menu-icon fa-solid fa-box"></i>
                        <div data-i18n="Analytics">Master Data</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('stok-masuk') ? 'active' : '' }}">
                    <a href="/stok-masuk" class="menu-link">
                        <i class="menu-icon fa-solid fa-exchange-alt"></i>
                        <div data-i18n="Analytics">Stok Masuk</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('request') ? 'active' : '' }}">
                    <a href="/request" class="menu-link">
                        <i class="menu-icon fa-solid fa-list"></i>
                        <div data-i18n="Analytics">Permintaan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('stok-keluar') ? 'active' : '' }}">
                    <a href="/stok-keluar" class="menu-link">
                        <i class="menu-icon fa-solid fa-box-open"></i>
                        <div data-i18n="Analytics">Stok Keluar</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('eoq') ? 'active' : '' }}">
                    <a href="/eoq" class="menu-link">
                        <i class="menu-icon fa-solid fa-square-root-variable"></i>
                        <div data-i18n="Analytics">Pengaturan EOQ</div>
                    </a>
                </li>
            </ul>
        </aside>
        <!-- / Menu -->
