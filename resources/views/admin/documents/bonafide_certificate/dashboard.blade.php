@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.documents.bonafide-certificate.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Index
        </a>
    </div>

    {{-- Status Cards --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body text-center">
                    <i class="bi bi-collection display-6 mb-2"></i>
                    <div class="fw-bold">Total Certificates</div>
                    <div class="display-6">{{ $statusCounts->sum() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-6 mb-2"></i>
                    <div class="fw-bold">Issued</div>
                    <div class="display-6">{{ $statusCounts['issued'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text display-6 mb-2"></i>
                    <div class="fw-bold">Draft</div>
                    <div class="display-6">{{ $statusCounts['draft'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle display-6 mb-2"></i>
                    <div class="fw-bold">Cancelled</div>
                    <div class="display-6">{{ $statusCounts['cancelled'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Small Graph --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold">
            Certificates Summary (Chart)
        </div>
        <div class="card-body">
            <div id="certificateChart" style="height:200px;"></div>
        </div>
    </div>

    {{-- Recent Certificates Table --}}
    <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <span>Recent Bonafide Certificates</span>
            <div class="d-flex gap-2">
                <select id="statusFilter" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="issued">Issued</option>
                    <option value="draft">Draft</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <input type="text" id="searchBox" class="form-control form-control-sm" placeholder="Search student...">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="certTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>BC No.</th>
                            <th>Student</th>
                            <th>Purpose</th>
                            <th>Issue Date</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent as $r)
                            <tr data-status="{{ $r->status }}" data-student="{{ strtolower($r->student_name) }}">
                                <td>{{ $r->id }}</td>
                                <td><span class="badge bg-secondary">{{ $r->bc_number }}</span></td>
                                <td>{{ $r->student_name }}</td>
                                <td>{{ $r->purpose }}</td>
                                <td>{{ optional($r->issue_date)->format('Y-m-d') }}</td>
                                <td>
                                    @if($r->status === 'issued')
                                        <span class="badge bg-success">Issued</span>
                                    @elseif($r->status === 'draft')
                                        <span class="badge bg-primary">Draft</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.documents.bonafide-certificate.show', $r) }}" 
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">No records</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Small Pie Chart
    var options = {
        chart: { type: 'pie', height: 150 },
        labels: ['Issued', 'Draft', 'Cancelled'],
        series: [
            {{ $statusCounts['issued'] ?? 0 }},
            {{ $statusCounts['draft'] ?? 0 }},
            {{ $statusCounts['cancelled'] ?? 0 }}
        ],
        colors: ['#198754', '#0d6efd', '#dc3545'],
        legend: { position: 'bottom', fontSize: '12px' }
    };
    new ApexCharts(document.querySelector("#certificateChart"), options).render();

    // Search & Filter in Table
    const rows = document.querySelectorAll("#certTable tbody tr");
    document.getElementById("statusFilter").addEventListener("change", function() {
        filterTable();
    });
    document.getElementById("searchBox").addEventListener("keyup", function() {
        filterTable();
    });

    function filterTable() {
        let status = document.getElementById("statusFilter").value;
        let search = document.getElementById("searchBox").value.toLowerCase();
        rows.forEach(row => {
            let rowStatus = row.getAttribute("data-status");
            let student = row.getAttribute("data-student");
            let matchStatus = !status || rowStatus === status;
            let matchSearch = !search || student.includes(search);
            row.style.display = (matchStatus && matchSearch) ? "" : "none";
        });
    }
</script>
@endsection
