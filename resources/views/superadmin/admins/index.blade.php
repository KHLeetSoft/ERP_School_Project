
@extends('superadmin.app')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="admin-icon me-3">
                            <i class="bx bx-user-check fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">School Administrators</h1>
                            <p class="text-muted mb-0">Manage school admin accounts and permissions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.admins.create') }}" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bx bx-plus me-2"></i>Add New Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="totalAdmins">-</h3>
                                    <p class="mb-0">Total Admins</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-user fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="activeAdmins">-</h3>
                                    <p class="mb-0">Active Admins</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-user-check fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="inactiveAdmins">-</h3>
                                    <p class="mb-0">Inactive Admins</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-user-x fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="schoolsCount">-</h3>
                                    <p class="mb-0">Schools</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-building fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Data Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-dark fw-bold">
                            <i class="bx bx-list-ul me-2 text-primary"></i>Admin List
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="refreshTable()">
                                <i class="bx bx-refresh me-1"></i>Refresh
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-download me-1"></i>Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="exportToCSV()">CSV</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="exportToPDF()">PDF</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="exportToExcel()">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="admin-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-semibold text-dark">#</span>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-building me-2 text-primary"></i>
                                            <span class="fw-semibold text-dark">School Name</span>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-user me-2 text-primary"></i>
                                            <span class="fw-semibold text-dark">Admin Name</span>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-envelope me-2 text-primary"></i>
                                            <span class="fw-semibold text-dark">Email</span>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-calendar me-2 text-primary"></i>
                                            <span class="fw-semibold text-dark">Create Date</span>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bx bx-check-circle me-2 text-primary"></i>
                                            <span class="fw-semibold text-dark">Status</span>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 px-4 text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="bx bx-cog me-2 text-primary"></i>
                                            <span class="fw-semibold text-dark">Actions</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-trash me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="delete-icon mb-3">
                        <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h6 class="fw-bold text-dark">Are you sure you want to delete this admin?</h6>
                    <p class="text-muted mb-0">Admin: <strong id="adminName" class="text-primary"></strong></p>
                    <p class="text-danger small mt-2">
                        <i class="bx bx-info-circle me-1"></i>This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>Delete Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
/* Custom Styles */
.stats-card {
    border-radius: 15px;
    border: none;
    transition: all 0.3s ease;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.stats-card .card-body {
    padding: 1.5rem;
}

.stats-icon {
    opacity: 0.3;
    transition: all 0.3s ease;
}

.stats-card:hover .stats-icon {
    opacity: 0.5;
    transform: scale(1.1);
}

.admin-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background-color: #f8f9ff;
    transform: scale(1.01);
    transition: all 0.2s ease;
}

.btn-action {
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.badge {
    border-radius: 20px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    font-size: 0.75rem;
}

.badge-light-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.badge-light-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Loading Animation */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Custom Scrollbar */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
let adminTable;

$(document).ready(function() {
    initializeDataTable();
    loadStats();
    
    // Delete confirmation
    $(document).on('click', '.delete-admin-btn', function() {
        const adminId = $(this).data('id');
        const adminName = $(this).closest('tr').find('td:nth-child(3)').text().trim();
        
        $('#adminName').text(adminName);
        $('#deleteForm').attr('action', `/superadmin/admins/${adminId}`);
        $('#deleteModal').modal('show');
    });
});

function initializeDataTable() {
    adminTable = $('#admin-datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        
        language: {
            processing: '<div class="loading"></div> Loading admins...',
            emptyTable: 'No admins found',
            zeroRecords: 'No matching admins found'
        },

        ajax: {
            url: '{{ route('superadmin.admins.datatable') }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            error: function (xhr, textStatus, errorThrown) {
                console.error('AJAX Error:', xhr.responseText);
                showAlert('Error loading data. Please try again.', 'danger');
            }
        },

        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                className: 'text-center fw-bold text-muted'
            },
            { 
                data: 'school', 
                name: 'school',
                render: function(data, type, row) {
                    return data || '<span class="text-muted">No School</span>';
                }
            },
            { 
                data: 'name', 
                name: 'name',
                render: function(data, type, row) {
                    return `<span class="fw-semibold text-dark">${data}</span>`;
                }
            },
            { 
                data: 'email', 
                name: 'email',
                render: function(data, type, row) {
                    return `<span class="text-primary">${data}</span>`;
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data, type, row) {
                    return `<span class="text-muted">${data}</span>`;
                }
            },
            { 
                data: 'status', 
                name: 'status',
                orderable: false,
                searchable: false
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            }
        ],

        drawCallback: function() {
            // Update stats after table is drawn
            updateStats();
        }
    });
}

function loadStats() {
    // This would typically be an AJAX call to get real stats
    // For now, we'll use placeholder values
    $('#totalAdmins').text('0');
    $('#activeAdmins').text('0');
    $('#inactiveAdmins').text('0');
    $('#schoolsCount').text('0');
}

function updateStats() {
    // Update stats based on current table data
    const info = adminTable.page.info();
    $('#totalAdmins').text(info.recordsTotal);
    
    // Count active/inactive admins from current page
    let activeCount = 0;
    let inactiveCount = 0;
    
    adminTable.rows({page: 'current'}).every(function() {
        const status = $(this.node()).find('.badge').text().trim();
        if (status === 'Active') {
            activeCount++;
        } else if (status === 'Inactive') {
            inactiveCount++;
        }
    });
    
    $('#activeAdmins').text(activeCount);
    $('#inactiveAdmins').text(inactiveCount);
}

function refreshTable() {
    adminTable.ajax.reload(null, false);
    showAlert('Table refreshed successfully', 'success');
}

function exportToCSV() {
    adminTable.button('.buttons-csv').trigger();
}

function exportToPDF() {
    adminTable.button('.buttons-pdf').trigger();
}

function exportToExcel() {
    adminTable.button('.buttons-excel').trigger();
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : 'error-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Add smooth animations
$(document).ready(function() {
    $('.stats-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
        $(this).addClass('animate__animated animate__fadeInUp');
    });
});
</script>
@endsection
