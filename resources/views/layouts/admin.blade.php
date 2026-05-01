<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'Dashboard') | Computer TK Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a73e8;
            --dark: #1a1a2e;
            --secondary: #ff6b00;

            /* Light */
            --bg: #f4f6fb;
            --surface: #ffffff;
            --surface-2: #f8f9fa;
            --text: #212529;
            --text-muted: #6c757d;
            --border: #dee2e6;
            --sidebar-bg: #1a1a2e;
        }
        [data-theme="dark"] {
            --bg: #0f1117;
            --surface: #1a1d27;
            --surface-2: #22263a;
            --text: #e8eaf0;
            --text-muted: #9aa0b4;
            --border: #2e3347;
            --sidebar-bg: #0d0f1a;
        }

        body { font-family:'Segoe UI',sans-serif; background:var(--bg); color:var(--text); transition:background .25s,color .25s; }

        /* Sidebar always dark */
        .admin-sidebar { background:var(--sidebar-bg); min-height:100vh; width:250px; position:fixed; top:0; left:0; z-index:100; overflow-y:auto; }
        .admin-sidebar .brand { padding:1.5rem 1.2rem; border-bottom:1px solid rgba(255,255,255,.1); }
        .admin-sidebar .brand a { color:#fff; text-decoration:none; font-weight:800; font-size:1.1rem; }
        .admin-sidebar .brand span { color:var(--secondary); }
        .admin-sidebar .nav-link { color:rgba(255,255,255,.75); padding:.6rem 1.2rem; border-radius:6px; margin:2px 8px; font-size:.9rem; }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active { background:var(--primary); color:#fff; }
        .admin-sidebar .nav-link i { width:20px; }
        .admin-sidebar .section-label { color:rgba(255,255,255,.4); font-size:.7rem; text-transform:uppercase; letter-spacing:1px; padding:.8rem 1.2rem .3rem; }

        .admin-content { margin-left:250px; min-height:100vh; background:var(--bg); }
        .admin-topbar { background:var(--surface); border-bottom:1px solid var(--border); padding:.75rem 1.5rem; display:flex; align-items:center; justify-content:space-between; }
        .admin-main { padding:1.5rem; }

        .card { background:var(--surface); border-color:var(--border) !important; color:var(--text); }
        .table { color:var(--text); }
        .table-light { background:var(--surface-2) !important; color:var(--text); }
        .table-hover tbody tr:hover { background:var(--surface-2); }
        .table td, .table th { border-color:var(--border); }
        .form-control, .form-select { background:var(--surface-2); border-color:var(--border); color:var(--text); }
        .form-control:focus, .form-select:focus { background:var(--surface); border-color:var(--primary); color:var(--text); box-shadow:none; }
        .dropdown-menu { background:var(--surface); border-color:var(--border); }
        .dropdown-item { color:var(--text); }
        .dropdown-item:hover { background:var(--surface-2); }
        .modal-content { background:var(--surface); color:var(--text); border-color:var(--border); }
        .modal-header, .modal-footer { border-color:var(--border); }
        .input-group-text { background:var(--surface-2); border-color:var(--border); color:var(--text); }
        .text-muted { color:var(--text-muted) !important; }
        hr { border-color:var(--border); }
        .bg-light { background:var(--surface-2) !important; }
        .alert { border-color:var(--border); }

        .stat-card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.08); background:var(--surface); }
        .stat-card .icon { width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }
        .table th { font-weight:600; font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; }

        /* Theme toggle */
        #adminThemeToggle {
            background:none; border:1px solid rgba(255,255,255,.3); color:rgba(255,255,255,.85);
            border-radius:20px; padding:3px 10px; font-size:.8rem; cursor:pointer; transition:all .2s;
        }
        #adminThemeToggle:hover { border-color:#fff; color:#fff; }

        .w-5 { width:30px; }
        @media(max-width:768px){ .admin-sidebar{transform:translateX(-100%);} .admin-content{margin-left:0;} }
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
<div class="admin-sidebar">
    <div class="brand">
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-desktop me-2"></i>TK <span>Admin</span></a>
    </div>
    <nav class="mt-3">
        <div class="section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>
        <div class="section-label">Catalog</div>
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-tags me-2"></i>Categories
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fas fa-box me-2"></i>Products
        </a>
        <div class="section-label">Sales</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag me-2"></i>Orders
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fas fa-users me-2"></i>Customers
        </a>
        <div class="section-label">Store</div>
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="fas fa-external-link-alt me-2"></i>View Store
        </a>
        <div class="section-label">Account</div>
        <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <i class="fas fa-user-circle me-2"></i>My Profile
        </a>
        <form method="POST" action="{{ route('logout') }}" class="px-2 mt-2">
            @csrf
            <button class="nav-link w-100 text-start border-0 bg-transparent text-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </nav>
</div>

<div class="admin-content">
    <div class="admin-topbar">
        <h6 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">
            <button id="adminThemeToggle" onclick="toggleTheme()" title="Toggle dark/light mode">
                <i id="adminThemeIcon" class="fas fa-moon"></i>
                <span id="adminThemeLabel" class="ms-1">Dark</span>
            </button>
            <a href="{{ route('admin.profile') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-2">
                @if(Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}"
                         class="rounded-circle" style="width:28px;height:28px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white"
                         style="width:28px;height:28px;font-size:.75rem;font-weight:700;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                {{ Auth::user()->name }}
            </a>
        </div>
    </div>
    <div class="admin-main">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
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
        const current = document.documentElement.getAttribute('data-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }
    applyTheme(localStorage.getItem('theme') || 'light');
</script>
</body>
</html>
