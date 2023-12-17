<div class="h-100 offcanvas-md offcanvas-end" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Company name ABC</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3" style="height: 100%">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 {{ $active == 'penawaran' ? 'active' : '' }}"
                    href="/penawaran">
                    <i class="bi bi-rocket"></i>
                    Penawaran
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-circle-square"></i>
                    Master
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="/user">
                            <i class="bi bi-people-fill"></i>
                            User
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/barang">
                            <i class="bi bi-shop"></i>
                            Barang
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
