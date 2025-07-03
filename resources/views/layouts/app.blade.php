<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'One Love ProposalApp') }}</title>

    <!-- Bootstrap Icons -->
    <link 
      rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" 
    />

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- Vite compiled assets (Ensure Bootstrap JS is included here!) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Livewire Styles & Scripts -->
    @livewireStyles
    @livewireScripts
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button 
                  class="navbar-toggler" 
                  type="button" 
                  data-bs-toggle="collapse" 
                  data-bs-target="#navbarSupportedContent" 
                  aria-controls="navbarSupportedContent" 
                  aria-expanded="false" 
                  aria-label="{{ __('Toggle navigation') }}"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="bi bi-house"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('proposals.index') ? 'active' : '' }}" href="{{ route('proposals.index') }}">
                                    <i class="bi bi-file-earmark-text"></i> My Proposals
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('proposals.create') ? 'active' : '' }}" href="{{ route('proposals.create') }}">
                                    <i class="bi bi-plus-circle"></i> New Proposal
                                </a>
                            </li>
                        @endauth
                    </ul>
@auth
<li class="nav-item dropdown">
    <a id="notificationDropdown" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bi bi-bell-fill"></i>
        @php $unreadCount = auth()->user()->notifications()->where('read', false)->count(); @endphp
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="notificationDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Notifications</h6>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-decoration-none">Mark all as read</button>
                </form>
            @endif
        </div>
        <hr>
        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
            <div class="dropdown-item">
                <strong>{{ $notification->title }}</strong><br>
                <small>{{ \Illuminate\Support\Str::limit($notification->message, 50) }}</small><br>
                <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                @if(!$notification->read)
                    <span class="badge bg-primary float-end">New</span>
                @endif
            </div>
            <div class="dropdown-divider"></div>
        @empty
            <div class="dropdown-item text-muted">No notifications</div>
        @endforelse
        <a href="{{ route('notifications.index') }}" class="dropdown-item text-center">View All</a>
    </div>
</li>
@endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            @if(Auth::user()->is_admin)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a 
                                  id="navbarDropdown" 
                                  class="nav-link dropdown-toggle" 
                                  href="#" 
                                  role="button" 
                                  data-bs-toggle="dropdown" 
                                  aria-haspopup="true" 
                                  aria-expanded="false" 
                                  v-pre
                                >
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('proposals.export') }}">
                                            <i class="bi bi-download"></i> Export Proposals
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a 
                                          class="dropdown-item" 
                                          href="{{ route('logout') }}"
                                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        >
                                            <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                        </a>
                                    </li>
                                </ul>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')

            @if(session('success'))
                <div class="alert alert-success mt-3" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger mt-3" role="alert">
                    {{ session('error') }}
                </div>
            @endif
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>