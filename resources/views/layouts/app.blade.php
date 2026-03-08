<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FlexPOS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            z-index: 100;
            transition: all 0.3s;
        }
        
        .sidebar .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-item .nav-link:hover,
        .sidebar .nav-item .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 1.2rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            background-color: #f8f9fc;
        }
        
        /* Top Bar */
        .topbar {
            height: 70px;
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.15);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* Card Styling */
        .card { border: none; border-radius: 0.35rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58,59,69,.1); }
        .card-header { background-color: #fff; border-bottom: 1px solid #e3e6f0; font-weight: 700; color: #4e73df; }
        
        /* Button Polish */
        .btn-primary { background-color: #4e73df; border-color: #4e73df; }
        .btn-primary:hover { background-color: #2e59d9; border-color: #2e59d9; }
        .btn-success { background-color: #1cc88a; border-color: #1cc88a; }
        .btn-success:hover { background-color: #17a673; border-color: #17a673; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="{{ url('/') }}" class="sidebar-brand">
            <i class="fas fa-cash-register me-2"></i> FlexPOS
        </a>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <i class="fas fa-fw fa-shopping-cart me-2"></i>
                    <span>POS</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="fas fa-fw fa-boxes me-2"></i>
                    <span>Inventory</span>
                </a>
            </li>
            
            <li class="nav-item mt-4">
                <a class="nav-link text-light opacity-50" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-gray-800 fw-bold">{{ View::shared('title', 'Dashboard') }}</h4>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="me-2 text-gray-600 small">{{ Auth::user()->name }}</span>
                    <i class="fas fa-user-circle fa-lg text-gray-400"></i>
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
    
</body>
</html>