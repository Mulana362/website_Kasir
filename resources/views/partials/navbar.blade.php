<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        {{-- BRAND --}}
        <a class="navbar-brand" href="{{ route('kasir.index') }}">
            <span class="brand-primary">Kasir</span> Serba-Serbi Banten
        </a>

        {{-- TOGGLER --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                @auth

                    {{-- Kasir --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kasir.*') ? 'active' : '' }}"
                           href="{{ route('kasir.index') }}">
                            Kasir
                        </a>
                    </li>

                    {{-- ✅ Laporan Penjualan --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('laporan.penjualan*') ? 'active' : '' }}"
                           href="{{ route('laporan.penjualan') }}">
                            Laporan Penjualan
                        </a>
                    </li>

                    {{-- Admin Produk --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                           href="{{ route('admin.products.index') }}">
                            Admin Produk
                        </a>
                    </li>

                    {{-- Pemasukan Barang --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}"
                           href="{{ route('barang-masuk.index') }}">
                            Pemasukan Barang
                        </a>
                    </li>

                    {{-- Pengeluaran Barang --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}"
                           href="{{ route('barang-keluar.index') }}">
                            Pengeluaran Barang
                        </a>
                    </li>

                    {{-- Logout --}}
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn nav-link btn-link text-white" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>

                @endauth

            </ul>

        </div>

    </div>
</nav>
