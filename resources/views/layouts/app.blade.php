<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Panti Asuhan Assholihin</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="d-flex"> <!-- Wrapper transparent to show body background -->
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column flex-shrink-0">
            <div class="brand-section">
                <img src="{{ asset('assets/images/logo_yayasan.png') }}" alt="Logo" class="sidebar-logo">
                <div class="brand-text">YAYASAN</div>
                <div class="brand-text">ASSHOLIHIN</div>
                <div class="brand-subtext">Panti Asuhan</div>
            </div>
            
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-header">Data Utama</li>
                <li class="nav-item">
                    <a href="{{ route('anak.index') }}" class="nav-link {{ request()->routeIs('anak.*') ? 'active' : '' }}">
                        <i class="fas fa-child"></i> <span>Data Anak Asuh</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengurus.index') }}" class="nav-link {{ request()->routeIs('pengurus.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i> <span>Data Pengurus</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gallery.index') }}" class="nav-link {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <i class="fas fa-images"></i> <span>Gallery Kegiatan</span>
                    </a>
                </li>

                <li class="nav-header">Keuangan</li>
                <li class="nav-item">
                    <a href="{{ route('donasi.index') }}" class="nav-link {{ request()->routeIs('donasi.*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-heart"></i> <span>Donasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pengeluaran.index') }}" class="nav-link {{ request()->routeIs('pengeluaran.*') || request()->routeIs('transaksi.*') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i> <span>Transaksi</span>
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->role === 'ADMIN')
                    <li class="nav-header">Data Master</li>
                    <li class="nav-item">
                        <a href="{{ route('donatur.index') }}" class="nav-link {{ request()->routeIs('donatur.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> <span>Donatur</span>
                        </a>
                    </li>

                    <li class="nav-header">Laporan</li>
                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i> <span>Laporan Keuangan</span>
                        </a>
                    </li>
                @endif
            </ul>

            <!-- User Menu moved to Topbar -->
        </div>

        <!-- Main Content Wrapper -->
        <div class="d-flex flex-column flex-grow-1" style="height: 100vh; overflow: hidden;">
            
            <!-- Topbar -->
            <nav class="navbar topbar px-4 py-3 d-flex justify-content-between align-items-center shadow-sm" style="border-bottom-left-radius: 30px; border-bottom-right-radius: 30px; background: white;">
                
                <div class="d-flex align-items-center">
                    <!-- Mobile Toggle -->
                    <button class="btn btn-outline-secondary d-lg-none me-3 border-0 bg-transparent" id="sidebarToggle">
                        <i class="fas fa-bars fa-lg text-muted"></i>
                    </button>

                    <!-- Search -->
                    <div class="search-wrapper d-none d-md-flex align-items-center rounded-pill px-3 py-2" style="width: 300px;">
                        <i class="fas fa-search text-muted me-2"></i>
                        <input type="text" class="form-control border-0 bg-transparent p-0" placeholder="Search for anything...">
                    </div>
                </div>

                <!-- Right Nav -->
                <div class="d-flex align-items-center gap-4">
                    <!-- Theme Switch -->
                    <div class="theme-switch-wrapper">
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle">
                            <span class="slider">
                                <div class="star star_1"></div>
                                <div class="star star_2"></div>
                                <div class="star star_3"></div>
                                <svg viewBox="0 0 16 16" class="cloud_1 cloud">
                                    <path transform="matrix(.77976 0 0 .78395-299.99-418.63)" fill="#fff" d="m391.84 540.91c-.421-.329-.949-.524-1.523-.524-1.351 0-2.451 1.084-2.485 2.435-1.395.526-2.388 1.88-2.388 3.466 0 1.874 1.385 3.423 3.182 3.667v.034h12.73v-.006c1.775-.104 3.182-1.584 3.182-3.395 0-1.747-1.309-3.186-2.994-3.379.007-.106.011-.214.011-.322 0-2.707-2.271-4.901-5.072-4.901-2.073 0-3.856 1.202-4.643 2.925"></path>
                                </svg>
                            </span>
                        </label>
                    </div>

                    <!-- Notifications -->
                    <div class="position-relative cursor-pointer" id="notificationIcon" data-bs-toggle="offcanvas" data-bs-target="#notificationSidebar" aria-controls="notificationSidebar">
                        <i class="fas fa-bell fa-lg text-muted"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none; font-size: 0.5rem; padding: 3px 5px;">
                            0
                        </span>
                    </div>
                    
                    <!-- User Dropdown (Moved from Sidebar) -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle no-arrow" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'User' }}&background=0E4D2B&color=fff" alt="" class="user-avatar shadow-sm border border-2 border-white">
                            <div class="ms-2 d-none d-sm-block text-start">
                                <div class="fw-bold text-dark small mb-0">{{ Auth::user()->name ?? 'User' }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ Auth::user()->role ?? 'Admin' }}</small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" aria-labelledby="dropdownUser1" style="border-radius: 12px; overflow: hidden;">
                            <li><a class="dropdown-item py-2 small" href="{{ route('profile.show') }}"><i class="fas fa-user me-2 text-primary"></i> Profil</a></li>
                            <li><a class="dropdown-item py-2 small" href="{{ route('profile.change-password') }}"><i class="fas fa-key me-2 text-warning"></i> Ganti Password</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger small"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Scrollable Page Content -->
            <div class="flex-grow-1 overflow-auto">
                <div class="content-wrapper container-fluid p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <strong><i class="fas fa-exclamation-circle me-2"></i> Terjadi Kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>

    <div class="sidebar-overlay"></div> <!-- Overlay for mobile -->

    <!-- Notification Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="notificationSidebar" aria-labelledby="notificationSidebarLabel" style="width: 350px;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fw-bold" id="notificationSidebarLabel">Notifikasi</h5>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" id="markAllReadBtn" title="Tandai semua dibaca">
                    <i class="fas fa-check-double"></i>
                </button>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body p-0">
            <div id="notificationList">
                <!-- Notifications will be loaded here -->
                <div class="text-center p-5 text-muted">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Sidebar Toggle Logic
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('show');
                });
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('show');
                });
            }
            
            // Dark Mode Logic (existing)
            const themeToggle = document.getElementById('theme-toggle');
            const body = document.body;
            
            // Check Local Storage
            if (localStorage.getItem('theme') === 'dark') {
                body.classList.add('dark-mode');
                themeToggle.checked = true;
            }
            
            themeToggle.addEventListener('change', function() {
                if (this.checked) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('theme', 'light');
                }
            });

            // Notification Logic
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationBadge = document.getElementById('notificationBadge');
            const notificationList = document.getElementById('notificationList');
            const markAllReadBtn = document.getElementById('markAllReadBtn');
            const notificationSidebar = document.getElementById('notificationSidebar');

            // Load Notifications when offcanvas opens
            notificationSidebar.addEventListener('show.bs.offcanvas', function () {
                fetchNotifications();
            });

            // Mark all as read
            markAllReadBtn.addEventListener('click', function() {
                fetch('{{ route("notifications.markRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        fetchNotifications(); // Reload to update UI
                    }
                });
            });

            function fetchNotifications() {
                fetch('{{ route("notifications.index") }}')
                .then(response => response.json())
                .then(data => {
                    updateBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(error => console.error('Error fetching notifications:', error));
            }

            function updateBadge(count) {
                if (count > 0) {
                    notificationBadge.style.display = 'block';
                    notificationBadge.textContent = count > 99 ? '99+' : count;
                } else {
                    notificationBadge.style.display = 'none';
                }
            }

            function renderNotifications(notifications) {
                if (notifications.length === 0) {
                    notificationList.innerHTML = `
                        <div class="text-center p-5 text-muted">
                            <i class="fas fa-bell-slash fa-3x mb-3 text-secondary opacity-50"></i>
                            <p class="mb-0">Belum ada notifikasi.</p>
                        </div>
                    `;
                    return;
                }

                let html = '<div class="list-group list-group-flush">';
                notifications.forEach(n => {
                    const bgClass = n.read_at ? '' : 'bg-light';
                    html += `
                        <a href="#" class="list-group-item list-group-item-action ${bgClass} position-relative">
                            <div class="d-flex align-items-start">
                                <div class="avatar-sm rounded-circle bg-light-${n.color} text-${n.color} d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="${n.icon}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <p class="mb-0 text-dark fw-bold small text-truncate" style="max-width: 200px;">${n.data.message}</p>
                                        <small class="text-muted" style="font-size: 0.7rem;">${n.created_at}</small>
                                    </div>
                                    <p class="mb-0 text-muted small text-truncate" style="max-width: 230px;">
                                       ${n.data.message}
                                    </p>
                                </div>
                                ${!n.read_at ? '<span class="position-absolute top-50 end-0 translate-middle-y me-3 badge bg-danger rounded-circle p-1" style="width: 8px; height: 8px;"></span>' : ''}
                            </div>
                        </a>
                    `;
                });
                html += '</div>';
                notificationList.innerHTML = html;
            }

            // Initial Badge Load
            fetchNotifications();
        });
    </script>
    
    @stack('scripts')
</body>
</html>
