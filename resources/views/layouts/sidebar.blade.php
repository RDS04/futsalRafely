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
                    Region {{ Auth::guard('admin')->user()->region }}
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
                @php
                $region = Auth::user()->region ?? null;
                @endphp

                <li class="nav-item">
                    <a
                        href="
                            {{ 
                                $region === 'padang' ? route('adminPadang') :
                                ($region === 'sijunjung' ? route('adminSijunjung') :
                                ($region === 'bukittinggi' ? route('adminBukittinggi') : '#')) 
                            }}"
                        class="nav-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                        </svg>

                        <p>Dashboard</p>
                        <i class="fa-solid fa-angle-right ms-auto"></i>
                    </a>
                </li>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Master Latihan <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('pendaftaran') }}" class="nav-link active">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Latihan 1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('dosens') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Data Dosen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('products') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Crud Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('anggota') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Anggota</p>
                            </a>
                        </li>
                    </ul>
                </li>



                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Data Data Tugas <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('input_data_dosen') }}" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Tugas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/info-box.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Info Box</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./widgets/cards.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Cards</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>
                            Layout Options
                            <span class="nav-badge badge text-bg-secondary me-3">6</span>
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./layout/sidebar-mini.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/collapsed-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Collapsed</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/logo-switch.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Sidebar Mini <small>+ Logo Switch</small></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./layout/layout-rtl.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Layout RTL</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-tree-fill"></i>
                        <p>UI Elements <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./UI/general.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>General</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./UI/icons.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Icons</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./UI/timeline.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Timeline</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>Forms <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./forms/general.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>General Elements</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-table"></i>
                        <p>Tables <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./tables/simple.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Simple Tables</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">EXAMPLES</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-in-right"></i>
                        <p>Auth <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                <p>User<i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./examples/login.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Login</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./examples/register.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Register</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon bi bi-box-arrow-in-right"></i>
                                <p>Version 2 <i class="nav-arrow bi bi-chevron-right"></i></p>
                            </a>

                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./examples/login-v2.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Login</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./examples/register-v2.html" class="nav-link">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Register</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="./examples/lockscreen.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Lockscreen</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">DOCUMENTATIONS</li>

                <li class="nav-item">
                    <a href="./docs/introduction.html" class="nav-link">
                        <i class="nav-icon bi bi-download"></i>
                        <p>Installation</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./docs/layout.html" class="nav-link">
                        <i class="nav-icon bi bi-grip-horizontal"></i>
                        <p>Layout</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./docs/color-mode.html" class="nav-link">
                        <i class="nav-icon bi bi-star-half"></i>
                        <p>Color Mode</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-ui-checks-grid"></i>
                        <p>Components <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./docs/components/main-header.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Main Header</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./docs/components/main-sidebar.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Main Sidebar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-filetype-js"></i>
                        <p>Javascript <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./docs/javascript/treeview.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Treeview</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- End Sidebar Menu -->
        </nav>
    </div>
    <!-- End Sidebar Wrapper -->
</aside>
<!-- End Sidebar -->