@extends('student.layout.app')

@section('title', 'Assignment History')
@section('page-title', 'Assignment History')

@section('content')
<div class="row">
    <!-- Submission Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Total Submissions</h6>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            <small class="text-muted">All submissions</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Graded</h6>
                            <h4 class="mb-0">{{ $stats['graded'] }}</h4>
                            <small class="text-muted">Evaluated</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Pending</h6>
                            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                            <small class="text-muted">Awaiting evaluation</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">Average Grade</h6>
                            <h4 class="mb-0">{{ $stats['average_grade'] }}</h4>
                            <small class="text-muted">Overall performance</small>
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
                <form method="GET" action="{{ route('student.assignments.history') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="submitted" {{ $status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="graded" {{ $status == 'graded' ? 'selected' : '' }}>Graded</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="subject" class="form-label">Subject</label>
                        <select name="subject" id="subject" class="form-select">
                            <option value="all" {{ $subject == 'all' ? 'selected' : '' }}>All Subjects</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}" {{ $subject == $subj->id ? 'selected' : '' }}>{{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Assignments
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Submissions List -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Submission History</h5>
            </div>
            <div class="card-body">
                @if($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Subject</th>
                                    <th>Submitted</th>
                                    <th>Status</th>
                                    <th>Grade</th>
                                    <th>Feedback</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $submission->assignment->title }}</strong>
                                                <br><small class="text-muted">{{ $submission->assignment->teacher->name ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $submission->assignment->subject->name ?? 'N/A' }}</td>
                                        <td>
                                            <div>
                                                {{ \Carbon\Carbon::parse($submission->submitted_at)->format('M d, Y') }}
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($submission->submitted_at)->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($submission->status === 'graded')
                                                <span class="badge bg-success">Graded</span>
                                            @else
                                                <span class="badge bg-warning">Submitted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission->grade !== null)
                                                <div>
                                                    <strong>{{ $submission->grade }}/{{ $submission->assignment->max_marks ?? 'N/A' }}</strong>
                                                    <br><small class="text-muted">{{ $submission->grade_percentage }}%</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission->feedback)
                                                <span class="text-success">
                                                    <i class="fas fa-comment"></i> Available
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('student.assignments.show', $submission->assignment->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($submission->submission_file)
                                                    <a href="{{ route('student.assignments.download', $submission->id) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Submissions Found</h4>
                        <p class="text-muted">No assignment submissions found for the selected criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Chart -->
    @if($submissions->where('grade', '!=', null)->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Performance Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    @endif

    <!-- Subject Performance -->
    @if($submissions->count() > 0)
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject Performance</h5>
                </div>
                <div class="card-body">
                    @php
                        $subjectStats = $submissions->where('grade', '!=', null)->groupBy(function($submission) {
                            return $submission->assignment->subject->name ?? 'Unknown';
                        })->map(function ($subjectSubmissions) {
                            $grades = $subjectSubmissions->pluck('grade');
                            return [
                                'subject' => $subjectSubmissions->first()->assignment->subject->name ?? 'Unknown',
                                'count' => $subjectSubmissions->count(),
                                'average' => $grades->avg(),
                                'highest' => $grades->max(),
                                'lowest' => $grades->min(),
                            ];
                        })->sortByDesc('average');
                    @endphp
                    
                    <div class="row">
                        @foreach($subjectStats as $subjectStat)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="subject-performance-card">
                                    <h6 class="subject-name">{{ $subjectStat['subject'] }}</h6>
                                    <div class="performance-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">Average:</span>
                                            <span class="stat-value">{{ round($subjectStat['average'], 2) }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Highest:</span>
                                            <span class="stat-value">{{ $subjectStat['highest'] }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Lowest:</span>
                                            <span class="stat-value">{{ $subjectStat['lowest'] }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">Submissions:</span>
                                            <span class="stat-value">{{ $subjectStat['count'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                        <a href="{{ route('student.assignments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Assignments
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('student.assignments.index', ['status' => 'pending']) }}" class="btn btn-outline-warning">
                            <i class="fas fa-clock me-2"></i>Pending Assignments
                        </a>
                        <a href="{{ route('student.assignments.index', ['status' => 'submitted']) }}" class="btn btn-outline-success">
                            <i class="fas fa-check me-2"></i>Submitted Assignments
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

    .subject-performance-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        border-left: 4px solid #007bff;
    }

    .subject-name {
        color: #007bff;
        margin-bottom: 1rem;
        font-weight: bold;
    }

    .performance-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .stat-value {
        font-weight: bold;
        color: #333;
    }
</style>
@endsection

@section('scripts')
<script>
    // Performance Chart
    @if($submissions->where('grade', '!=', null)->count() > 0)
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($submissions->where('grade', '!=', null)->pluck('assignment.title')) !!},
                datasets: [{
                    label: 'Grade',
                    data: {!! json_encode($submissions->where('grade', '!=', null)->pluck('grade')) !!},
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
                        beginAtZero: true
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
