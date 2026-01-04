@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-bar-chart-line me-2 text-primary"></i> Exam Grades Dashboard
            </h4>
            <a href="{{ route('admin.exams.grades.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Add New Grade
            </a>
        </div>
        <div class="card-body">
            <p class="text-muted">Welcome to the Exam Grades Management Dashboard. Track grade distribution, performance stats, and manage grades easily.</p>

            <!-- Stats Quick Cards -->
            <div class="row text-center mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="bi bi-list-check fs-3 text-primary"></i>
                        <h5 class="mt-2">Total Grades</h5>
                        <p class="fw-bold">{{ $grades->count() ?? 0 }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="bi bi-trophy fs-3 text-success"></i>
                        <h5 class="mt-2">Highest Grade</h5>
                        <p class="fw-bold">{{ $grades->max('grade_point') ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="bi bi-graph-up-arrow fs-3 text-warning"></i>
                        <h5 class="mt-2">Average Point</h5>
                        <p class="fw-bold">{{ round($grades->avg('grade_point'),2) ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 p-3">
                        <i class="bi bi-people fs-3 text-danger"></i>
                        <h5 class="mt-2">Classes Covered</h5>
                        <p class="fw-bold">{{ $grades->pluck('class_id')->unique()->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <!-- Grade Distribution -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <i class="bi bi-pie-chart-fill text-primary me-2"></i> Grade Distribution
                        </div>
                        <div class="card-body">
                            <canvas id="gradeChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Performance Over Time -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <i class="bi bi-bar-chart-fill text-success me-2"></i> Average Grade Over Time
                        </div>
                        <div class="card-body">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- New Bar Chart -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <i class="bi bi-bar-chart-steps text-info me-2"></i> Grade Points Comparison
                        </div>
                        <div class="card-body">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- New Doughnut Chart -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <i class="bi bi-circle-half text-danger me-2"></i> Pass vs Fail
                        </div>
                        <div class="card-body">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest Grades Table -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-header bg-light">
                    <i class="bi bi-table text-secondary me-2"></i> Latest Grades
                </div>
                <div class="card-body">
                    <table id="gradesTable" class="table table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Grade Name</th>
                                <th>Grade Point</th>
                                <th>Class</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grades as $g)
                                <tr>
                                    <td>{{ $g->id }}</td>
                                    <td>{{ $g->grade_name }}</td>
                                    <td>{{ $g->grade_point }}</td>
                                    <td>{{ $g->class_id }}</td>
                                    <td>{{ $g->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Chart.js & DataTable -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script>
    // Pie Chart
    new Chart(document.getElementById('gradeChart'), {
        type: 'pie',
        data: {
            labels: @json($grades->pluck('grade_name')),
            datasets: [{
                data: @json($grades->pluck('grade_point')),
                backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1'],
            }]
        }
    });

    // Line Chart
    new Chart(document.getElementById('performanceChart'), {
        type: 'line',
        data: {
            labels: @json($grades->pluck('created_at')->map(fn($d)=>$d->format('M d'))),
            datasets: [{
                label: 'Grade Point',
                data: @json($grades->pluck('grade_point')),
                borderColor: '#0d6efd',
                fill: false,
                tension: 0.3
            }]
        }
    });

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: @json($grades->pluck('grade_name')),
            datasets: [{
                label: 'Grade Points',
                data: @json($grades->pluck('grade_point')),
                backgroundColor: '#198754'
            }]
        }
    });

    // Doughnut Chart
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pass','Fail'],
            datasets: [{
                data: [ 
                    {{ $grades->where('grade_point','>=',5)->count() }},
                    {{ $grades->where('grade_point','<',5)->count() }}
                ],
                backgroundColor: ['#198754','#dc3545']
            }]
        }
    });

    // DataTable
    $(document).ready(function () {
        $('#gradesTable').DataTable();
    });
</script>
@endsection
