@extends('student.layout.app')

@section('title', 'Academic Transcript')
@section('page-title', 'Academic Transcript')

@section('content')
<div class="row">
    <!-- Transcript Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="mb-3">Academic Transcript</h3>
                <h5 class="text-muted mb-2">{{ $transcript['academic_year'] }} Academic Year</h5>
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar me-1"></i>
                    Generated on {{ $transcript['generated_at']->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Student Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Name:</td>
                                <td>{{ $transcript['student']->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Class:</td>
                                <td>{{ $transcript['student']->class_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Roll No:</td>
                                <td>{{ $transcript['student']->roll_no ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Admission No:</td>
                                <td>{{ $transcript['student']->admission_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Academic Year:</td>
                                <td>{{ $transcript['academic_year'] }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
                                <td>
                                    @if($transcript['overall_stats']['pass_percentage'] >= 50)
                                        <span class="badge bg-success">Pass</span>
                                    @else
                                        <span class="badge bg-danger">Fail</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Performance -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Overall Performance</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="performance-card">
                            <div class="performance-icon bg-primary">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Total Exams</h6>
                                <h4>{{ $transcript['overall_stats']['total_exams'] }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="performance-card">
                            <div class="performance-icon bg-success">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Average %</h6>
                                <h4>{{ $transcript['overall_stats']['average_percentage'] }}%</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="performance-card">
                            <div class="performance-icon bg-info">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Pass Rate</h6>
                                <h4>{{ $transcript['overall_stats']['pass_percentage'] }}%</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="performance-card">
                            <div class="performance-icon bg-warning">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="performance-content">
                                <h6>Best Score</h6>
                                <h4>{{ $transcript['overall_stats']['highest_percentage'] }}%</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject-wise Results -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Subject-wise Results</h5>
            </div>
            <div class="card-body">
                @if($transcript['subject_groups']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Total Exams</th>
                                    <th>Total Marks</th>
                                    <th>Obtained Marks</th>
                                    <th>Average %</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transcript['subject_groups'] as $subjectName => $subjectResults)
                                    @php
                                        $totalMarks = $subjectResults->sum('max_marks');
                                        $obtainedMarks = $subjectResults->sum('obtained_marks');
                                        $averagePercentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;
                                        $grade = $this->calculateGrade($averagePercentage);
                                        $passCount = $subjectResults->where('result_status', 'pass')->count();
                                        $totalCount = $subjectResults->count();
                                        $status = $passCount > ($totalCount / 2) ? 'Pass' : 'Fail';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="subject-icon me-3">
                                                    <i class="fas fa-book text-primary"></i>
                                                </div>
                                                <strong>{{ $subjectName }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $totalCount }}</td>
                                        <td>{{ $totalMarks }}</td>
                                        <td>{{ $obtainedMarks }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-{{ $averagePercentage >= 80 ? 'success' : ($averagePercentage >= 60 ? 'warning' : 'danger') }} me-2">
                                                    {{ $averagePercentage }}%
                                                </span>
                                                <div class="progress" style="width: 80px; height: 6px;">
                                                    <div class="progress-bar bg-{{ $averagePercentage >= 80 ? 'success' : ($averagePercentage >= 60 ? 'warning' : 'danger') }}" 
                                                         style="width: {{ $averagePercentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $this->getGradeColor($grade) }} fs-6">
                                                {{ $grade }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($status === 'Pass')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Pass
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>Fail
                                                </span>
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
                        <p class="text-muted">No academic results found for the selected year.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detailed Exam Results -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detailed Exam Results</h5>
            </div>
            <div class="card-body">
                @if($transcript['subject_groups']->count() > 0)
                    @foreach($transcript['subject_groups'] as $subjectName => $subjectResults)
                        <div class="subject-detail mb-4">
                            <h6 class="subject-title">
                                <i class="fas fa-book me-2"></i>{{ $subjectName }}
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Exam</th>
                                            <th>Type</th>
                                            <th>Marks Obtained</th>
                                            <th>Total Marks</th>
                                            <th>Percentage</th>
                                            <th>Grade</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjectResults as $result)
                                            <tr>
                                                <td>{{ $result->exam->title ?? 'N/A' }}</td>
                                                <td>
                                                    @if($result->exam)
                                                        <span class="badge bg-secondary">{{ ucfirst($result->exam->exam_type) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Transcript Footer -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-md-4">
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-label">Class Teacher</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-label">Principal</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <p class="signature-label">Date</p>
                        </div>
                    </div>
                </div>
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
                        <button type="button" class="btn btn-outline-primary" onclick="printTranscript()">
                            <i class="fas fa-print me-2"></i>Print Transcript
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="exportTranscript()">
                            <i class="fas fa-download me-2"></i>Export PDF
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
    .performance-card {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        border-left: 4px solid #007bff;
    }

    .performance-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        margin-right: 1rem;
    }

    .performance-content h6 {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .performance-content h4 {
        margin: 0;
        font-weight: bold;
    }

    .subject-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .subject-detail {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .subject-title {
        color: #007bff;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .signature-section {
        margin: 1rem 0;
    }

    .signature-line {
        width: 100%;
        height: 2px;
        background-color: #000;
        margin-bottom: 0.5rem;
    }

    .signature-label {
        margin: 0;
        font-weight: bold;
        color: #6c757d;
    }

    .progress {
        height: 6px;
        border-radius: 3px;
    }

    @media print {
        .btn, .card-header, .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .page-break {
            page-break-before: always;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Print Transcript
    function printTranscript() {
        window.print();
    }

    // Export Transcript (placeholder)
    function exportTranscript() {
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

    function calculateGrade($percentage) {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        return 'F';
    }
@endphp
