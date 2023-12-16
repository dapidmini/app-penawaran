<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand" href="#">App-Penawaran</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ $active == 'list-penawaran' ? 'active' : '' }}" aria-current="page"
                        href="/penawaran">
                        Penawaran
                    </a>
                </li>
                <li class="nav-item dropdown">
                    @php
                        $include_array = ['master-user', 'master-barang'];
                        $is_active = Arr::exists($include_array, $active) ? 'active' : '';
                    @endphp

                    <a class="nav-link dropdown-toggle {{ $is_active }}" href="#" role="button"
                        data-bs-toggle="dropdown">
                        Master
                    </a>
                    <ul class="dropdown-menu {{ $is_active == 'active' ? 'show' : '' }}">
                        <li><a class="dropdown-item" href="#">User</a></li>
                        <li><a class="dropdown-item" href="#">Barang</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Laporan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Penawaran</a></li>
                        <li><a class="dropdown-item" href="#">Barang</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">
                        {{ Auth::check() ? Auth::user()->name : '' }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
