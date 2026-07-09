<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'DompetKu - Aplikasi Keuangan Pribadi')</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- NProgress for Loading -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>
        // Check for saved theme preference to prevent FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
    @yield('styles')
</head>
<body class="">

    @guest
        @yield('content')
    @else
        <div class="app-container">
            <!-- Mobile Overlay -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <!-- Sidebar -->
            <aside class="sidebar" id="sidebar">
                <div class="brand">
                    <i class="fas fa-wallet"></i> DompetKu
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt"></i> Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('budgets.index') }}" class="{{ request()->routeIs('budgets.*') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="display: flex; align-items: center; gap: 1rem;"><i class="fas fa-chart-pie"></i> Budgeting</span>
                            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                                <span style="background: var(--danger-color); color: white; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; font-weight: bold;">{{ Auth::user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Laporan
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <header class="top-header fade-in">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <button class="mobile-toggle" id="mobileToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 style="margin: 0;">@yield('page_title', 'Dashboard')</h2>
                    </div>
                    
                    <div style="display: flex; gap: 1.25rem; align-items: center;">
                        <button id="themeToggle" class="btn btn-outline" style="padding: 0.4rem; border-color: transparent; background: transparent; font-size: 1.2rem; display: flex; align-items: center; justify-content: center;" title="Ubah Tema">
                            <i class="fas fa-moon" id="themeIcon"></i>
                        </button>
                        
                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; font-weight: 500; color: var(--text-main); text-decoration: none;" class="user-name-display" title="Profil Saya">
                            <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--primary-color);"></i>
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: flex; align-items: center;">
                            @csrf
                            <button type="submit" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;" title="Keluar">
                                <i class="fas fa-sign-out-alt"></i> <span class="logout-text">Keluar</span>
                            </button>
                        </form>
                    </div>
                </header>

                @if(session('success'))
                    <div class="alert alert-success fade-in">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger fade-in">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger fade-in">
                        <ul style="margin-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                    @foreach(Auth::user()->unreadNotifications as $notification)
                        <div class="alert fade-in" style="display: flex; justify-content: space-between; align-items: center; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 16px; margin-bottom: 1.5rem; padding: 1.25rem 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 1.25rem;">
                                <div style="background: rgba(239, 68, 68, 0.2); color: var(--danger-color); width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; color: var(--danger-color); font-size: 1.05rem; font-weight: 700; margin-bottom: 0.25rem;">Peringatan Budget!</h4>
                                    <p style="margin: 0; color: var(--text-main); font-size: 0.95rem; opacity: 0.9;">{{ $notification->data['message'] ?? 'Budget tercapai!' }}</p>
                                </div>
                            </div>
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-outline" style="padding: 0.5rem; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; border-color: transparent; background: rgba(239, 68, 68, 0.1); color: var(--danger-color);" title="Tandai sudah dibaca">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endif

                <div class="fade-in">
                    @yield('content')
                </div>
                
                <!-- Footer Informasi Mahasiswa -->
                <footer class="fade-in" style="margin-top: 3rem; background: var(--card-bg); border-radius: 16px; padding: 1.5rem 2rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; border: 1px solid var(--border-color); margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="background: var(--primary-color); color: white; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4 style="margin: 0; color: var(--text-main); font-weight: 700; font-size: 1.1rem;">DompetKu Project</h4>
                    </div>
                    
                    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1.5rem; color: var(--text-muted); font-size: 0.95rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-user-graduate" style="color: var(--primary-color);"></i>
                            <span><strong>Firman Riyadhi</strong> (112522093)</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-laptop-code" style="color: var(--primary-color);"></i>
                            <span>S1 Teknik Informatika</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-university" style="color: var(--primary-color);"></i>
                            <span>Universitas Banten Jaya</span>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    @endguest

    <script>
        // NProgress Loading logic
        NProgress.configure({ showSpinner: false, speed: 400, minimum: 0.1 });
        
        // Start loading immediately when script runs
        NProgress.start();
        
        window.addEventListener('load', function() {
            NProgress.done();
        });

        // Theme toggle logic
        document.addEventListener('DOMContentLoaded', function() {
            
            // Attach loader to all links and forms
            const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"])');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.button === 0 && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                        NProgress.start();
                    }
                });
            });
            
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    NProgress.start();
                });
            });

            const themeToggleBtn = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            
            // Check current theme and set icon
            if (document.documentElement.classList.contains('dark-mode') || document.body.classList.contains('dark-mode')) {
                document.body.classList.add('dark-mode');
                if (themeIcon) {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                }
            }
            
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    document.body.classList.toggle('dark-mode');
                    
                    if (document.body.classList.contains('dark-mode')) {
                        localStorage.setItem('theme', 'dark');
                        themeIcon.classList.remove('fa-moon');
                        themeIcon.classList.add('fa-sun');
                    } else {
                        localStorage.setItem('theme', 'light');
                        themeIcon.classList.remove('fa-sun');
                        themeIcon.classList.add('fa-moon');
                    }
                    
                    // Trigger custom event for Chart.js update if exists
                    window.dispatchEvent(new Event('themeChanged'));
                });
            }
            
            // Mobile Sidebar toggle logic
            const mobileToggle = document.getElementById('mobileToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (mobileToggle && sidebar && overlay) {
                function toggleSidebar() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                }

                mobileToggle.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
