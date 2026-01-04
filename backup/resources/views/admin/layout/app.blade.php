<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    {{-- Add this inside <head> --}}
    <!-- DataTables core -->
       <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DateRangePicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
   
    <style>
        body { min-height: 100vh; }
        .sidebar { height: 100vh; background: #343a40; color: #fff; }
        .sidebar a { color: #ddd; text-decoration: none; }
        .sidebar a.active, .sidebar a:hover { color: #fff; background: #495057; }
        .buttons-csv {
            background-color: #28a745 !important;       /* Green background */
            color: white !important;         /* White text */
            border: none;
            border-radius: 4px;
            padding: 6px 18px !important;
            font-weight: 500;
            font-size: 10px !important;
            transition: background 0.3s ease;
        }

        .buttons-csv:hover {
            background-color: #218838 !important;       /* Darker green on hover */
            color: #fff;
        }
       
        .buttons-pdf {
            background-color: #dc3545 !important;       /* Bootstrap danger red */
            color: #fff !important;          /* White text */
            border: none;
            border-radius: 4px;
            padding: 6px 18px !important;
            font-weight: 500;
            font-size: 10px !important;
            transition: background 0.3s ease;
            }

        .buttons-pdf:hover {
            background-color: #bd2130 !important;       /* Darker red on hover */
            color: #fff;
        }
        .buttons-print {
            background-color: #17a2b8 !important;       /* Bootstrap info blue */
            color: #fff !important;
            border: none;
            border-radius: 4px;
            padding: 6px 18px !important;
            font-weight: 500;
            font-size: 10px !important;
            transition: background 0.3s ease;
            }

            .buttons-print:hover {
            background-color: #117a8b !important;       /* Darker blue on hover */
            color: #fff;
            }
            .buttons-copy {
            background-color: #6c757d !important;       /* Bootstrap secondary gray */
            color: #fff !important;
            border: none;
            border-radius: 4px;
            padding: 6px 18px !important;
            font-weight: 500;
            font-size: 10px !important;
            transition: background 0.3s ease;
            }

            .buttons-copy:hover {
            background-color: #5a6268 !important;       /* Darker gray on hover */
            color: #fff;
            }
            .badge-light-success {
                color: #155724;  
                font-weight: 500;    
                font-size: 14px;       /* Darker text */
            }
            .bx-show, .bxs-edit, .bx-trash, .bx-key, .bx-phone-call, .table-dropdown, 
            .bx-log-out, .bx-log-in, .bx-time-five, .bx-printer{
                font-size: 20px;
                font-weight: lighter;
                color: dimgray;
                padding: 4px;"
            }
            .bx-toggle-right, .bx-toggle-left{
                font-size: 24px;
                font-weight: lighter;
                color: dimgray;
                padding: 4px;"
            }
            .bx-show:hover{
                color: #0cf5d9;
            }
            .bxs-edit:hover{
                
                color:rgb(12, 28, 245);
            }
            .bx-trash:hover{
                color: #C70039;
            } 
            .bx-key:hover{
                color:#ffc300;
            }
            .bx-phone-call:hover{
                color: #0cff00;
            }
            .bx-log-out:hover{
                color: #ff00e8;
            }
            .bx-log-in:hover{
                color: #FF8674;
            }
            .bx-time-five:hover{
                color: #D35400;
            }
            .bx-toggle-left:hover{
                color: #9F0712;
            }
            .bx-toggle-right:hover{
                color: #A3044C
            }
            .bx-printer:hover{
                color: #4B0150;
            }
            .badge-light-warning {
                color: #856404;               /* Text color */
                background-color: #fff3cd;    /* Light warning background */
                padding: 0.35em 0.65em;
                font-size: 0.75rem;
                font-weight: 600;
                border-radius: 0.375rem;      /* Rounded corners */
                display: inline-block;
                line-height: 1;
                text-align: center;
                vertical-align: baseline;
                white-space: nowrap;
            }
            .badge-light-secondary {
                background-color: #e2e3e5; /* Light gray */
                color: #41464b;            /* Darker text for contrast */
                border: 1px solid transparent;
                font-size: 0.75rem;
                font-weight: 600;
                padding: 0.35em 0.65em;
                border-radius: 0.375rem;
            }

            /* Footer Styling */
            .main-footer {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-top: 1px solid #dee2e6;
                padding: 15px 20px;
                margin-top: 30px;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                font-size: 14px;
                color: #6c757d;
                text-align: center;
            }

            .main-footer strong {
                color: #495057;
                font-weight: 600;
            }

            .main-footer a {
                color: #007bff;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .main-footer a:hover {
                color: #0056b3;
                text-decoration: underline;
            }

            .main-footer .float-right {
                color: #6c757d;
                font-size: 13px;
            }

            .main-footer .float-right b {
                color: #495057;
                font-weight: 600;
            }

            /* Responsive Footer */
            @media (max-width: 768px) {
                .main-footer {
                    text-align: center;
                    padding: 12px 15px;
                }
                
                .main-footer .float-right {
                    float: none !important;
                    display: block;
                    margin-top: 8px;
                }
            }

            /* Center align all footer content */
            .main-footer .float-right {
                float: none !important;
                display: inline-block;
                margin-left: 20px;
            }

            /* Modern Alert Styles */
            .modern-alert {
                border-radius: 16px;
                border: none;
                padding: 20px 25px;
                margin-bottom: 25px;
                font-weight: 500;
                position: relative;
                overflow: hidden;
                backdrop-filter: blur(10px);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                animation: slideInDown 0.5s ease-out;
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .modern-alert::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                animation: shimmer 2s infinite;
            }

            .modern-alert.alert-success {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
                color: #059669;
                border: 1px solid rgba(16, 185, 129, 0.2);
                border-left: 5px solid #10b981;
            }

            .modern-alert.alert-danger {
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.05) 100%);
                color: #dc2626;
                border: 1px solid rgba(239, 68, 68, 0.2);
                border-left: 5px solid #dc2626;
            }

            .modern-alert.alert-warning {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.05) 100%);
                color: #d97706;
                border: 1px solid rgba(245, 158, 11, 0.2);
                border-left: 5px solid #f59e0b;
            }

            .modern-alert.alert-info {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
                color: #2563eb;
                border: 1px solid rgba(59, 130, 246, 0.2);
                border-left: 5px solid #3b82f6;
            }

            .alert-icon {
                font-size: 1.5rem;
                flex-shrink: 0;
                animation: pulse 2s infinite;
            }

            .alert-content {
                flex: 1;
                font-size: 1rem;
                line-height: 1.5;
            }

            .alert-close {
                background: none;
                border: none;
                font-size: 1.2rem;
                color: inherit;
                opacity: 0.7;
                cursor: pointer;
                padding: 5px;
                border-radius: 50%;
                transition: all 0.3s ease;
                flex-shrink: 0;
            }

            .alert-close:hover {
                opacity: 1;
                background: rgba(0, 0, 0, 0.1);
                transform: scale(1.1);
            }

            @keyframes slideInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideOutUp {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-30px);
                }
            }

            @keyframes shimmer {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.05); }
            }

    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-2 p-3 sidebar">
            @include('admin.layout.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-md-10 p-4">
            @include('admin.layout.header')

            <div class="content">
                <!-- Modern Alerts Section -->
                @if(session('success'))
                    <div class="alert alert-success modern-alert" id="successAlert">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <div class="alert-content">
                            <strong>Success:</strong> {{ session('success') }}
                        </div>
                        <button type="button" class="alert-close" onclick="closeAlert('successAlert')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger modern-alert" id="errorAlert">
                        <i class="fas fa-exclamation-triangle alert-icon"></i>
                        <div class="alert-content">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                        <button type="button" class="alert-close" onclick="closeAlert('errorAlert')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning modern-alert" id="warningAlert">
                        <i class="fas fa-exclamation-circle alert-icon"></i>
                        <div class="alert-content">
                            <strong>Warning:</strong> {{ session('warning') }}
                        </div>
                        <button type="button" class="alert-close" onclick="closeAlert('warningAlert')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info modern-alert" id="infoAlert">
                        <i class="fas fa-info-circle alert-icon"></i>
                        <div class="alert-content">
                            <strong>Info:</strong> {{ session('info') }}
                        </div>
                        <button type="button" class="alert-close" onclick="closeAlert('infoAlert')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger modern-alert" id="validationAlert">
                        <i class="fas fa-exclamation-triangle alert-icon"></i>
                        <div class="alert-content">
                            <strong>Validation Error:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button type="button" class="alert-close" onclick="closeAlert('validationAlert')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')

                  @yield('scripts')
            </div>
          
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

             
            @include('admin.layout.footer')
           
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables core -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Buttons extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- JSZip & pdfmake (required for export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<!-- Bootstrap JS
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

 DataTables Buttons -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        // Modern Alert Functions
        function closeAlert(alertElement) {
            if (typeof alertElement === 'string') {
                alertElement = document.getElementById(alertElement);
            }
            
            if (alertElement) {
                alertElement.style.animation = 'slideOutUp 0.3s ease-in forwards';
                setTimeout(() => {
                    if (alertElement.parentNode) {
                        alertElement.remove();
                    }
                }, 300);
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.modern-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.parentNode) {
                        closeAlert(alert);
                    }
                }, 5000);
            });
        });
    </script> --> 
</body>
</html>
