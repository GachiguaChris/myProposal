<!DOCTYPE html>
<html>
<head>
    <title>One Love Proposal App</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #fff;
        }
        .quick-links {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .dropdown-mega {
            position: static !important;
        }
        .dropdown-mega .dropdown-menu {
            width: 100%;
            border-radius: 0;
            margin-top: 0;
            border-top: none;
        }
        .module-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .module-card {
            text-align: center;
            padding: 15px 10px;
            transition: all 0.3s ease;
            border-radius: 8px;
            color: #495057;
        }
        .module-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-5px);
        }
        .module-card.active {
            background-color: #e9ecef;
        }
        .module-title {
            font-size: 0.9rem;
            margin-top: 5px;
            font-weight: 500;
        }
        .favorites-bar {
            background-color: #f8f9fa;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="quick-links">
        <div class="container">
            <div class="row g-2">
                <div class="col-auto">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm {{ request()->routeIs('admin.dashboard') ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </div>
                <div class="col-auto dropdown dropdown-mega">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="modulesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-grid-3x3-gap"></i> Modules
                    </button>
                    <div class="dropdown-menu shadow-lg py-4" aria-labelledby="modulesDropdown">
                        <div class="container">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.proposals.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.proposals.*') ? 'active' : '' }}">
                                            <div class="module-icon text-primary">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div class="module-title">Proposals</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.clients.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                                            <div class="module-icon text-info">
                                                <i class="bi bi-briefcase"></i>
                                            </div>
                                            <div class="module-title">Clients</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.templates.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                                            <div class="module-icon text-success">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                            <div class="module-title">Templates</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.tasks.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                                            <div class="module-icon text-warning">
                                                <i class="bi bi-calendar-check"></i>
                                            </div>
                                            <div class="module-title">Tasks</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.documents.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                                            <div class="module-icon text-danger">
                                                <i class="bi bi-file-earmark"></i>
                                            </div>
                                            <div class="module-title">Documents</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.project-categories.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.project-categories.*') ? 'active' : '' }}">
                                            <div class="module-icon text-secondary">
                                                <i class="bi bi-folder"></i>
                                            </div>
                                            <div class="module-title">Categories</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.reports.summary') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                            <div class="module-icon text-info">
                                                <i class="bi bi-bar-chart"></i>
                                            </div>
                                            <div class="module-title">Reports</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                            <div class="module-icon text-warning">
                                                <i class="bi bi-bell"></i>
                                            </div>
                                            <div class="module-title">Notifications</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                            <div class="module-icon text-success">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="module-title">Users</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.settings') }}" class="text-decoration-none">
                                        <div class="module-card {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                            <div class="module-icon text-secondary">
                                                <i class="bi bi-gear"></i>
                                            </div>
                                            <div class="module-title">Settings</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-auto ms-auto">
                    <a class="btn btn-sm btn-outline-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Favorites Bar - Shows most frequently used modules -->
    <!-- <div class="favorites-bar d-none d-md-block">
        <div class="container">
            <div class="row g-2">
                <div class="col-auto">
                    <small class="text-muted me-2">Quick Access:</small>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.proposals.index') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-file-earmark-text"></i> Proposals
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-briefcase"></i> Clients
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-calendar-check"></i> Tasks
                    </a>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-file-earmark"></i> Documents
                    </a>
                </div>
            </div>
        </div>
    </div>
     -->
    <div class="container-fluid">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>