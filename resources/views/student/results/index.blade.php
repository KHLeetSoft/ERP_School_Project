@extends('student.layout.app')

@section('title', 'My Results')
@section('page-title', 'Results')

@section('content')
<div class="row">
    <!-- Results Statistics -->
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
                            <h4 class="mb-0">{{ $stats['total_exams'] }}</h4>
                            <small class="text-muted">{{ $stats['total_subjects'] }} subjects</small>
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
                            <h4 class="mb-0">{{ $stats['average_percentage'] }}%</h4>
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
                            <h6 class="mb-0">Passed</h6>
                            <h4 class="mb-0">{{ $stats['pass_count'] }}</h4>
                            <small class="text-muted">{{ $stats['pass_percentage'] }}% pass rate</small>
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
                            <h4 class="mb-0">{{ $stats['highest_percentage'] }}%</h4>
                            <small class="text-muted">Highest percentage</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('student.results.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <select name="academic_year" id="academic_year" class="form-select">
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $academicYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="exam_type" class="form-label">Exam Type</label>
                        <select name="exam_type" id="exam_type" class="form-select">
                            <option value="all" {{ $examType == 'all' ? 'selected' : '' }}>All Exams</option>
                            @foreach($examTypes as $type)
                                <option value="{{ $type }}" {{ $examType == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('student.results.report') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-1"></i>Report
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results List -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Exam Results</h5>
            </div>
            <div class="card-body">
                @if($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Exam</th>
                                    <th>Subject</th>
                                    <th>Marks Obtained</th>
                                    <th>Total Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $result->exam->title ?? 'N/A' }}</strong>
                                                @if($result->exam)
                                                    <br><small class="text-muted">{{ ucfirst($result->exam->exam_type) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $result->subject_name }}</td>
                                        <td>{{ $result->obtained_marks }}</td>
                                        <td>{{ $result->max_marks }}</td>
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
                                        <td>{{ \Carbon\Carbon::parse($result->created_at)->format('M d, Y') }}</td>
                                        <td>
                                            @if($result->exam)
                                                <a href="{{ route('student.results.show', ['exam_id' => $result->exam->id]) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
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
                        <h4 class="mt-3 text-muted">No Results Found</h4>
                        <p class="text-muted">No exam results found for the selected criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    @if($results->count() > 0)
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

        <!-- Subject Performance -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject Performance</h5>
                </div>
                <div class="card-body">
                    @php
                        $subjectStats = $results->groupBy('subject_name')->map(function ($subjectResults) {
                            $totalMarks = $subjectResults->sum('max_marks');
                            $obtainedMarks = $subjectResults->sum('obtained_marks');
                            $averagePercentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
                            return [
                                'subject' => $subjectResults->first()->subject_name,
                                'average_percentage' => $averagePercentage,
                                'total_exams' => $subjectResults->count(),
                            ];
                        })->sortByDesc('average_percentage');
                    @endphp
                    
                    @foreach($subjectStats as $subjectStat)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">{{ $subjectStat['subject'] }}</span>
                                <span class="fw-bold">{{ $subjectStat['average_percentage'] }}%</span>
                            </div>
                            <div class="progress mb-1">
                                <div class="progress-bar bg-{{ $subjectStat['average_percentage'] >= 80 ? 'success' : ($subjectStat['average_percentage'] >= 60 ? 'warning' : 'danger') }}" 
                                     style="width: {{ $subjectStat['average_percentage'] }}%"></div>
                            </div>
                            <small class="text-muted">{{ $subjectStat['total_exams'] }} exam(s)</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.results.report') }}" class="btn btn-outline-info">
                            <i class="fas fa-chart-bar me-2"></i>Detailed Report
                        </a>
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

    .progress {
        height: 8px;
        border-radius: 4px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Performance Chart
    @if($results->count() > 0)
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($results->pluck('subject_name')->unique()->values()) !!},
                datasets: [{
                    label: 'Percentage',
                    data: {!! json_encode($results->groupBy('subject_name')->map(function($subjectResults) {
                        $totalMarks = $subjectResults->sum('max_marks');
                        $obtainedMarks = $subjectResults->sum('obtained_marks');
                        return $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
                    })->values()) !!},
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
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    @endif
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
