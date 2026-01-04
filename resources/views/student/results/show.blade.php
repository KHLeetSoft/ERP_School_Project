@extends('student.layout.app')

@section('title', 'Exam Results')
@section('page-title', 'Exam Results')

@section('content')
<div class="row">
    <!-- Exam Information -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">{{ $exam->title }}</h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse($exam->end_date)->format('M d, Y') }}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-tag me-1"></i>
                            {{ ucfirst($exam->exam_type) }} Exam
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="exam-status">
                            @if($exam->status === 'completed')
                                <span class="badge bg-success fs-6">Completed</span>
                            @elseif($exam->status === 'scheduled')
                                <span class="badge bg-info fs-6">Scheduled</span>
                            @else
                                <span class="badge bg-secondary fs-6">{{ ucfirst($exam->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Subjects</h6>
                            <h4 class="mb-0">{{ $examStats['total_subjects'] }}</h4>
                            <small class="text-muted">Total subjects</small>
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
                            <h4 class="mb-0">{{ $examStats['average_percentage'] }}%</h4>
                            <small class="text-muted">Overall performance</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Grade</h6>
                            <h4 class="mb-0">{{ $examStats['grade'] }}</h4>
                            <small class="text-muted">Overall grade</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon {{ $examStats['result_status'] === 'pass' ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas fa-{{ $examStats['result_status'] === 'pass' ? 'check' : 'times' }}"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Status</h6>
                            <h4 class="mb-0">{{ ucfirst($examStats['result_status']) }}</h4>
                            <small class="text-muted">Exam result</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Subject-wise Results</h5>
            </div>
            <div class="card-body">
                @if($examResults->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Marks Obtained</th>
                                    <th>Total Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($examResults as $result)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="subject-icon me-3">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $result->subject_name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $result->obtained_marks }}</span>
                                        </td>
                                        <td>{{ $result->max_marks }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 60 ? 'warning' : 'danger') }} me-2">
                                                    {{ $result->percentage }}%
                                                </span>
                                                <div class="progress" style="width: 60px; height: 6px;">
                                                    <div class="progress-bar bg-{{ $result->percentage >= 80 ? 'success' : ($result->percentage >= 60 ? 'warning' : 'danger') }}" 
                                                         style="width: {{ $result->percentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $this->getGradeColor($result->grade) }} fs-6">
                                                {{ $result->grade }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($result->result_status === 'pass')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Pass
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Fail
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($result->remarks)
                                                <span class="text-muted">{{ $result->remarks }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Results Available</h4>
                        <p class="text-muted">Results for this exam are not yet available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    @if($examResults->count() > 0)
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Performance Summary</h5>
                </div>
                <div class="card-body">
                    <canvas id="subjectChart" width="400" height="200"></canvas>
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
                    @php
                        $gradeDistribution = $examResults->groupBy('grade')->map->count();
                    @endphp
                    
                    @foreach($gradeDistribution as $grade => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-{{ $this->getGradeColor($grade) }} fs-6">{{ $grade }}</span>
                            <span class="fw-bold">{{ $count }} subject(s)</span>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-{{ $this->getGradeColor($grade) }}" 
                                 style="width: {{ ($count / $examResults->count()) * 100 }}%"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

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
                        <a href="{{ route('student.results.report') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-2"></i>Detailed Report
                        </a>
                        <button type="button" class="btn btn-outline-success" onclick="printResults()">
                            <i class="fas fa-print me-2"></i>Print Results
                        </button>
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

    .subject-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
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
    // Subject Performance Chart
    @if($examResults->count() > 0)
        const ctx = document.getElementById('subjectChart').getContext('2d');
        const subjectChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($examResults->pluck('subject_name')) !!},
                datasets: [{
                    label: 'Percentage',
                    data: {!! json_encode($examResults->pluck('percentage')) !!},
                    backgroundColor: [
                        '#28a745', '#007bff', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#e83e8c'
                    ],
                    borderColor: [
                        '#28a745', '#007bff', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#e83e8c'
                    ],
                    borderWidth: 1
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
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    @endif

    // Print Results
    function printResults() {
        window.print();
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
