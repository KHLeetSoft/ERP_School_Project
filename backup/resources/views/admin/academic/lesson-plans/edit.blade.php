@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-edit me-2 text-primary"></i> Edit Lesson Plan
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.academic.lesson-plans.duplicate', $lessonPlan) }}" class="btn btn-outline-info btn-sm" onclick="return confirm('Duplicate this lesson plan?')">
                    <i class="fas fa-copy me-1"></i> Duplicate
                </a>
                <a href="{{ route('admin.academic.lesson-plans.show', $lessonPlan) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-eye me-1"></i> View
                </a>
                <a href="{{ route('admin.academic.lesson-plans.index') }}" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <h6 class="alert-heading">Please fix the following errors:</h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.academic.lesson-plans.update', $lessonPlan) }}">
                @method('PUT')
                @include('admin.academic.lesson-plans._form', ['lessonPlan' => $lessonPlan])
            </form>
        </div>
    </div>
</div>
@endsection
