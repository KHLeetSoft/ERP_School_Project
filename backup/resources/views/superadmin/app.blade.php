
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    {{-- Add this inside <head> --}}
    <!-- DataTables core -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- DataTables Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- DateRangePicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>



    <style>

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        transition: background 0.3s;
        font-size: 15px;
    }

    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        text-decoration: none;
    }

    .sidebar .nav-link.active {
        background-color: #ffffff33;
        font-weight: bold;
    }

    .sidebar .nav-link i {
        font-size: 18px;
        margin-right: 10px;
    }

    .sidebar .btn-logout {
        width: 100%;
        text-align: left;
        padding-left: 15px;
        color: white;
    }

    .sidebar .btn-logout:hover {
        background-color: #dc3545 !important;
        color: white;
    }
        .content-wrapper {
            padding: 20px;
        }
        .small-box {
            border-radius: 10px;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .small-box .icon {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3;
        }
        .small-box-footer {
            display: block;
            background-color: rgba(0,0,0,0.1);
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 10px;
            margin: -20px -20px -20px -20px;
            margin-top: 15px;
        }
        .bg-info { background: linear-gradient(135deg, #17a2b8, #138496); }
        .bg-success { background: linear-gradient(135deg, #28a745, #20c997); }
        .bg-warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }
        .bg-danger { background: linear-gradient(135deg, #dc3545, #c82333); }


        /* Button Colors */
    .btn-csv {
        background-color: #28a745;
        color: #fff;
    }
    .btn-print {
        background-color: #ffc107;
        color: #212529;
    }
    .btn-pdf {
        background-color: #dc3545;
        color: #fff;
    }
    .btn-copy {
        background-color: #17a2b8;
        color: #fff;
    }

    /* Hover Effect */
    .dt-buttons-row .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        transition: all 0.2s ease-in-out;
    }

    /* Responsive spacing */
    .dt-buttons-row {
        margin-bottom: 10px;
    }
    </style>
</head>
<body>
  

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar text-white p-0">
                <div class="p-3">
                    <h4>🔐 SuperAdmin</h4>
                    <hr>
                    <ul class="nav flex-column sidebar">
    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
           href="{{ route('superadmin.dashboard') }}">
            <i class="bx bx-home"></i> Dashboard
        </a>
    </li>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('superadmin.schools.*') ? 'active' : '' }}"
           href="{{ route('superadmin.schools.index') }}">
            <i class="bx bx-school"></i> Schools
        </a>
    </li>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('superadmin.admins.*') ? 'active' : '' }}"
           href="{{ route('superadmin.admins.index') }}">
            <i class="bx bx-user"></i> Admins
        </a>
    </li>

    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('superadmin.settings.*') ? 'active' : '' }}"
           href="{{ route('superadmin.settings.index') }}">
            <i class="bx bx-cog"></i> Settings
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link text-white {{ request()->routeIs('superadmin.purchases.*') ? 'active' : '' }}"
        href="{{ route('superadmin.purchases.index') }}">
            <i class="bx bx-cart"></i> Purchases
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('superadmin.productplans.index') }}"
        class="nav-link d-flex align-items-center gap-2 text-white {{ request()->routeIs('superadmin.productplans.*') ? 'active bg-primary text-white rounded' : '' }}">
            <i class="bx bx-package fs-5"></i>
            <span>Product Plans</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('superadmin.payment.school-qr-codes.index') }}"
        class="nav-link d-flex align-items-center gap-2 text-white {{ request()->routeIs('superadmin.payment.school-qr-codes.*') ? 'active bg-primary text-white rounded' : '' }}">
            <i class="bx bx-qr-scan fs-5"></i>
            <span>School QR Codes</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('superadmin.payment.gateways.index') }}"
        class="nav-link d-flex align-items-center gap-2 text-white {{ request()->routeIs('superadmin.payment.gateways.*') ? 'active bg-primary text-white rounded' : '' }}">
            <i class="bx bx-credit-card fs-5"></i>
            <span>Payment Gateways</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('superadmin.payment.plans.index') }}"
        class="nav-link d-flex align-items-center gap-2 text-white {{ request()->routeIs('superadmin.payment.plans.*') ? 'active bg-primary text-white rounded' : '' }}">
            <i class="bx bx-package fs-5"></i>
            <span>Payment Plans</span>
        </a>
    </li>
    <li class="nav-item mb-2">
        <a href="{{ route('superadmin.payment.reports.index') }}"
        class="nav-link d-flex align-items-center gap-2 text-white {{ request()->routeIs('superadmin.payment.reports.*') ? 'active bg-primary text-white rounded' : '' }}">
            <i class="bx bx-bar-chart fs-5"></i>
            <span>Payment Reports</span>
        </a>
    </li>


    <li class="nav-item mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm btn-logout">
                <i class="bx bx-log-out"></i> Logout
            </button>
        </form>
    </li>
</ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Add these before closing </body> --}}

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
