<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <div class="user-sidebar text-center">
            <div class="dropdown">
                <div class="user-img">
                    <img src="{{ asset('assets/images/jpg/1.jpg') }}" alt="" class="rounded-circle">
                    <span class="avatar-online bg-success"></span>
                </div>
                <div class="user-info">
                    <h5 class="mt-3 font-size-16 text-white">{{ Auth::user()->name }}</h5>
                    <span class="font-size-13 text-white-50">
                        @if(Auth::user()->hasRole('admin')) Administrator
                        @elseif(Auth::user()->hasRole('petugas')) Petugas
                        @elseif(Auth::user()->hasRole('pimpinan')) Pimpinan
                        @else Pelanggan
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu Utama</li>

                <li class="{{ request()->routeIs('*.dashboard', 'dashboard') ? 'mm-active' : '' }}">
                    @php
                        $dashRoute = route('dashboard');
                        if (Auth::user()->hasRole('admin')) $dashRoute = route('admin.dashboard');
                        elseif (Auth::user()->hasRole('petugas')) $dashRoute = route('petugas.dashboard');
                        elseif (Auth::user()->hasRole('pelanggan')) $dashRoute = route('pelanggan.dashboard');
                        elseif (Auth::user()->hasRole('pimpinan')) $dashRoute = route('pimpinan.dashboard');
                    @endphp
                    <a href="{{ $dashRoute }}" class="waves-effect {{ request()->routeIs('*.dashboard', 'dashboard') ? 'active' : '' }}">
                        <i class="dripicons-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- ADMIN MENU --}}
                @hasrole('admin')
                <li class="{{ request()->routeIs('admin.users.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="waves-effect {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="dripicons-user-group"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.golongan_tarif.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.golongan_tarif.index') }}" class="waves-effect {{ request()->routeIs('admin.golongan_tarif.*') ? 'active' : '' }}">
                        <i class="mdi mdi-tag-multiple"></i>
                        <span>Golongan Tarif</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.pelanggan.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.pelanggan.index') }}" class="waves-effect {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-group"></i>
                        <span>Data Pelanggan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('petugas.pemakaian.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('petugas.pemakaian.index') }}" class="waves-effect {{ request()->routeIs('petugas.pemakaian.*') ? 'active' : '' }}">
                        <i class="mdi mdi-water"></i>
                        <span>Pemakaian Air</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('petugas.tagihan') ? 'mm-active' : '' }}">
                    <a href="{{ route('petugas.tagihan') }}" class="waves-effect {{ request()->routeIs('petugas.tagihan') ? 'active' : '' }}">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span>Tagihan & Pembayaran</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.pengaduan*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.pengaduan') }}" class="waves-effect {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
                        <i class="mdi mdi-bullhorn"></i>
                        <span>Pengaduan</span>
                    </a>
                </li>
                
                <li class="menu-title">Pembayaran Online</li>
                <li class="{{ request()->routeIs('admin.pembayaran_gateway.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.pembayaran_gateway.index') }}" class="waves-effect {{ request()->routeIs('admin.pembayaran_gateway.*') ? 'active' : '' }}">
                        <i class="mdi mdi-credit-card-outline"></i>
                        <span>History Gateway</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.gateway_logs.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.gateway_logs.index') }}" class="waves-effect {{ request()->routeIs('admin.gateway_logs.*') ? 'active' : '' }}">
                        <i class="mdi mdi-lan"></i>
                        <span>Log Gateway (Webhook)</span>
                    </a>
                </li>

                <li class="menu-title">Report</li>
                <li class="{{ request()->routeIs('laporan.*') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-file-document"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="sub-menu {{ request()->routeIs('laporan.*') ? 'mm-show' : '' }}" aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}">
                        <li class="{{ request()->routeIs('laporan.pembayaran') ? 'mm-active' : '' }}">
                            <a href="{{ route('laporan.pembayaran') }}" class="{{ request()->routeIs('laporan.pembayaran') ? 'active' : '' }}">Laporan Pembayaran</a>
                        </li>
                        <li class="{{ request()->routeIs('laporan.pelanggan') ? 'mm-active' : '' }}">
                            <a href="{{ route('laporan.pelanggan') }}" class="{{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}">Laporan Pelanggan</a>
                        </li>
                        <li class="{{ request()->routeIs('laporan.pemakaian') ? 'mm-active' : '' }}">
                            <a href="{{ route('laporan.pemakaian') }}" class="{{ request()->routeIs('laporan.pemakaian') ? 'active' : '' }}">Laporan Pemakaian Air</a>
                        </li>
                    </ul>
                </li>
                @endhasrole

                {{-- PETUGAS MENU --}}
                @hasrole('petugas')
                <li class="{{ request()->routeIs('admin.pelanggan.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.pelanggan.index') }}" class="waves-effect {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-group"></i>
                        <span>Data Pelanggan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('petugas.pemakaian.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('petugas.pemakaian.index') }}" class="waves-effect {{ request()->routeIs('petugas.pemakaian.*') ? 'active' : '' }}">
                        <i class="mdi mdi-water"></i>
                        <span>Input Pemakaian Air</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('petugas.tagihan') ? 'mm-active' : '' }}">
                    <a href="{{ route('petugas.tagihan') }}" class="waves-effect {{ request()->routeIs('petugas.tagihan') ? 'active' : '' }}">
                        <i class="mdi mdi-cash-multiple"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.pengaduan*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.pengaduan') }}" class="waves-effect {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
                        <i class="mdi mdi-bullhorn"></i>
                        <span>Pengaduan</span>
                    </a>
                </li>
                @endhasrole

                {{-- PELANGGAN MENU --}}
                @hasrole('pelanggan')
                <li class="{{ request()->routeIs('pelanggan.tagihan') ? 'mm-active' : '' }}">
                    <a href="{{ route('pelanggan.tagihan') }}" class="waves-effect {{ request()->routeIs('pelanggan.tagihan') ? 'active' : '' }}">
                        <i class="mdi mdi-receipt"></i>
                        <span>Tagihan Saya</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('pelanggan.riwayat') ? 'mm-active' : '' }}">
                    <a href="{{ route('pelanggan.riwayat') }}" class="waves-effect {{ request()->routeIs('pelanggan.riwayat') ? 'active' : '' }}">
                        <i class="mdi mdi-history"></i>
                        <span>Riwayat Pembayaran</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('pelanggan.pengaduan.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('pelanggan.pengaduan.index') }}" class="waves-effect {{ request()->routeIs('pelanggan.pengaduan.*') ? 'active' : '' }}">
                        <i class="mdi mdi-bullhorn"></i>
                        <span>Pengaduan Saya</span>
                    </a>
                </li>
                @endhasrole

                {{-- PIMPIMAN MENU --}}
                @hasrole('pimpinan')
                <li class="{{ request()->routeIs('laporan.*') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-file-document"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="sub-menu {{ request()->routeIs('laporan.*') ? 'mm-show' : '' }}" aria-expanded="{{ request()->routeIs('laporan.*') ? 'true' : 'false' }}">
                        <li class="{{ request()->routeIs('laporan.pembayaran') ? 'mm-active' : '' }}">
                            <a href="{{ route('laporan.pembayaran') }}" class="{{ request()->routeIs('laporan.pembayaran') ? 'active' : '' }}">Laporan Pembayaran</a>
                        </li>
                        <li class="{{ request()->routeIs('laporan.pelanggan') ? 'mm-active' : '' }}">
                            <a href="{{ route('laporan.pelanggan') }}" class="{{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}">Laporan Pelanggan</a>
                        </li>
                        <li class="{{ request()->routeIs('laporan.pemakaian') ? 'mm-active' : '' }}">
                            <a href="{{ route('laporan.pemakaian') }}" class="{{ request()->routeIs('laporan.pemakaian') ? 'active' : '' }}">Laporan Pemakaian Air</a>
                        </li>
                    </ul>
                </li>
                @endhasrole
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
