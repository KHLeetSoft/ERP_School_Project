@extends('student.layout.app')

@section('title', 'Results Report')
@section('page-title', 'Results Report')

@section('content')
<div class="row">
    <!-- Report Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">Academic Performance Report</h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            Academic Year: {{ $report['academic_year'] }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user me-1"></i>
                            Student: {{ $report['student']->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" onclick="printReport()">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="exportPDF()">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Exams</h6>
                            <h4 class="mb-0">{{ $report['stats']['total_exams'] }}</h4>
                            <small class="text-muted">{{ $report['stats']['total_subjects'] }} subjects</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Average %</h6>
                            <h4 class="mb-0">{{ $report['stats']['average_percentage'] }}%</h4>
                            <small class="text-muted">Overall performance</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Pass Rate</h6>
                            <h4 class="mb-0">{{ $report['stats']['pass_percentage'] }}%</h4>
                            <small class="text-muted">{{ $report['stats']['pass_count'] }} passed</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Best Score</h6>
                            <h4 class="mb-0">{{ $report['stats']['highest_percentage'] }}%</h4>
                            <small class="text-muted">Highest percentage</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Performance Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Grade Distribution -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Grade Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="gradeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Subject Performance -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject-wise Performance</h5>
            </div>
            <div class="card-body">
                @if($report['subject_performance']->count() > 0)
                    <div class="row">
                        @foreach($report['subject_performance'] as $subject)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="subject-card">
                                    <div class="subject-header">
                                        <h6 class="mb-1">{{ $subject['subject'] }}</h6>
                                        <span class="badge bg-{{ $subject['average_percentage'] >= 80 ? 'success' : ($subject['average_percentage'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ $subject['average_percentage'] }}%
                                        </span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $subject['average_percentage'] >= 80 ? 'success' : ($subject['average_percentage'] >= 60 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $subject['average_percentage'] }}%"></div>
                                    </div>
                                    <div class="subject-stats">
                                        <small class="text-muted">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            {{ $subject['total_exams'] }} exam(s) | 
                                            <i class="fas fa-trophy me-1"></i>
                                            Best: {{ $subject['best_percentage'] }}% | 
                                            <i class="fas fa-chart-line me-1"></i>
                                            Grade: {{ $subject['grade'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-book text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No subject performance data available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Performance -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Monthly Performance</h5>
            </div>
            <div class="card-body">
                @if($report['monthly_performance']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Exams</th>
                                    <th>Average %</th>
                                    <th>Best Score</th>
                                    <th>Worst Score</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($report['monthly_performance'] as $month)
                                    <tr>
                                        <td><strong>{{ $month['month'] }}</strong></td>
                                        <td>{{ $month['total_exams'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $month['average_percentage'] >= 80 ? 'success' : ($month['average_percentage'] >= 60 ? 'warning' : 'danger') }}">
                                                {{ $month['average_percentage'] }}%
                                            </span>
                                        </td>
                                        <td>{{ $month['best_percentage'] }}%</td>
                                        <td>{{ $month['worst_percentage'] }}%</td>
                                        <td>
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar bg-{{ $month['average_percentage'] >= 80 ? 'success' : ($month['average_percentage'] >= 60 ? 'warning' : 'danger') }}" 
                                                     style="width: {{ $month['average_percentage'] }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No monthly performance data available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detailed Results</h5>
            </div>
            <div class="card-body">
                @if($report['exam_groups']->count() > 0)
                    @foreach($report['exam_groups'] as $examId => $examResults)
                        @php $exam = $report['exams'][$examId] ?? null; @endphp
                        @if($exam)
                            <div class="exam-group mb-4">
                                <h6 class="exam-title">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    {{ $exam->title }} 
                                    <span class="badge bg-secondary ms-2">{{ ucfirst($exam->exam_type) }}</span>
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Subject</th>
                                                <th>Marks</th>
                                                <th>%</th>
                                                <th>Grade</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($examResults as $result)
                                                <tr>
                                                    <td>{{ $result->subject_name }}</td>
                                                    <td>{{ $result->obtained_marks }}/{{ $result->max_marks }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 60 ? 'warning' : 'danger') }}">
                                                            {{ $result->percentage }}%
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $this->getGradeColor($result->grade) }}">
                                                            {{ $result->grade }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($result->result_status === 'pass')
                                                            <span class="badge bg-success">Pass</span>
                                                        @else
                                                            <span class="badge bg-danger">Fail</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-graduation-cap text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No detailed results available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.results.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Results
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.results.transcript') }}" class="btn btn-outline-success">
                            <i class="fas fa-certificate me-2"></i>Academic Transcript
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .subject-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border-left: 4px solid #007bff;
    }

    .subject-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .exam-group {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .exam-title {
        color: #007bff;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
    }

    @media print {
        .btn, .card-header, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Performance Chart
    const ctx1 = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($report['monthly_performance']->pluck('month')) !!},
            datasets: [{
                label: 'Average Percentage',
                data: {!! json_encode($report['monthly_performance']->pluck('average_percentage')) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Grade Distribution Chart
    const ctx2 = document.getElementById('gradeChart').getContext('2d');
    const gradeChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($report['subject_performance']->pluck('grade')->unique()) !!},
            datasets: [{
                data: {!! json_encode($report['subject_performance']->groupBy('grade')->map->count()->values()) !!},
                backgroundColor: [
                    '#28a745', '#007bff', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#e83e8c'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Print Report
    function printReport() {
        window.print();
    }

    // Export PDF (placeholder)
    function exportPDF() {
        alert('PDF export functionality would be implemented here.');
    }
</script>
@endsection

@php
    function getGradeColor($grade) {
        switch($grade) {
            case 'A+':
            case 'A':
                return 'success';
            case 'B+':
            case 'B':
                return 'info';
            case 'C+':
            case 'C':
                return 'warning';
            default:
                return 'danger';
        }
    }
@endphp
