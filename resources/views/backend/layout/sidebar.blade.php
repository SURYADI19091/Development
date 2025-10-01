<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('backend.dashboard') }}" class="brand-link">
        <i class="fas fa-landmark brand-image img-circle elevation-3 ml-3" style="opacity: .8; color: white;"></i>
        <span class="brand-text font-weight-light">Admin Panel</span>
        <small class="d-block text-sm opacity-75">Desa Ciuwlan</small>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/160x160/28a745/fff?text=' . strtoupper(substr(Auth::user()->name, 0, 1)) }}" 
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('user.profile') }}" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-light">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('backend.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('backend.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Data Management Section -->
                <li class="nav-header">DATA MANAGEMENT</li>
                
                @can('manage.users')
                <li class="nav-item">
                    <a href="{{ route('backend.users.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Pengguna
                            @if(isset($pendingUsersCount) && $pendingUsersCount > 0)
                                <span class="badge badge-info right">{{ $pendingUsersCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                @endcan

                @can('manage.population_data')
                <li class="nav-item">
                    <a href="{{ route('backend.population.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.population.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Data Penduduk</p>
                    </a>
                </li>
                @endcan

                @can('manage.village_data')
                <li class="nav-item {{ request()->routeIs('backend.village.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('backend.village.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Data Desa
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.village.profile') }}" 
                               class="nav-link {{ request()->routeIs('backend.village.profile') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profil Desa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.village.officials') }}" 
                               class="nav-link {{ request()->routeIs('backend.village.officials') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Perangkat Desa</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- Content Management Section -->
                <li class="nav-header">CONTENT MANAGEMENT</li>
                
                @can('manage.content')
                <li class="nav-item">
                    <a href="{{ route('backend.news.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.news.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>Berita</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.announcements.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.announcements.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Pengumuman</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.agenda.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.agenda.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Agenda</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.gallery.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.gallery.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Galeri</p>
                    </a>
                </li>
                @endcan

                @can('manage.services')
                <li class="nav-item {{ request()->routeIs('backend.umkm.*', 'backend.tourism.*', 'backend.banners.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('backend.umkm.*', 'backend.tourism.*', 'backend.banners.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-concierge-bell"></i>
                        <p>
                            Layanan Publik
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.umkm.index') }}" 
                               class="nav-link {{ request()->routeIs('backend.umkm.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>UMKM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.tourism.index') }}" 
                               class="nav-link {{ request()->routeIs('backend.tourism.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Wisata</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.banners.index') }}" 
                               class="nav-link {{ request()->routeIs('backend.banners.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Banner</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- Communication Section -->
                <li class="nav-header">KOMUNIKASI</li>
                
                @can('manage.contact_messages')
                <li class="nav-item">
                    <a href="{{ route('backend.contact.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.contact.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Pesan Kontak
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                <span class="badge badge-info right">{{ $unreadMessagesCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>
                @endcan

                @can('process.service_requests')
                <li class="nav-item">
                    <a href="{{ route('backend.letter-requests.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.letter-requests.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Pengajuan Surat</p>
                    </a>
                </li>
                @endcan

                <!-- Financial Management Section -->
                @can('manage.budget_data')
                <li class="nav-header">KEUANGAN</li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.budget.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.budget.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>APBDes</p>
                    </a>
                </li>
                @endcan

                <!-- Reports & Analytics Section -->
                @can('generate.reports')
                <li class="nav-header">LAPORAN & ANALISIS</li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.statistics') }}" 
                       class="nav-link {{ request()->routeIs('backend.statistics') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Statistik</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.reports') }}" 
                       class="nav-link {{ request()->routeIs('backend.reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-chart-pie"></i>
                        <p>Generate Laporan</p>
                    </a>
                </li>
                @endcan

                <!-- System Management Section -->
                @can('manage.settings')
                <li class="nav-header">SISTEM</li>
                
                <li class="nav-item">
                    <a href="{{ route('backend.settings.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Pengaturan</p>
                    </a>
                </li>
                
                @can('manage.permissions')
                <li class="nav-item">
                    <a href="{{ route('backend.permissions.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.permissions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>Permission</p>
                    </a>
                </li>
                @endcan
                
                @can('manage.system_backup')
                <li class="nav-item">
                    <a href="{{ route('backend.backup.index') }}" 
                       class="nav-link {{ request()->routeIs('backend.backup.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Backup</p>
                    </a>
                </li>
                @endcan

                @can('view.system_logs')
                <li class="nav-item">
                    <a href="{{ route('backend.logs') }}" 
                       class="nav-link {{ request()->routeIs('backend.logs') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>System Logs</p>
                    </a>
                </li>
                @endcan
                @endcan
            </ul>
        </nav>

    </div>
</aside>

