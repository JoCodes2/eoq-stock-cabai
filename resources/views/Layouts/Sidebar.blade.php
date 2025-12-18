        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="/home" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/assets/scm.png') }}" alt="Logo" class="img-fluid" width="50"
                            height="50">
                    </span>
                    <span class="text-start app-brand-text fw-bold ms-2 ">
                        <small>Supply Chain Management</small><br>
                        <small>Management</small><br>
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





            </ul>
        </aside>
        <!-- / Menu -->
