<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Management System | @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- DateRangePicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <style>
        :root {
            --primary-color: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary-color: #374151;
            --accent-color: #dc2626;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
            --dark-color: #111827;
            --light-color: #f9fafb;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --formal-blue: #1e40af;
            --formal-gray: #374151;
            --formal-dark: #111827;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            overflow-x: hidden;
            color: #374151;
        }

        /* Sidebar Styles */
        .accountant-sidebar { 
            background: linear-gradient(180deg, #1e40af 0%, #1e3a8a 100%);
            color: #fff; 
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            z-index: 1040;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(30, 64, 175, 0.3);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .accountant-sidebar.collapsed {
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

        .accountant-sidebar.collapsed .sidebar-title {
            opacity: 0;
            transform: translateX(-20px);
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
            max-height: calc(100vh - 120px);
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) rgba(255, 255, 255, 0.1);
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.5rem;
        }

        .accountant-sidebar.collapsed .nav-section-title {
            opacity: 0;
            transform: translateX(-20px);
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
            position: relative;
            gap: 1rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: white;
            border-radius: 0 4px 4px 0;
        }

        .nav-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .nav-text {
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .accountant-sidebar.collapsed .nav-text {
            opacity: 0;
            transform: translateX(-20px);
        }

        .accountant-sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
            border-left: 1px solid #e2e8f0;
        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 20px rgba(30, 64, 175, 0.1);
            border-bottom: 2px solid #1e40af;
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
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e40af;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: white;
        }

        .stat-icon.primary { background: var(--primary-color); }
        .stat-icon.success { background: var(--success-color); }
        .stat-icon.warning { background: var(--warning-color); }
        .stat-icon.danger { background: var(--danger-color); }

        .user-dropdown {
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: var(--dark-color);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dropdown-item:hover {
            background: var(--light-color);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .dropdown-header {
            font-weight: 600;
            color: var(--dark-color);
            padding: 0.75rem 1rem 0.5rem;
        }

        /* Content Wrapper */
        .content-wrapper {
            padding: 2.5rem;
            min-height: calc(100vh - 100px);
            background: #ffffff;
            margin: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(30, 64, 175, 0.08);
            border: 1px solid #e2e8f0;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background: #ffffff;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(30, 64, 175, 0.15);
            border-color: #1e40af;
        }

        .card-header {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e40af, #1e3a8a);
            border: 1px solid #1e40af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        /* Tables */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--light-color);
            border: none;
            font-weight: 600;
            color: var(--dark-color);
            padding: 1rem;
        }

        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(16, 185, 129, 0.05);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        /* Badges */
        .badge {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
        }

        .badge-success {
            background: var(--success-color);
        }

        .badge-warning {
            background: var(--warning-color);
        }

        .badge-danger {
            background: var(--danger-color);
        }

        .badge-primary {
            background: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .accountant-sidebar {
                transform: translateX(-100%);
            }
            
            .accountant-sidebar.show {
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

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.2));
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.4));
        }

        .sidebar-nav::-webkit-scrollbar-thumb:active {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
        }

        /* AI Assistant Styles */
        .ai-assistant {
            max-height: 500px;
        }

        .ai-chat-container {
            height: 300px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .ai-messages {
            height: 200px;
            overflow-y: auto;
            padding: 1rem;
            background: #f8f9fa;
        }

        .ai-message {
            display: flex;
            margin-bottom: 1rem;
            align-items: flex-start;
        }

        .ai-message.ai-user {
            flex-direction: row-reverse;
        }

        .ai-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 0.5rem;
            font-size: 0.875rem;
        }

        .ai-message.ai-bot .ai-avatar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .ai-message.ai-user .ai-avatar {
            background: #6c757d;
            color: white;
        }

        .ai-content {
            flex: 1;
            background: white;
            padding: 0.75rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .ai-input-container {
            display: flex;
            padding: 1rem;
            background: white;
            border-top: 1px solid #e9ecef;
        }

        .ai-input-container input {
            border-radius: 20px;
            border: 1px solid #e9ecef;
            padding: 0.5rem 1rem;
        }

        .ai-quick-actions {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }

        .ai-quick-actions h6 {
            margin-bottom: 0.75rem;
            color: var(--dark-color);
        }

        /* Financial Calculator Styles */
        .financial-calculator {
            max-width: 300px;
            margin: 0 auto;
        }

        .calculator-display {
            margin-bottom: 1rem;
        }

        .calculator-buttons .btn {
            margin: 2px;
            font-weight: 600;
        }

        /* AI Analytics Styles */
        .ai-analytics .card {
            margin-bottom: 1rem;
        }

        .insights {
            space-y: 0.75rem;
        }

        .insight-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .insight-item i {
            font-size: 1.25rem;
        }

        /* Badge Styles */
        .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }

        .badge.ms-auto {
            margin-left: auto !important;
        }

        /* Modal Enhancements */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 16px 16px 0 0;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        /* Enhanced Button Styles */
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Loading Animation for AI */
        .ai-loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .ai-chat-container {
                height: 250px;
            }
            
            .ai-messages {
                height: 150px;
            }
            
            .financial-calculator {
                max-width: 100%;
            }
            
            .calculator-buttons .btn {
                padding: 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="accountant-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="sidebar-title">Financial Management System</div>
        </div>
        
        <nav class="sidebar-nav">
            <!-- Main Navigation -->
            <div class="nav-section">
                <div class="nav-section-title">Core Functions</div>
                <div class="nav-item">
                    <a href="{{ route('accountant.dashboard') }}" class="nav-link {{ request()->routeIs('accountant.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.profile') }}" class="nav-link {{ request()->routeIs('accountant.profile*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAIAssistant()">
                        <i class="nav-icon fas fa-robot"></i>
                        <span class="nav-text">AI Assistant</span>
                        <span class="badge bg-success ms-auto">NEW</span>
                    </a>
                </div>
            </div>

            <!-- Financial Management -->
            <div class="nav-section">
                <div class="nav-section-title">Financial Operations</div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.dashboard') }}" class="nav-link {{ request()->routeIs('accountant.payment.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span class="nav-text">Payment Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.payments.index') }}" class="nav-link {{ request()->routeIs('accountant.payment.payments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <span class="nav-text">Payment Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.qr-scanner') }}" class="nav-link {{ request()->routeIs('accountant.payment.qr-scanner') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-qrcode"></i>
                        <span class="nav-text">QR Scanner</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.online-payment') }}" class="nav-link {{ request()->routeIs('accountant.payment.online-payment*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-globe"></i>
                        <span class="nav-text">Online Payment</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.transaction-history') }}" class="nav-link {{ request()->routeIs('accountant.payment.transaction-history') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <span class="nav-text">Transaction History</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.school-qr-codes.index') }}" class="nav-link {{ request()->routeIs('accountant.payment.school-qr-codes*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-school"></i>
                        <span class="nav-text">School QR Code</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.payment.verification.index') }}" class="nav-link {{ request()->routeIs('accountant.payment.verification*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <span class="nav-text">Payment Verification</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.fees') }}" class="nav-link {{ request()->routeIs('accountant.fees*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-rupee-sign"></i>
                        <span class="nav-text">Fees Management</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showBulkPayment()">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <span class="nav-text">Bulk Payment</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showPaymentReminders()">
                        <i class="nav-icon fas fa-bell"></i>
                        <span class="nav-text">Payment Reminders</span>
                        <span class="badge bg-warning ms-auto">3</span>
                    </a>
                </div>
            </div>

            <!-- Advanced Analytics -->
            <div class="nav-section">
                <div class="nav-section-title">Business Intelligence</div>
                <div class="nav-item">
                    <a href="{{ route('accountant.reports') }}" class="nav-link {{ request()->routeIs('accountant.reports*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <span class="nav-text">Financial Reports</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAIAnalytics()">
                        <i class="nav-icon fas fa-brain"></i>
                        <span class="nav-text">AI Analytics</span>
                        <span class="badge bg-primary ms-auto">AI</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showPredictiveAnalysis()">
                        <i class="nav-icon fas fa-crystal-ball"></i>
                        <span class="nav-text">Predictive Analysis</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showRevenueForecast()">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <span class="nav-text">Revenue Forecast</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showExpenseTracking()">
                        <i class="nav-icon fas fa-receipt"></i>
                        <span class="nav-text">Expense Tracking</span>
                    </a>
                </div>
            </div>

            <!-- Student Management -->
            <div class="nav-section">
                <div class="nav-section-title">Student Services</div>
                <div class="nav-item">
                    <a href="{{ route('accountant.students') }}" class="nav-link {{ request()->routeIs('accountant.students*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <span class="nav-text">Students</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showFeeStructure()">
                        <i class="nav-icon fas fa-table"></i>
                        <span class="nav-text">Fee Structure</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showScholarships()">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <span class="nav-text">Scholarships</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showFeeWaivers()">
                        <i class="nav-icon fas fa-hand-holding-heart"></i>
                        <span class="nav-text">Fee Waivers</span>
                    </a>
                </div>
            </div>

            <!-- Communication & Notifications -->
            <div class="nav-section">
                <div class="nav-section-title">Communication Center</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showNotifications()">
                        <i class="nav-icon fas fa-bell"></i>
                        <span class="nav-text">Notifications</span>
                        <span class="badge bg-danger ms-auto">5</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showSMSAlerts()">
                        <i class="nav-icon fas fa-sms"></i>
                        <span class="nav-text">SMS Alerts</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showEmailCampaigns()">
                        <i class="nav-icon fas fa-envelope"></i>
                        <span class="nav-text">Email Campaigns</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showParentPortal()">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <span class="nav-text">Parent Portal</span>
                    </a>
                </div>
            </div>

            <!-- AI-Powered Features -->
            <div class="nav-section">
                <div class="nav-section-title">Advanced AI Tools</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAIFraudDetection()">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <span class="nav-text">Fraud Detection</span>
                        <span class="badge bg-success ms-auto">AI</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAIPatternRecognition()">
                        <i class="nav-icon fas fa-search"></i>
                        <span class="nav-text">Pattern Recognition</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAIAutomation()">
                        <i class="nav-icon fas fa-cogs"></i>
                        <span class="nav-text">Smart Automation</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAIDocumentProcessing()">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <span class="nav-text">Document Processing</span>
                    </a>
                </div>
            </div>

            <!-- QR Code Management -->
            <div class="nav-section">
                <div class="nav-section-title">Digital Solutions</div>
                <div class="nav-item">
                    <a href="{{ route('accountant.qr-codes') }}" class="nav-link {{ request()->routeIs('accountant.qr-codes*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-qrcode"></i>
                        <span class="nav-text">QR Codes</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('accountant.qr-codes.create') }}" class="nav-link {{ request()->routeIs('accountant.qr-codes.create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <span class="nav-text">Generate QR</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showBulkQRGeneration()">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <span class="nav-text">Bulk Generate</span>
                    </a>
                </div>
            </div>

            <!-- Tools & Utilities -->
            <div class="nav-section">
                <div class="nav-section-title">Professional Tools</div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showCalculator()">
                        <i class="nav-icon fas fa-calculator"></i>
                        <span class="nav-text">Financial Calculator</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showTaxCalculator()">
                        <i class="nav-icon fas fa-percentage"></i>
                        <span class="nav-text">Tax Calculator</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showInvoiceGenerator()">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <span class="nav-text">Invoice Generator</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showReceiptGenerator()">
                        <i class="nav-icon fas fa-receipt"></i>
                        <span class="nav-text">Receipt Generator</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showDataExport()">
                        <i class="nav-icon fas fa-download"></i>
                        <span class="nav-text">Data Export</span>
                    </a>
                </div>
            </div>

            <!-- Settings -->
            <div class="nav-section">
                <div class="nav-section-title">System Administration</div>
                <div class="nav-item">
                    <a href="{{ route('accountant.change-password') }}" class="nav-link {{ request()->routeIs('accountant.change-password*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key"></i>
                        <span class="nav-text">Change Password</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showPreferences()">
                        <i class="nav-icon fas fa-cog"></i>
                        <span class="nav-text">Preferences</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showBackupRestore()">
                        <i class="nav-icon fas fa-database"></i>
                        <span class="nav-text">Backup & Restore</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#" class="nav-link" onclick="showAuditLog()">
                        <i class="nav-icon fas fa-history"></i>
                        <span class="nav-text">Audit Log</span>
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
                            <i class="fas fa-users"></i>
                    </div>
                        <span>Students: {{ \App\Models\Student::where('status', true)->count() }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon success">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <span>Fees: {{ \App\Models\Fee::count() }}</span>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon warning">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span>Payments: {{ \App\Models\Payment::count() }}</span>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown">
                        {{ substr(auth()->user()->name, 0, 1) }}
                        <div class="notification-badge">2</div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">{{ auth()->user()->name }}</h6></li>
                        <li><a class="dropdown-item" href="{{ route('accountant.profile') }}">
                            <i class="fas fa-user me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-bell me-2"></i>Notifications
                            <span class="badge bg-danger ms-2">2</span>
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="logout-form" action="{{ route('accountant.logout') }}" method="POST" class="d-inline">
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

            // Add loading state to forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM') {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="loading"></span> Processing...';
                        submitBtn.disabled = true;
                }
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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

        // AI Assistant Modal
        function showAIAssistant() {
            showModal('AI Assistant', `
                <div class="ai-assistant">
                    <div class="ai-chat-container">
                        <div class="ai-messages" id="aiMessages">
                            <div class="ai-message ai-bot">
                                <div class="ai-avatar">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="ai-content">
                                    <p>Hello! I'm your AI accounting assistant. How can I help you today?</p>
                                </div>
                            </div>
                        </div>
                        <div class="ai-input-container">
                            <input type="text" id="aiInput" class="form-control" placeholder="Ask me anything about accounting, fees, or financial management...">
                            <button class="btn btn-primary" onclick="sendAIMessage()">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                    <div class="ai-quick-actions">
                        <h6>Quick Actions:</h6>
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="askAI('How to process bulk payments?')">
                                    <i class="fas fa-layer-group me-1"></i> Bulk Payments
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="askAI('Generate financial report')">
                                    <i class="fas fa-chart-bar me-1"></i> Generate Report
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="askAI('Check pending payments')">
                                    <i class="fas fa-clock me-1"></i> Pending Payments
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary btn-sm w-100 mb-2" onclick="askAI('Calculate tax for this month')">
                                    <i class="fas fa-percentage me-1"></i> Tax Calculation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }

        // AI Message Handler
        function sendAIMessage() {
            const input = document.getElementById('aiInput');
            const message = input.value.trim();
            if (message) {
                addAIMessage(message, 'user');
                input.value = '';
                
                // Simulate AI response
                setTimeout(() => {
                    const response = generateAIResponse(message);
                    addAIMessage(response, 'bot');
                }, 1000);
            }
        }

        function askAI(question) {
            document.getElementById('aiInput').value = question;
            sendAIMessage();
        }

        function addAIMessage(message, sender) {
            const messagesContainer = document.getElementById('aiMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `ai-message ai-${sender}`;
            
            const avatar = sender === 'bot' ? '<i class="fas fa-robot"></i>' : '<i class="fas fa-user"></i>';
            messageDiv.innerHTML = `
                <div class="ai-avatar">${avatar}</div>
                <div class="ai-content">
                    <p>${message}</p>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function generateAIResponse(message) {
            const responses = {
                'bulk payments': 'To process bulk payments, go to the Bulk Payment section and upload your CSV file with student details and amounts. The system will automatically validate and process all payments.',
                'financial report': 'I can help you generate various financial reports. Choose from: Monthly Revenue Report, Fee Collection Summary, Outstanding Payments Report, or Custom Report with specific date ranges.',
                'pending payments': 'Currently, you have 15 pending payments totaling ₹45,000. The oldest pending payment is from 3 days ago. Would you like me to send reminder notifications?',
                'tax calculation': 'For this month, your total taxable income is ₹2,50,000. Based on current tax rates, you need to pay ₹25,000 in taxes. This includes TDS of ₹15,000 and advance tax of ₹10,000.',
                'default': 'I understand you\'re asking about "' + message + '". Let me help you with that. Could you provide more specific details so I can give you the most accurate assistance?'
            };
            
            const lowerMessage = message.toLowerCase();
            for (const key in responses) {
                if (lowerMessage.includes(key)) {
                    return responses[key];
                }
            }
            return responses.default;
        }

        // Quick Payment Modal
        function showQuickPayment() {
            showModal('Quick Payment', `
                <form id="quickPaymentForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="studentId" placeholder="Enter Student ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" placeholder="Enter Amount">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="upi">UPI</option>
                            <option value="netbanking">Net Banking</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" rows="3" placeholder="Additional notes..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-credit-card me-1"></i> Process Payment
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            `);
        }

        // Bulk Payment Modal
        function showBulkPayment() {
            showModal('Bulk Payment', `
                <div class="bulk-payment">
                    <div class="mb-3">
                        <label class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" id="csvFile" accept=".csv">
                        <small class="text-muted">Download template: <a href="#" onclick="downloadTemplate()">bulk_payment_template.csv</a></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="bulkPaymentMethod">
                            <option value="upi">UPI</option>
                            <option value="netbanking">Net Banking</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Instructions:</strong> Upload a CSV file with columns: Student ID, Amount, Payment Method, Notes
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" onclick="processBulkPayment()">
                            <i class="fas fa-upload me-1"></i> Process Bulk Payment
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            `);
        }

        // AI Analytics Modal
        function showAIAnalytics() {
            showModal('AI Analytics Dashboard', `
                <div class="ai-analytics">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6><i class="fas fa-brain me-2"></i>Revenue Prediction</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6><i class="fas fa-chart-pie me-2"></i>Payment Patterns</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="patternChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6><i class="fas fa-lightbulb me-2"></i>AI Insights</h6>
                                </div>
                                <div class="card-body">
                                    <div class="insights">
                                        <div class="insight-item">
                                            <i class="fas fa-arrow-up text-success me-2"></i>
                                            <span>Revenue increased by 15% this month compared to last month</span>
                                        </div>
                                        <div class="insight-item">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            <span>5 students have overdue payments for more than 30 days</span>
                                        </div>
                                        <div class="insight-item">
                                            <i class="fas fa-lightbulb text-info me-2"></i>
                                            <span>Consider offering early payment discounts to improve cash flow</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }

        // Financial Calculator Modal
        function showCalculator() {
            showModal('Financial Calculator', `
                <div class="financial-calculator">
                    <div class="calculator-display">
                        <input type="text" class="form-control text-end fs-4" id="calcDisplay" value="0" readonly>
                    </div>
                    <div class="calculator-buttons">
                        <div class="row">
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcClear()">C</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcBackspace()">⌫</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('/')">/</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('*')">×</button></div>
                        </div>
                        <div class="row">
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('7')">7</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('8')">8</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('9')">9</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('-')">-</button></div>
                        </div>
                        <div class="row">
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('4')">4</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('5')">5</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('6')">6</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('+')">+</button></div>
                        </div>
                        <div class="row">
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('1')">1</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('2')">2</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('3')">3</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcEquals()" style="height: 100px;">=</button></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><button class="btn btn-outline-secondary w-100" onclick="calcInput('0')">0</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('.')">.</button></div>
                            <div class="col-3"><button class="btn btn-outline-secondary w-100" onclick="calcInput('%')">%</button></div>
                        </div>
                    </div>
                </div>
            `);
        }

        // Calculator Functions
        let calcDisplay = '0';
        let calcOperation = null;
        let calcPreviousValue = null;

        function calcInput(value) {
            if (calcDisplay === '0' && value !== '.') {
                calcDisplay = value;
            } else {
                calcDisplay += value;
            }
            document.getElementById('calcDisplay').value = calcDisplay;
        }

        function calcClear() {
            calcDisplay = '0';
            calcOperation = null;
            calcPreviousValue = null;
            document.getElementById('calcDisplay').value = calcDisplay;
        }

        function calcBackspace() {
            if (calcDisplay.length > 1) {
                calcDisplay = calcDisplay.slice(0, -1);
            } else {
                calcDisplay = '0';
            }
            document.getElementById('calcDisplay').value = calcDisplay;
        }

        function calcEquals() {
            if (calcOperation && calcPreviousValue !== null) {
                const result = eval(calcPreviousValue + calcOperation + calcDisplay);
                calcDisplay = result.toString();
                calcOperation = null;
                calcPreviousValue = null;
                document.getElementById('calcDisplay').value = calcDisplay;
            }
        }

        // Generic Modal Function
        function showModal(title, content) {
            const modalHtml = `
                <div class="modal fade" id="featureModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${content}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('featureModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add new modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('featureModal'));
            modal.show();
        }

        // Placeholder functions for other features
        function showPaymentReminders() { showModal('Payment Reminders', '<p>Payment reminders feature coming soon...</p>'); }
        function showPredictiveAnalysis() { showModal('Predictive Analysis', '<p>AI-powered predictive analysis feature coming soon...</p>'); }
        function showRevenueForecast() { showModal('Revenue Forecast', '<p>Revenue forecasting feature coming soon...</p>'); }
        function showExpenseTracking() { showModal('Expense Tracking', '<p>Expense tracking feature coming soon...</p>'); }
        function showFeeStructure() { showModal('Fee Structure', '<p>Fee structure management feature coming soon...</p>'); }
        function showScholarships() { showModal('Scholarships', '<p>Scholarship management feature coming soon...</p>'); }
        function showFeeWaivers() { showModal('Fee Waivers', '<p>Fee waiver management feature coming soon...</p>'); }
        function showNotifications() { showModal('Notifications', '<p>Notification center feature coming soon...</p>'); }
        function showSMSAlerts() { showModal('SMS Alerts', '<p>SMS alert system feature coming soon...</p>'); }
        function showEmailCampaigns() { showModal('Email Campaigns', '<p>Email campaign management feature coming soon...</p>'); }
        function showParentPortal() { showModal('Parent Portal', '<p>Parent portal integration feature coming soon...</p>'); }
        function showAIFraudDetection() { showModal('AI Fraud Detection', '<p>AI-powered fraud detection feature coming soon...</p>'); }
        function showAIPatternRecognition() { showModal('AI Pattern Recognition', '<p>Pattern recognition feature coming soon...</p>'); }
        function showAIAutomation() { showModal('AI Automation', '<p>Smart automation feature coming soon...</p>'); }
        function showAIDocumentProcessing() { showModal('AI Document Processing', '<p>Document processing feature coming soon...</p>'); }
        function showTaxCalculator() { showModal('Tax Calculator', '<p>Tax calculation feature coming soon...</p>'); }
        function showInvoiceGenerator() { showModal('Invoice Generator', '<p>Invoice generation feature coming soon...</p>'); }
        function showReceiptGenerator() { showModal('Receipt Generator', '<p>Receipt generation feature coming soon...</p>'); }
        function showDataExport() { showModal('Data Export', '<p>Data export feature coming soon...</p>'); }
        function showPreferences() { showModal('Preferences', '<p>User preferences feature coming soon...</p>'); }
        function showBackupRestore() { showModal('Backup & Restore', '<p>Backup and restore feature coming soon...</p>'); }
        function showAuditLog() { showModal('Audit Log', '<p>Audit log feature coming soon...</p>'); }
        function downloadTemplate() { alert('Template download feature coming soon...'); }
        function processBulkPayment() { alert('Bulk payment processing feature coming soon...'); }
        
        // Bulk QR Generation Modal
        function showBulkQRGeneration() {
            showModal('Bulk QR Code Generation', `
                <form id="bulkQRForm" action="{{ route('accountant.qr-codes.bulk-generate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">QR Code Type</label>
                        <select class="form-select" name="type" required>
                            <option value="student">Student QR Codes</option>
                            <option value="payment">Payment QR Codes</option>
                            <option value="fee">Fee QR Codes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title Prefix</label>
                        <input type="text" class="form-control" name="title_prefix" value="Student QR" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Students</label>
                        <div class="row" id="studentList">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAllStudents()">
                                    <label class="form-check-label" for="selectAll">
                                        <strong>Select All Students</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-qrcode me-1"></i> Generate QR Codes
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            `);
            
            // Load students dynamically
            loadStudentsForBulkQR();
        }
        
        function loadStudentsForBulkQR() {
            // This would typically load students via AJAX
            // For now, we'll show a placeholder
            const studentList = document.getElementById('studentList');
            studentList.innerHTML += `
                <div class="col-12 mt-2">
                    <small class="text-muted">Loading students...</small>
                </div>
            `;
        }
        
        function toggleAllStudents() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }
    </script>
    
    <script>
    $(document).ready(function() {
        // Initialize DateRangePicker
        $('.daterange-picker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });

        $('.daterange-picker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('.daterange-picker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
    </script>

    @yield('scripts')
</body>
</html>
