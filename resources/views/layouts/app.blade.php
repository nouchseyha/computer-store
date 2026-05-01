<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Computer TK Store')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Theme tokens ── */
        :root {
            --primary:       #2563eb;
            --primary-dark:  #1d4ed8;
            --accent:        #f97316;
            --accent-dark:   #ea6c0a;

            --bg:            #f1f5f9;
            --surface:       #ffffff;
            --surface-2:     #f8fafc;
            --surface-3:     #f1f5f9;
            --text:          #0f172a;
            --text-muted:    #64748b;
            --border:        #e2e8f0;
            --shadow-sm:     0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:     0 4px 16px rgba(0,0,0,.08);
            --shadow-lg:     0 10px 32px rgba(0,0,0,.12);
            --radius:        12px;
            --radius-lg:     18px;
            --navbar-bg:     #0f172a;
        }
        [data-theme="dark"] {
            --bg:            #0b0f1a;
            --surface:       #141824;
            --surface-2:     #1c2133;
            --surface-3:     #222840;
            --text:          #f0f4ff;
            --text-muted:    #8892aa;
            --border:        #252d42;
            --shadow-sm:     0 1px 3px rgba(0,0,0,.3);
            --shadow-md:     0 4px 16px rgba(0,0,0,.4);
            --shadow-lg:     0 10px 32px rgba(0,0,0,.5);
            --navbar-bg:     #080c14;
        }

        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
            line-height: 1.6;
            transition: background .2s, color .2s;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Navbar ── */
        .navbar {
            background: var(--navbar-bg) !important;
            padding: .75rem 0;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -.3px;
            color: #fff !important;
        }
        .navbar-brand .brand-accent { color: var(--accent); }
        .navbar .nav-link {
            color: rgba(255,255,255,.75) !important;
            font-weight: 500;
            font-size: .9rem;
            padding: .4rem .75rem !important;
            border-radius: 8px;
            transition: color .15s, background .15s;
        }
        .navbar .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,.08);
        }
        .navbar .nav-link.active { color: #fff !important; }

        /* Search bar */
        .navbar-search .form-control {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: #fff;
            border-radius: 10px 0 0 10px;
            font-size: .85rem;
            padding: .45rem .9rem;
        }
        .navbar-search .form-control::placeholder { color: rgba(255,255,255,.45); }
        .navbar-search .form-control:focus {
            background: rgba(255,255,255,.15);
            border-color: rgba(255,255,255,.3);
            box-shadow: none;
            color: #fff;
        }
        .navbar-search .btn {
            border-radius: 0 10px 10px 0;
            background: var(--accent);
            border: none;
            color: #fff;
            padding: .45rem .9rem;
        }
        .navbar-search .btn:hover { background: var(--accent-dark); }

        /* Theme toggle */
        #themeToggle {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            color: rgba(255,255,255,.8);
            border-radius: 20px;
            padding: 5px 12px;
            font-size: .78rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            white-space: nowrap;
        }
        #themeToggle:hover { background: rgba(255,255,255,.15); color: #fff; }

        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -5px; right: -7px;
            background: var(--accent);
            color: #fff;
            border-radius: 50%;
            width: 17px; height: 17px;
            font-size: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        /* User avatar pill */
        .user-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,.2);
        }
        .user-initial {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid rgba(255,255,255,.2);
        }

        /* Dropdown */
        .dropdown-menu {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: .4rem;
            min-width: 200px;
        }
        .dropdown-item {
            color: var(--text);
            border-radius: 8px;
            padding: .5rem .85rem;
            font-size: .88rem;
            font-weight: 500;
            transition: background .12s;
        }
        .dropdown-item:hover { background: var(--surface-2); color: var(--text); }
        .dropdown-divider { border-color: var(--border); margin: .3rem 0; }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-sm);
            color: var(--text);
        }

        /* ── Tables ── */
        .table { color: var(--text); }
        .table-light { background: var(--surface-2) !important; color: var(--text); }
        .table-hover tbody tr:hover { background: var(--surface-2); }
        .table td, .table th { border-color: var(--border); }

        /* ── Forms ── */
        .form-control, .form-select {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 10px;
            font-size: .9rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            background: var(--surface);
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .input-group-text {
            background: var(--surface-2);
            border-color: var(--border);
            color: var(--text-muted);
        }
        .form-label { font-weight: 600; font-size: .85rem; color: var(--text); }

        /* ── Buttons ── */
        .btn { border-radius: 10px; font-weight: 600; font-size: .88rem; transition: all .15s; }
        .btn-primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,.3); }
        .btn-warning { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-warning:hover { background: var(--accent-dark); border-color: var(--accent-dark); color: #fff; transform: translateY(-1px); }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }
        .btn-outline-secondary { border-color: var(--border); color: var(--text-muted); }
        .btn-outline-secondary:hover { background: var(--surface-2); color: var(--text); border-color: var(--border); }

        /* ── Alerts ── */
        .alert { border-radius: var(--radius); border: 1px solid var(--border); }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .alert-danger  { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        [data-theme="dark"] .alert-success { background: #052e16; border-color: #166534; color: #86efac; }
        [data-theme="dark"] .alert-danger  { background: #2d0a0a; border-color: #991b1b; color: #fca5a5; }

        /* ── Badges ── */
        .badge { border-radius: 6px; font-weight: 600; }

        /* ── Misc ── */
        .bg-light { background: var(--surface-2) !important; }
        .text-muted { color: var(--text-muted) !important; }
        hr { border-color: var(--border); opacity: 1; }

        /* ── Price styles (used everywhere) ── */
        .price-regular { font-size: 1.05rem; font-weight: 700; color: var(--primary); }
        .price-sale    { font-size: 1.05rem; font-weight: 700; color: var(--accent); }
        .price-old     { font-size: .82rem; color: var(--text-muted); text-decoration: line-through; margin-right: 4px; }

        /* ── Hero ── */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f2d5e 100%);
            color: #fff;
            padding: 90px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 70% 50%, rgba(37,99,235,.25) 0%, transparent 60%);
        }
        .hero-section h1 { font-size: 3rem; font-weight: 800; line-height: 1.15; letter-spacing: -.5px; }
        @media(max-width:768px) { .hero-section h1 { font-size: 2rem; } }

        /* ── Section title ── */
        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -.3px;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: .5rem;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 40px; height: 3px;
            background: var(--accent);
            border-radius: 2px;
        }

        /* ── Product cards (home) ── */
        .product-card {
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-sm);
            transition: transform .2s, box-shadow .2s;
            overflow: hidden;
            background: var(--surface);
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .product-card img { height: 200px; object-fit: contain; background: var(--surface-2); padding: 12px; }

        /* ── Category cards ── */
        .category-card {
            border-radius: var(--radius) !important;
            border: 1px solid var(--border) !important;
            box-shadow: var(--shadow-sm);
            transition: transform .2s, box-shadow .2s;
            background: var(--surface);
        }
        .category-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        /* ── Footer ── */
        .footer {
            background: #0f172a;
            color: rgba(255,255,255,.65);
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .footer a { color: rgba(255,255,255,.5); text-decoration: none; transition: color .15s; }
        .footer a:hover { color: var(--accent); }
        .footer-brand { font-size: 1.1rem; font-weight: 800; color: #fff; }

        /* ── Pagination ── */
        .pagination .page-link {
            background: var(--surface);
            border-color: var(--border);
            color: var(--text);
            border-radius: 8px !important;
            margin: 0 2px;
            font-size: .85rem;
        }
        .pagination .page-link:hover { background: var(--surface-2); }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
    </style>

    <script>
        (function() {
            const t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    @stack('styles')
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div style="width:32px;height:32px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-desktop" style="font-size:.85rem;color:#fff;"></i>
            </div>
            Computer <span class="brand-accent">TK</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                </li>
            </ul>

            {{-- Search --}}
            <form class="d-flex navbar-search me-3" action="{{ route('shop.index') }}" method="GET">
                <input class="form-control" type="search" name="search"
                       placeholder="Search products…" value="{{ request('search') }}"
                       style="width:220px;">
                <button class="btn" type="submit"><i class="fas fa-search" style="font-size:.8rem;"></i></button>
            </form>

            <ul class="navbar-nav align-items-center gap-2">
                {{-- Theme toggle --}}
                <li class="nav-item">
                    <button id="themeToggle" onclick="toggleTheme()">
                        <i id="themeIcon" class="fas fa-moon me-1"></i>
                        <span id="themeLabel">Dark</span>
                    </button>
                </li>

                {{-- Cart --}}
                <li class="nav-item">
                    <livewire:cart-count />
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm fw-semibold px-3"
                           href="{{ route('register') }}"
                           style="background:var(--accent);color:#fff;border-radius:20px;">
                            Register
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 pe-0"
                           href="#" data-bs-toggle="dropdown">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset(Auth::user()->avatar) }}" class="user-avatar" alt="">
                            @else
                                <div class="user-initial">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span style="font-size:.88rem;">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="px-3 py-2 mb-1">
                                    <div class="fw-semibold" style="font-size:.88rem;">{{ Auth::user()->name }}</div>
                                    <div class="text-muted" style="font-size:.78rem;">{{ Auth::user()->email }}</div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">
                                <i class="fas fa-box me-2 text-primary" style="width:16px;"></i>My Orders
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle me-2 text-primary" style="width:16px;"></i>My Profile
                            </a></li>
                            @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-warning" style="width:16px;"></i>Admin Panel
                                </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2" style="width:16px;"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

@yield('content')

{{-- ── Footer ── --}}
<footer class="footer mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row g-4 pb-4" style="border-bottom:1px solid rgba(255,255,255,.08);">
            <div class="col-md-4">
                <div class="footer-brand mb-2">
                    <i class="fas fa-desktop me-2" style="color:var(--accent);"></i>Computer TK Store
                </div>
                <p style="font-size:.88rem;line-height:1.7;">
                    Your trusted source for computers, laptops, and tech accessories. Quality products at competitive prices.
                </p>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold text-white mb-3" style="font-size:.85rem;">Shop</div>
                <ul class="list-unstyled" style="font-size:.85rem;">
                    <li class="mb-1"><a href="{{ route('shop.index') }}">All Products</a></li>
                    <li class="mb-1"><a href="{{ route('shop.index', ['category' => 'laptops']) }}">Laptops</a></li>
                    <li class="mb-1"><a href="{{ route('shop.index', ['category' => 'desktops']) }}">Desktops</a></li>
                    <li class="mb-1"><a href="{{ route('shop.index', ['category' => 'accessories']) }}">Accessories</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold text-white mb-3" style="font-size:.85rem;">Account</div>
                <ul class="list-unstyled" style="font-size:.85rem;">
                    @guest
                        <li class="mb-1"><a href="{{ route('login') }}">Login</a></li>
                        <li class="mb-1"><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="mb-1"><a href="{{ route('orders.index') }}">My Orders</a></li>
                        <li class="mb-1"><a href="{{ route('profile.edit') }}">Profile</a></li>
                    @endguest
                </ul>
            </div>
            <div class="col-md-4">
                <div class="fw-semibold text-white mb-3" style="font-size:.85rem;">Contact</div>
                <ul class="list-unstyled" style="font-size:.85rem;">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2" style="color:var(--accent);width:14px;"></i>123 Tech Street, Phnom Penh</li>
                    <li class="mb-2"><i class="fas fa-phone me-2" style="color:var(--accent);width:14px;"></i>+855 888-110-427</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2" style="color:var(--accent);width:14px;"></i>info@computertkstore.com</li>
                </ul>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-4 flex-wrap gap-2">
            <small style="font-size:.8rem;">&copy; {{ date('Y') }} Computer TK Store. All rights reserved.</small>
            <div class="d-flex gap-3">
                <a href="#" style="color:rgba(255,255,255,.4);font-size:1rem;"><i class="fab fa-facebook"></i></a>
                <a href="#" style="color:rgba(255,255,255,.4);font-size:1rem;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color:rgba(255,255,255,.4);font-size:1rem;"><i class="fab fa-telegram"></i></a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
@livewireScripts
@stack('scripts')

<script>
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        const icon  = document.getElementById('themeIcon');
        const label = document.getElementById('themeLabel');
        if (!icon) return;
        if (theme === 'dark') {
            icon.className    = 'fas fa-sun me-1';
            label.textContent = 'Light';
        } else {
            icon.className    = 'fas fa-moon me-1';
            label.textContent = 'Dark';
        }
    }
    function toggleTheme() {
        applyTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    }
    applyTheme(localStorage.getItem('theme') || 'light');
</script>
</body>
</html>
