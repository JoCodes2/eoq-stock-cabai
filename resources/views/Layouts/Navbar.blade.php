<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <ul class="navbar-nav flex-row align-items-center ms-auto">


            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        <!-- Avatar -->
                        <div class="">
                            <span class="fw-bold text-nowrap">
                                @auth
                                    {{ auth()->user()->name }}
                                @endauth
                            </span>
                            <span class="px-2">|</span>
                        </div>
                        <div class="avatar avatar-online">
                            <img src="{{ asset('assets/assets/img/avatars/1.png') }}" alt="Avatar"
                                class="w-px-40 h-auto rounded-circle" />
                        </div>
                        <!-- Nama di sebelah avatar -->
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <button class="dropdown-item" id="btnLogout">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </button>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
