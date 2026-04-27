<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MedNoble &mdash; {{ config('app.name', 'Cabinet Médical') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --secondary: #333333;
            --accent: #f8f9fa;
            --bg: #ffffff;
            --card-bg: #ffffff;
            --text: #111111;
            --muted: #666666;
            --sidebar-w: 260px;
            --gold: #c5a059;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            display: flex;
            flex-direction: column;
        }
        h1,h2,h3,h4,h5,h6,.navbar-brand {
            font-family: 'Cormorant Garamond', serif;
            color: var(--primary);
            letter-spacing: -0.02em;
        }
        /* ── NAVBAR ── */
        .app-navbar {
            background: #fff;
            border-bottom: 2px solid #000;
            padding: 0.75rem 0;
            z-index: 1030;
        }
        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #000 !important;
            text-transform: uppercase;
        }
        .navbar-brand span { color: var(--gold); }
        /* ── LAYOUT ── */
        .wrapper { display: flex; flex: 1; }
        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid #e8e8e8;
            padding: 1.5rem 1rem;
            min-height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
        }
        .sidebar-section {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #999;
            margin: 1.2rem 0.5rem 0.5rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            color: var(--text);
            text-decoration: none;
            border-radius: 0.6rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .sidebar-link:hover { background: #f3f3f3; color: #000; }
        .sidebar-link.active { background: #000; color: #fff; }
        .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }
        .sidebar-role-badge {
            margin: 0 0.5rem 1.5rem;
            padding: 0.5rem 0.75rem;
            background: #f5f5f5;
            border-radius: 0.6rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #555;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .sidebar-spacer { flex: 1; }
        /* ── MAIN ── */
        .main-content { flex: 1; padding: 2rem; min-width: 0; }
        /* ── CARDS ── */
        .app-card {
            background: var(--card-bg);
            border: 1px solid #f0f0f0;
            border-radius: 1.2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        }
        /* ── BUTTONS ── */
        .btn { border-radius: 50rem; padding: 0.5rem 1.4rem; font-weight: 500; transition: all 0.2s; }
        .btn-dark:hover { background: #333; transform: translateY(-1px); }
        .btn-outline-dark:hover { background: #000; color: #fff; }
        /* ── TABLE ── */
        .table th { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #888; }
        /* ── BADGES ── */
        .badge-status { font-size: 0.78rem; font-weight: 500; padding: 0.4em 0.9em; border-radius: 50rem; }
        /* ── PAGE TITLE ── */
        .page-title { font-size: 1.9rem; font-weight: 700; color: #000; }
        /* ── FOOTER ── */
        .app-footer {
            background: #fff;
            border-top: 1px solid #eee;
            padding: 1rem 0;
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
        }
        /* ── STAT CARD ── */
        .stat-card { border-radius: 1rem; padding: 1.5rem; background: #fff; border: 1px solid #f0f0f0; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        .stat-number { font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 700; line-height: 1; }
        /* ── SEARCH ── */
        #searchResults { position: absolute; top: 100%; left: 0; right: 0; z-index: 999; background: #fff; border: 1px solid #eee; border-radius: 0.75rem; box-shadow: 0 8px 30px rgba(0,0,0,0.1); max-height: 350px; overflow-y: auto; }
        #searchResults .search-item { padding: 0.75rem 1rem; border-bottom: 1px solid #f5f5f5; cursor: pointer; transition: background 0.15s; }
        #searchResults .search-item:hover { background: #f9f9f9; }
        #searchResults .search-item:last-child { border-bottom: none; }
    </style>
</head>
<body>
    {{-- ─── NAVBAR ─── --}}
    <nav class="navbar navbar-expand-lg app-navbar sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-heart-pulse me-1"></i>Med<span>Noble</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto"></ul>
                <div class="d-flex gap-2 align-items-center">
                    {{-- Language Switcher --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-globe me-1"></i>
                            @switch(app()->getLocale())
                                @case('fr') 🇫🇷 FR @break
                                @case('en') 🇬🇧 EN @break
                                @case('es') 🇪🇸 ES @break
                            @endswitch
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="border-radius:1rem; min-width:130px;">
                            <li><a class="dropdown-item" href="{{ url('lang/fr') }}">🇫🇷 Français</a></li>
                            <li><a class="dropdown-item" href="{{ url('lang/en') }}">🇬🇧 English</a></li>
                            <li><a class="dropdown-item" href="{{ url('lang/es') }}">🇪🇸 Español</a></li>
                        </ul>
                    </div>

                    @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:1rem;">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>{{ __('messages.my_profile') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                        <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Connexion</a>
                        <a class="btn btn-dark btn-sm" href="{{ route('register') }}">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        {{-- ─── SIDEBAR (role-based) ─── --}}
        @auth
        <aside class="sidebar d-none d-md-flex flex-column">
            {{-- Role badge --}}
            <div class="sidebar-role-badge mb-3">
                @switch(Auth::user()->role)
                    @case('admin')
                        <i class="bi bi-shield-check text-dark"></i>
                        <span>{{ __('messages.admin') }}</span>
                        @break
                    @case('medecin')
                        <i class="bi bi-heart-pulse text-dark"></i>
                        <span>{{ __('messages.doctor') }}</span>
                        @break
                    @default
                        <i class="bi bi-person text-dark"></i>
                        <span>{{ __('messages.patient') }}</span>
                @endswitch
            </div>

            {{-- Main nav --}}
            <span class="sidebar-section">Menu</span>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> {{ __('messages.dashboard') }}
            </a>

            @if(Auth::user()->role === 'patient')
                <a href="{{ route('appointments.index') }}" class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> {{ __('messages.my_appointments') }}
                </a>
                <a href="{{ route('appointments.create') }}" class="sidebar-link {{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> {{ __('messages.new_appointment') }}
                </a>
            @endif

            @if(Auth::user()->role === 'medecin')
                <a href="{{ route('appointments.index') }}" class="sidebar-link {{ request()->routeIs('appointments.*') && !request()->routeIs('admin.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-week"></i> {{ __('messages.appointments_management') }}
                </a>
            @endif

            @if(Auth::user()->role === 'admin')
                <span class="sidebar-section">Administration</span>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> {{ __('messages.users_management') }}
                </a>
            @endif

            <span class="sidebar-section">{{ __('messages.my_profile') }}</span>
            <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i> {{ __('messages.my_profile') }}
            </a>

            <div class="sidebar-spacer"></div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="sidebar-link w-100 border-0 bg-transparent text-danger" style="cursor:pointer;">
                    <i class="bi bi-box-arrow-right"></i> {{ __('messages.logout') }}
                </button>
            </form>
        </aside>
        @endauth

        {{-- ─── MAIN CONTENT ─── --}}
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <footer class="app-footer">
        <div class="container-fluid">
            &copy; {{ date('Y') }} MediLuxe &mdash; {{ __('messages.security_note') }}
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // CSRF token for all Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    @stack('scripts')
</body>
</html>
