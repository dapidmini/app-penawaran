<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">App Penawaran</a>

    <ul class="navbar-nav flex-row d-md-none">
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSearch" aria-controls="navbarSearch" aria-expanded="false"
                aria-label="Toggle search">
                <svg class="bi">
                    <use xlink:href="#search" />
                </svg>
            </button>
        </li>
        <li class="nav-item text-nowrap">
            <button class="nav-link px-3 text-white" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <svg class="bi">
                    <use xlink:href="#list" />
                </svg>
            </button>
        </li>
    </ul>

    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item dropdown d-flex align-items-center">
            Halo, <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                {{ Auth::check() ? Auth::user()->name : '' }}
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-gear-fill"></i>
                        Setting
                    </a>
                </li>
                <li>
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>

    <div id="navbarSearch" class="navbar-search w-100 collapse">
        <input class="form-control w-100 rounded-0 border-0" type="text" placeholder="Search"
            aria-label="Search">
    </div>
</header>
