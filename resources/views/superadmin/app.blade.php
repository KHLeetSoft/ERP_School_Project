<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Super Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-brand i {
            font-size: 2rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
        }

        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .nav-link .badge {
            margin-left: auto;
            font-size: 0.65rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            color: #6b7280;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: auto;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-dropdown {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: none;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-btn:hover {
            background: rgba(99, 102, 241, 0.1);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            font-size: 0.875rem;
        }

        .user-role {
            color: #6b7280;
            font-size: 0.75rem;
            margin: 0;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 2px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="main-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-brand">
                    <i class="fas fa-crown"></i>
                    <span class="brand-text">Super Admin</span>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- School Management -->
                <div class="nav-section">
                    <div class="nav-section-title">School Management</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.schools.index') }}" class="nav-link {{ request()->routeIs('superadmin.schools.*') ? 'active' : '' }}">
                            <i class="fas fa-school"></i>
                            <span>Schools</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.admins.index') }}" class="nav-link {{ request()->routeIs('superadmin.admins.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Admins</span>
                        </a>
                    </div>
                </div>

                <!-- Plans & Subscriptions -->
                <div class="nav-section">
                    <div class="nav-section-title">Plans & Subscriptions</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.productplans.index') }}" class="nav-link {{ request()->routeIs('superadmin.productplans.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Plans</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.purchases.index') }}" class="nav-link {{ request()->routeIs('superadmin.purchases.*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Purchases</span>
                        </a>
                    </div>
                </div>

                <!-- Payment Module -->
                <div class="nav-section">
                    <div class="nav-section-title">Payments</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.payment.school-qr-codes.index') }}" class="nav-link {{ request()->routeIs('superadmin.payment.school-qr-codes.*') ? 'active' : '' }}">
                            <i class="fas fa-qrcode"></i>
                            <span>School QR Codes</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.payment.gateways.index') }}" class="nav-link {{ request()->routeIs('superadmin.payment.gateways.*') ? 'active' : '' }}">
                            <i class="fas fa-credit-card"></i>
                            <span>Payment Gateways</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.payment.plans.index') }}" class="nav-link {{ request()->routeIs('superadmin.payment.plans.*') ? 'active' : '' }}">
                            <i class="fas fa-list"></i>
                            <span>Payment Plans</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.payment.reports.index') }}" class="nav-link {{ request()->routeIs('superadmin.payment.reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                </div>

                <!-- Monitoring & Analytics -->
                <div class="nav-section">
                    <div class="nav-section-title">Monitoring</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.monitoring.index') }}" class="nav-link {{ request()->routeIs('superadmin.monitoring.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                </div>

                <!-- AI & Automation -->
                <div class="nav-section">
                    <div class="nav-section-title">AI & Automation</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.ai-automation.index') }}" class="nav-link {{ request()->routeIs('superadmin.ai-automation.*') ? 'active' : '' }}">
                            <i class="fas fa-robot"></i>
                            <span>AI Tools</span>
                        </a>
                    </div>
                </div>

                <!-- Support & Communication -->
                <div class="nav-section">
                    <div class="nav-section-title">Support</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.support.index') }}" class="nav-link {{ request()->routeIs('superadmin.support.*') ? 'active' : '' }}">
                            <i class="fas fa-headset"></i>
                            <span>Support Center</span>
                        </a>
                    </div>
                </div>

                <!-- Developer Tools -->
                <div class="nav-section">
                    <div class="nav-section-title">Developer</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.developer-tools.index') }}" class="nav-link {{ request()->routeIs('superadmin.developer-tools.*') ? 'active' : '' }}">
                            <i class="fas fa-code"></i>
                            <span>Dev Tools</span>
                        </a>
                    </div>
                </div>

                <!-- Role & Permission Management -->
                <div class="nav-section">
                    <div class="nav-section-title">Security</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.roles.index') }}" class="nav-link {{ request()->routeIs('superadmin.roles.*') ? 'active' : '' }}">
                            <i class="fas fa-shield-alt"></i>
                            <span>Roles</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.permissions.index') }}" class="nav-link {{ request()->routeIs('superadmin.permissions.*') ? 'active' : '' }}">
                            <i class="fas fa-key"></i>
                            <span>Permissions</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.user-roles.index') }}" class="nav-link {{ request()->routeIs('superadmin.user-roles.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i>
                            <span>User Roles</span>
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <div class="nav-item">
                        <a href="{{ route('superadmin.settings.index') }}" class="nav-link {{ request()->routeIs('superadmin.settings.*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>System Settings</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                
                <div class="header-right">
                    <button class="notification-btn" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <div class="user-dropdown">
                        <button class="user-btn" id="userDropdownBtn">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="user-info">
                                <p class="user-name">{{ Auth::user()->name }}</p>
                                <p class="user-role">Super Admin</p>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div class="dropdown-menu dropdown-menu-end" id="userDropdown">
                            <a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area fade-in-up">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Sidebar toggle
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');
            });

            // User dropdown
            $('#userDropdownBtn').on('click', function(e) {
                e.stopPropagation();
                $('#userDropdown').toggle();
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.user-dropdown').length) {
                    $('#userDropdown').hide();
                }
            });

            // Notification button
            $('#notificationBtn').on('click', function() {
                // Implement notification functionality
                alert('Notifications feature coming soon!');
            });

            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                $('#sidebarToggle').on('click', function() {
                    $('#sidebar').toggleClass('show');
                });
            }

            // Auto-hide alerts
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        });

        // Responsive sidebar
        $(window).on('resize', function() {
            if (window.innerWidth <= 768) {
                $('#sidebar').removeClass('collapsed');
                $('#mainContent').removeClass('expanded');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>