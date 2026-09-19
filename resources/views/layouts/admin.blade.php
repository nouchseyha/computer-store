<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') | Computer TK Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Same token system as app.blade.php ── */
        :root {
            --primary:      #2563eb;
            --primary-dark: #1d4ed8;
            --accent:       #f97316;
            --accent-dark:  #ea6c0a;

            --bg:           #f1f5f9;
            --surface:      #ffffff;
            --surface-2:    #f8fafc;
            --surface-3:    #f1f5f9;
            --text:         #0f172a;
            --text-muted:   #64748b;
            --border:       #e2e8f0;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:    0 4px 16px rgba(0,0,0,.08);
            --radius:       12px;

            --sidebar-bg:   #0f172a;
            --sidebar-hover:#1e293b;
        }
        [data-theme="dark"] {
            --bg:           #0b0f1a;
            --surface:      #141824;
            --surface-2:    #1c2133;
            --surface-3:    #222840;
            --text:         #f0f4ff;
            --text-muted:   #8892aa;
            --border:       #252d42;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.3);
            --shadow-md:    0 4px 16px rgba(0,0,0,.4);

            --sidebar-bg:   #080c14;
            --sidebar-hover:#111827;
        }

        /* ── Base ── */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            transition: background .2s, color .2s;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            background: var(--sidebar-bg);
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
            border-right: 1px solid rgba(255,255,255,.05);
            display: flex;
            flex-direction: column;
        }
        .admin-sidebar .brand {
            padding: 1.25rem 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .admin-sidebar .brand-icon {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .admin-sidebar .brand-text {
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: -.2px;
        }
        .admin-sidebar .brand-text span { color: var(--accent); }

        .admin-sidebar .section-label {
            color: rgba(255,255,255,.3);
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 1rem 1.2rem .3rem;
            font-weight: 600;
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,.6);
            padding: .5rem 1rem;
            border-radius: 8px;
            margin: 1px 8px;
            font-size: .85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .6rem;
            transition: background .15s, color .15s;
            text-decoration: none;
        }
        .admin-sidebar .nav-link i { width: 16px; text-align: center; font-size: .85rem; }
        .admin-sidebar .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }
        .admin-sidebar .nav-link.active {
            background: var(--primary);
            color: #fff;
            font-weight: 600;
        }
        .admin-sidebar .nav-link.text-danger { color: #f87171 !important; }
        .admin-sidebar .nav-link.text-danger:hover { background: rgba(248,113,113,.1); }

        /* ── Main content ── */
        .admin-content {
            margin-left: 240px;
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .admin-topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: .7rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-sm);
        }
        .admin-topbar h6 { color: var(--text); font-weight: 700; font-size: .9rem; }

        .admin-main { padding: 1.5rem; flex: 1; }

        /* ── Theme toggle ── */
        #adminThemeToggle {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        #adminThemeToggle:hover { border-color: var(--primary); color: var(--primary); }

        /* ── Cards ── */
        .card {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-sm);
            color: var(--text);
        }
        .card-header { background: var(--surface) !important; border-color: var(--border) !important; }

        /* ── Tables ── */
        .table { color: var(--text); --bs-table-bg: transparent; }
        .table-light {
            --bs-table-bg: var(--surface-2) !important;
            background: var(--surface-2) !important;
            color: var(--text) !important;
        }
        .table-hover > tbody > tr:hover > * { background: var(--surface-2); color: var(--text); }
        .table > :not(caption) > * > * { border-color: var(--border); }
        .table th {
            font-weight: 600;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
        }

        /* ── Forms ── */
        .form-control, .form-select {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 10px;
            font-size: .88rem;
        }
        .form-control:focus, .form-select:focus {
            background: var(--surface);
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-label { font-weight: 600; font-size: .82rem; color: var(--text); margin-bottom: .35rem; }
        .input-group-text {
            background: var(--surface-2);
            border-color: var(--border);
            color: var(--text-muted);
        }
        .form-check-input { background-color: var(--surface-2); border-color: var(--border); }
        .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }

        /* ── Buttons ── */
        .btn { border-radius: 10px; font-weight: 600; font-size: .85rem; }
        .btn-primary { background: var(--primary); border-color: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { border-color: var(--primary); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }
        .btn-outline-secondary { border-color: var(--border); color: var(--text-muted); background: transparent; }
        .btn-outline-secondary:hover { background: var(--surface-2); color: var(--text); border-color: var(--border); }
        .btn-outline-danger { border-color: #ef4444; color: #ef4444; background: transparent; }
        .btn-outline-danger:hover { background: #ef4444; color: #fff; }
        .btn-danger { background: #ef4444; border-color: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; border-color: #dc2626; }

        /* ── Modals ── */
        .modal-content {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 16px;
        }
        .modal-header { border-color: var(--border); }
        .modal-footer { border-color: var(--border); }
        .modal-backdrop { background: rgba(0,0,0,.6); }

        /* ── Dropdowns ── */
        .dropdown-menu {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }
        .dropdown-item { color: var(--text); font-size: .88rem; border-radius: 8px; }
        .dropdown-item:hover { background: var(--surface-2); color: var(--text); }
        .dropdown-divider { border-color: var(--border); }

        /* ── Alerts ── */
        .alert { border-radius: var(--radius); border: 1px solid var(--border); font-size: .88rem; }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .alert-danger  { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        [data-theme="dark"] .alert-success { background: #052e16; border-color: #166534; color: #86efac; }
        [data-theme="dark"] .alert-danger  { background: #2d0a0a; border-color: #991b1b; color: #fca5a5; }

        /* ── Badges ── */
        .badge { border-radius: 6px; font-weight: 600; font-size: .72rem; }

        /* ── Misc ── */
        .bg-light { background: var(--surface-2) !important; }
        .text-muted { color: var(--text-muted) !important; }
        hr { border-color: var(--border); opacity: 1; }
        .border { border-color: var(--border) !important; }

        /* ── Pagination ── */
        .pagination .page-link {
            background: var(--surface);
            border-color: var(--border);
            color: var(--text);
            border-radius: 8px !important;
            margin: 0 2px;
            font-size: .82rem;
        }
        .pagination .page-link:hover { background: var(--surface-2); }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }

        /* ── Stat cards ── */
        .stat-card { border-radius: 14px !important; }
        .stat-card .icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* ── Online pulse animation ── */
        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
            70%  { box-shadow: 0 0 0 5px rgba(34,197,94,0); }
            100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        }

        @media(max-width:768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-content { margin-left: 0; }
        }
    </style>

    {{-- Apply theme before paint to avoid flash --}}
    <script>
        (function() {
            const t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<div class="admin-sidebar">
    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-desktop" style="color:#fff;font-size:.8rem;"></i>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="brand-text">
            TK <span>Admin</span>
        </a>
    </div>

    <nav class="mt-2 flex-grow-1">
        <div class="section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="section-label">Catalog</div>
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Categories
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box"></i> Products
        </a>

        <div class="section-label">Sales</div>
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i> Orders
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Customers
        </a>

        <div class="section-label">Account</div>
        <a href="{{ route('admin.profile') }}"
           class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Store
        </a>
    </nav>

    <div class="px-2 pb-3 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- ── Main ── --}}
<div class="admin-content">
    <div class="admin-topbar">
        <h6 class="mb-0">@yield('title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">

            {{-- Theme toggle --}}
            <button id="adminThemeToggle" onclick="toggleTheme()">
                <i id="adminThemeIcon" class="fas fa-moon"></i>
                <span id="adminThemeLabel">Dark</span>
            </button>

            {{-- Admin user --}}
            <a href="{{ route('admin.profile') }}"
               class="text-decoration-none d-flex align-items-center gap-2"
               style="color:var(--text-muted);font-size:.85rem;">
                <div class="position-relative">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset(Auth::user()->avatar) }}"
                             class="rounded-circle"
                             style="width:30px;height:30px;object-fit:cover;border:2px solid var(--border);">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:30px;height:30px;background:var(--primary);font-size:.72rem;flex-shrink:0;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    {{-- Online dot --}}
                    <span style="position:absolute;bottom:0;right:0;width:9px;height:9px;
                                 background:#22c55e;border-radius:50%;border:2px solid var(--surface);
                                 animation:pulse 2s infinite;"></span>
                </div>
                <span class="fw-semibold" style="color:var(--text);">{{ Auth::user()->name }}</span>
            </a>
        </div>
    </div>

    <div class="admin-main">
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

        @yield('content')
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
@livewireScripts
@stack('scripts')

<script>
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        const icon  = document.getElementById('adminThemeIcon');
        const label = document.getElementById('adminThemeLabel');
        if (!icon) return;
        if (theme === 'dark') {
            icon.className    = 'fas fa-sun';
            label.textContent = 'Light';
        } else {
            icon.className    = 'fas fa-moon';
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
