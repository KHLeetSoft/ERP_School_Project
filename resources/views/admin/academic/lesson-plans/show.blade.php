@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-eye me-2 text-primary"></i> Lesson Plan Details
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.academic.lesson-plans.edit', $lessonPlan) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('admin.academic.lesson-plans.duplicate', $lessonPlan) }}" class="btn btn-info btn-sm" onclick="return confirm('Duplicate this lesson plan?')">
                    <i class="fas fa-copy me-1"></i> Duplicate
                </a>
                <a href="{{ route('admin.academic.lesson-plans.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    {{-- Basic Information --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i> Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong>
                            <p class="mb-0">{{ optional($lessonPlan->subject)->name }} ({{ optional($lessonPlan->subject)->code }})</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Syllabus:</strong>
                            <p class="mb-0">{{ optional($lessonPlan->syllabus)->title ?? 'Not linked' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Lesson Number:</strong>
                            <p class="mb-0">{{ $lessonPlan->lesson_number ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Unit Number:</strong>
                            <p class="mb-0">{{ $lessonPlan->unit_number ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-12">
                            <strong>Title:</strong>
                            <p class="mb-0 h5 text-primary">{{ $lessonPlan->title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-success"></i> Status & Progress
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-{{ $lessonPlan->progress_percentage == 100 ? 'success' : ($lessonPlan->progress_percentage > 50 ? 'warning' : 'info') }}" 
                                 style="width: {{ $lessonPlan->progress_percentage }}%">
                                {{ $lessonPlan->progress_percentage }}%
                            </div>
                        </div>
                        <small class="text-muted">Completion Progress</small>
                    </div>
                    
                    <div class="mb-3">
                        {!! $lessonPlan->status_badge !!}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Difficulty:</strong><br>
                        <span class="badge bg-{{ $lessonPlan->difficulty_level == 1 ? 'success' : ($lessonPlan->difficulty_level == 2 ? 'warning' : 'danger') }}">
                            {{ $lessonPlan->difficulty_text }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Duration:</strong><br>
                        <span class="text-primary">{{ $lessonPlan->duration_text }}</span>
                    </div>
                    
                    @if($lessonPlan->estimated_student_count)
                    <div class="mb-3">
                        <strong>Estimated Students:</strong><br>
                        <span class="text-info">{{ $lessonPlan->estimated_student_count }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Learning Objectives --}}
    @if($lessonPlan->learning_objectives_list)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-bullseye me-2 text-success"></i> Learning Objectives
            </h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($lessonPlan->learning_objectives_list as $objective)
                    <li class="list-group-item">
                        <i class="fas fa-check-circle me-2 text-success"></i>{{ $objective }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Prerequisites --}}
    @if($lessonPlan->prerequisites_list)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list-check me-2 text-warning"></i> Prerequisites
            </h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($lessonPlan->prerequisites_list as $prerequisite)
                    <li class="list-group-item">
                        <i class="fas fa-arrow-right me-2 text-warning"></i>{{ $prerequisite }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- Materials and Resources --}}
    @if($lessonPlan->materials_list)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-tools me-2 text-info"></i> Materials and Resources
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($lessonPlan->materials_list as $material)
                    <div class="col-md-6 mb-2">
                        <span class="badge bg-light text-dark border">
                            <i class="fas fa-box me-1"></i>{{ $material }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Teaching Methods and Activities --}}
    <div class="row mb-4">
        @if($lessonPlan->teaching_methods_list)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chalkboard me-2 text-success"></i> Teaching Methods
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($lessonPlan->teaching_methods_list as $method)
                            <li class="list-group-item">
                                <i class="fas fa-play me-2 text-success"></i>{{ $method }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        
        @if($lessonPlan->activities_list)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-play-circle me-2 text-warning"></i> Activities
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($lessonPlan->activities_list as $activity)
                            <li class="list-group-item">
                                <i class="fas fa-star me-2 text-warning"></i>{{ $activity }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Assessment Methods --}}
    @if($lessonPlan->assessment_methods_list)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-check me-2 text-info"></i> Assessment Methods
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($lessonPlan->assessment_methods_list as $method)
                    <div class="col-md-6 mb-2">
                        <span class="badge bg-info text-white">
                            <i class="fas fa-check me-1"></i>{{ $method }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Additional Information --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2 text-secondary"></i> Additional Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($lessonPlan->homework)
                    <div class="mb-3">
                        <strong>Homework:</strong>
                        <p class="mb-0">{{ $lessonPlan->homework }}</p>
                    </div>
                    @endif
                    
                    @if($lessonPlan->notes)
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p class="mb-0">{{ $lessonPlan->notes }}</p>
                    </div>
                    @endif
                    
                    @if($lessonPlan->room_requirements)
                    <div class="mb-3">
                        <strong>Room Requirements:</strong>
                        <p class="mb-0">{{ $lessonPlan->room_requirements }}</p>
                    </div>
                    @endif
                    
                    @if($lessonPlan->technology_needed)
                    <div class="mb-3">
                        <strong>Technology Needed:</strong>
                        <p class="mb-0">{{ $lessonPlan->technology_needed }}</p>
                    </div>
                    @endif
                    
                    @if($lessonPlan->special_considerations)
                    <div class="mb-3">
                        <strong>Special Considerations:</strong>
                        <p class="mb-0">{{ $lessonPlan->special_considerations }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i> Scheduling
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Planned Date:</strong>
                        <p class="mb-0">{{ $lessonPlan->formatted_planned_date }}</p>
                    </div>
                    
                    @if($lessonPlan->actual_date)
                    <div class="mb-3">
                        <strong>Actual Date:</strong>
                        <p class="mb-0">{{ $lessonPlan->formatted_actual_date }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <p class="mb-0">{!! $lessonPlan->status_badge !!}</p>
                    </div>
                    
                    @if($lessonPlan->is_overdue)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This lesson plan is overdue!
                    </div>
                    @endif
                    
                    @if($lessonPlan->is_upcoming)
                    <div class="alert alert-info">
                        <i class="fas fa-calendar-day me-2"></i>
                        This lesson plan is upcoming.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
