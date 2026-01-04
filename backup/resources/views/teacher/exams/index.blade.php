@extends('teacher.layout.app')

@section('title', 'Exams Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-graduation-cap"></i> Exams Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Exam
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Exam Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $examStats['total_exams'] }}</h3>
                                    <p>Total Exams</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $examStats['active_exams'] }}</h3>
                                    <p>Active Exams</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $examStats['upcoming_exams'] }}</h3>
                                    <p>Upcoming Exams</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ $examStats['completed_exams'] }}</h3>
                                    <p>Completed Exams</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-primary {{ request()->is('teacher/exams') && !request()->has('status') ? 'active' : '' }}">
                                    All Exams
                                </a>
                                <a href="{{ route('teacher.exams.active') }}" class="btn btn-outline-success">
                                    Active
                                </a>
                                <a href="{{ route('teacher.exams.upcoming') }}" class="btn btn-outline-warning">
                                    Upcoming
                                </a>
                                <a href="{{ route('teacher.exams.completed') }}" class="btn btn-outline-secondary">
                                    Completed
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Exams Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Academic Year</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($exams as $exam)
                                <tr>
                                    <td>{{ $exam->id }}</td>
                                    <td>
                                        <strong>{{ $exam->title }}</strong>
                                        @if($exam->description)
                                        <br><small class="text-muted">{{ Str::limit($exam->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $exam->exam_type ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $exam->academic_year ?? 'N/A' }}</td>
                                    <td>
                                        @if($exam->start_date)
                                            {{ $exam->start_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($exam->end_date)
                                            {{ $exam->end_date->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($exam->status)
                                            @case('scheduled')
                                                <span class="badge badge-success">Scheduled</span>
                                                @break
                                            @case('completed')
                                                <span class="badge badge-secondary">Completed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">Cancelled</span>
                                                @break
                                            @case('draft')
                                                <span class="badge badge-warning">Draft</span>
                                                @break
                                            @default
                                                <span class="badge badge-light">{{ ucfirst($exam->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher.exams.show', $exam) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('teacher.exams.schedules', $exam) }}" class="btn btn-primary btn-sm" title="Schedules">
                                                <i class="fas fa-calendar"></i>
                                            </a>
                                            <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-success btn-sm" title="Results">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                            <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this exam?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                                        <br>No exams found. Create your first exam to get started.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($exams->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $exams->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh page every 5 minutes for active exams
    setInterval(function() {
        if (window.location.href.includes('teacher/exams')) {
            location.reload();
        }
    }, 300000); // 5 minutes
});
</script>
@endpush
