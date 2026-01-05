@php
    $admin = Auth::guard('admin')->user();
    $isMaster = $admin && $admin->role === 'master';
    $region = $admin ? $admin->region : null;
@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Sidebar brand -->
    <div class="sidebar-brand px-3 py-3">
        <a href="/" class="brand-link d-flex align-items-center gap-3">
            <!-- Logo -->
            <img
                src="{{ asset('static/img/logo.png') }}"
                alt="Logo"
                class="rounded-circle shadow"
                style="width:48px; height:48px;" />

            <!-- Text -->
            <div class="d-flex flex-column">
                <span class="brand-text fw-semibold text-white leading-tight">
                    Futsal Rafelly
                </span>

                @auth('admin')
                <span class="text-xs text-gray-300 italic">
                    @if($isMaster)
                        <i class="fas fa-crown text-yellow-400 mr-1"></i>Master Admin
                    @else
                        <i class="fas fa-map-marker-alt text-blue-400 mr-1"></i>{{ $admin->region_label }}
                    @endif
                </span>
                @endauth
            </div>

        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">

            <!-- Sidebar Menu -->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-header">MENU UTAMA</li>

                <!-- DASHBOARD MENU -->
                <li class="nav-item">
                    <a
                        href="
                            @if($isMaster)
                                {{ route('admin.dashboard') }}
                            @else
                                {{ route('admin.dashboard.region', ['region' => $region]) }}
                            @endif
                        "
                        class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                        </svg>

                        <p>
                            @if($isMaster)
                                Master Dashboard
                            @else
                                Dashboard
                            @endif
                        </p>
                        <i class="fa-solid fa-angle-right ms-auto"></i>
                    </a>
                </li>

                <!-- DATA LAPANGAN (For Master & Regional) -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Data Lapangan<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inputLapangan.Lapangan') }}" class="nav-link">
                                <i class="nav-icon bi bi-plus-circle-fill"></i>
                                <p>Input Lapangan Baru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lapangan.daftar.Lapangan') }}" class="nav-link">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <p>List Lapangan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- INFORMASI LAINNYA (Slider, Event) -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-tree-fill"></i>
                        <p>Informasi Lainnya<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <!-- SLIDER MENU -->
                        <li class="nav-item">
                            <a href="{{ route('lapangan.slider') }}" class="nav-link">
                                <i class="nav-icon bi bi-images"></i>
                                <p>Slider</p>
                            </a>
                        </li>
                        <!-- EVENT MENU -->
                        <li class="nav-item">
                            <a href="{{ route('lapangan.event') }}" class="nav-link">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <p>Event</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- MANAJEMEN ADMIN - ONLY FOR MASTER -->
                @if($isMaster)
                <li class="nav-header">MANAJEMEN MASTER</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Manajemen Admin<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.register.show') }}" class="nav-link">
                                <i class="nav-icon bi bi-person-plus-fill"></i>
                                <p>Tambah Admin Baru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#admin-list" class="nav-link" data-bs-toggle="modal" data-bs-target="#adminListModal">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Daftar Semua Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-graph-up"></i>
                        <p>Laporan<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-bar-chart-fill"></i>
                                <p>Statistik Per Region</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-calendar-check-fill"></i>
                                <p>Booking Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="nav-header">LAINNYA</li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                       class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
    </div>
</aside>
