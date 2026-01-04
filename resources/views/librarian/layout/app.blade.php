<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Portal | @yield('title', 'Dashboard')</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #8b5cf6;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            min-height: 100vh; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .librarian-sidebar { 
            height: 100vh; 
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff; 
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            z-index: 1040;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .librarian-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
        }

        .librarian-sidebar.collapsed .sidebar-title {
            opacity: 0;
            transform: translateX(-20px);
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .librarian-sidebar.collapsed .nav-section-title {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-weight: 500;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .nav-text {
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .librarian-sidebar.collapsed .nav-text {
            opacity: 0;
            transform: translateX(-20px);
        }

        .librarian-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--light-color);
        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header */
        .main-header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sidebar-toggle:hover {
            background: var(--light-color);
            color: var(--primary-color);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-stats {
            display: flex;
            gap: 1rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--light-color);
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .stat-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .modern-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-success-modern {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }

        /* Notifications */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .librarian-sidebar {
                transform: translateX(-100%);
            }

            .librarian-sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header-stats {
                display: none;
            }

            .content-wrapper {
                padding: 1rem;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-in-left {
            animation: slideInLeft 0.5s ease-out;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="librarian-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="sidebar-title">Librarian Portal</div>
        </div>

        <nav class="sidebar-nav">
            <!-- Main Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <div class="nav-item">
                    <a href="{{ route('librarian.dashboard') }}" class="nav-link {{ request()->routeIs('librarian.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.profile') }}" class="nav-link {{ request()->routeIs('librarian.profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <span class="nav-text">My Profile</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.notifications.index') }}" class="nav-link {{ request()->routeIs('librarian.notifications*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <span class="nav-text">Notifications</span>
                        <span class="badge bg-danger ms-auto">3</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.messages.index') }}" class="nav-link {{ request()->routeIs('librarian.messages*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <span class="nav-text">Messages / Chat</span>
                        <span class="badge bg-primary ms-auto">5</span>
                    </a>
                </div>
            </div>

            <!-- Library Management -->
            <div class="nav-section">
                <div class="nav-section-title">Library Management</div>
                <div class="nav-item">
                    <a href="{{ route('librarian.books.index') }}" class="nav-link {{ request()->routeIs('librarian.books*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <span class="nav-text">Books</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.books.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <span class="nav-text">Add New Book</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.book-categories.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <span class="nav-text">Book Categories</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-tag"></i>
                        <span class="nav-text">Tags / Keywords</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-boxes"></i>
                        <span class="nav-text">Stock Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <span class="nav-text">Book Details & Editions</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <span class="nav-text">Book Analytics</span>
                    </a>
                </div>
            </div>

            <!-- Members Management -->
            <div class="nav-section">
                <div class="nav-section-title">Members</div>
                <div class="nav-item">
                    <a href="{{ route('librarian.users') }}" class="nav-link {{ request()->routeIs('librarian.users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <span class="nav-text">Members</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <span class="nav-text">Add Member</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-list"></i>
                        <span class="nav-text">Member List</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-id-card"></i>
                        <span class="nav-text">Membership Types</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-clock"></i>
                        <span class="nav-text">Membership Expiry Alerts</span>
                        <span class="badge bg-warning ms-auto">2</span>
                    </a>
                </div>
            </div>

            <!-- Borrow & Return -->
            <div class="nav-section">
                <div class="nav-section-title">Borrow & Return</div>
                <div class="nav-item">
                    <a href="{{ route('librarian.book-issues.create') }}" class="nav-link">
                        <i class="nav-icon fas fa-hand-holding"></i>
                        <span class="nav-text">Issue Book</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.book-issues.index') }}" class="nav-link {{ request()->routeIs('librarian.book-issues*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-undo"></i>
                        <span class="nav-text">Return Book</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.book-issues.overdue') }}" class="nav-link">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>
                        <span class="nav-text">Overdue Books</span>
                        <span class="badge bg-danger ms-auto">5</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <span class="nav-text">Penalty / Fine Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-sync"></i>
                        <span class="nav-text">Renewal Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-history"></i>
                        <span class="nav-text">Borrowing History</span>
                    </a>
                </div>
            </div>

            <!-- Reservations -->
            <div class="nav-section">
                <div class="nav-section-title">Reservations</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-calendar-plus"></i>
                        <span class="nav-text">New Reservation</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <span class="nav-text">All Reservations</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-bell"></i>
                        <span class="nav-text">Reservation Reminders</span>
                    </a>
                </div>
            </div>

            <!-- Finance & Invoices -->
            <div class="nav-section">
                <div class="nav-section-title">Finance & Invoices</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <span class="nav-text">Payment Records</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <span class="nav-text">Book Invoices</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <span class="nav-text">Fines & Dues</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <span class="nav-text">Income & Expense Reports</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-coins"></i>
                        <span class="nav-text">Subscription / Membership Fees</span>
                    </a>
                </div>
            </div>

            <!-- Digital Resources -->
            <div class="nav-section">
                <div class="nav-section-title">Digital Resources</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-globe"></i>
                        <span class="nav-text">e-Books</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-headphones"></i>
                        <span class="nav-text">Audio Books</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-video"></i>
                        <span class="nav-text">Video Lectures</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <span class="nav-text">Journals & Research Papers</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-magazine"></i>
                        <span class="nav-text">Magazines & Newsletters</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-archive"></i>
                        <span class="nav-text">Archive Management</span>
                    </a>
                </div>
            </div>

            <!-- Reports & Analytics -->
            <div class="nav-section">
                <div class="nav-section-title">Reports & Analytics</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <span class="nav-text">Usage Statistics</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-calculator"></i>
                        <span class="nav-text">Fine Collection Report</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <span class="nav-text">Borrowing Trends</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-clock"></i>
                        <span class="nav-text">Daily/Monthly Activity Log</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <span class="nav-text">Inventory Report</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-thumbtack"></i>
                        <span class="nav-text">Reservation Trends</span>
                    </a>
                </div>
            </div>

            <!-- Advanced Features -->
            <div class="nav-section">
                <div class="nav-section-title">Advanced Features</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-qrcode"></i>
                        <span class="nav-text">QR/Barcode Scanning</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-broadcast-tower"></i>
                        <span class="nav-text">RFID Integration</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-search"></i>
                        <span class="nav-text">Smart Search with Filters</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-mobile-alt"></i>
                        <span class="nav-text">Mobile App Sync</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-cloud"></i>
                        <span class="nav-text">Cloud Backup</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-bell"></i>
                        <span class="nav-text">Notification System</span>
                    </a>
                </div>
            </div>

            <!-- Communication -->
            <div class="nav-section">
                <div class="nav-section-title">Communication</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-envelope"></i>
                        <span class="nav-text">Send Email / SMS to Members</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-comments"></i>
                        <span class="nav-text">Internal Chat</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <span class="nav-text">Announcements / Notices</span>
                    </a>
                </div>
            </div>

            <!-- Settings -->
            <div class="nav-section">
                <div class="nav-section-title">Settings</div>
                <div class="nav-item">
                    <a href="{{ route('librarian.settings') }}" class="nav-link {{ request()->routeIs('librarian.settings*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <span class="nav-text">General Settings</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-palette"></i>
                        <span class="nav-text">Theme & Layout Settings</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('librarian.change-password') }}" class="nav-link {{ request()->routeIs('librarian.change-password*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key"></i>
                        <span class="nav-text">Change Password</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="alert('Coming Soon!')">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <span class="nav-text">Manage Roles & Permissions</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Header -->
        <header class="main-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="header-right">
                <!-- Quick Stats -->
                <div class="header-stats d-none d-lg-flex">
                    <div class="stat-item">
                        <div class="stat-icon primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <span>Books: 0</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon success">
                            <i class="fas fa-users"></i>
                        </div>
                        <span>Members: {{ \App\Models\User::whereIn('role_id', [3, 6])->where('status', true)->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon warning">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <span>Borrowed: 0</span>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="user-dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown">
                        {{ substr(auth()->user()->name, 0, 1) }}
                        <div class="notification-badge">2</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                        <li><a class="dropdown-item" href="{{ route('librarian.profile') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('librarian.notifications.index') }}">
                            <i class="fas fa-bell me-2"></i>Notifications
                            <span class="badge bg-danger ms-2">2</span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('librarian.settings') }}">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logout-form" action="{{ route('librarian.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Wrapper -->
        <div class="content-wrapper fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('sidebar-collapsed');
        });

        // Mobile Sidebar Toggle
        if (window.innerWidth <= 768) {
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('show');
            });
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Add loading states to buttons
        document.querySelectorAll('button[type="submit"]').forEach(function(button) {
            button.addEventListener('click', function() {
                this.classList.add('loading');
                this.disabled = true;
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-modern').forEach(function(button) {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
