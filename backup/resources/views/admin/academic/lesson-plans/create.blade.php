@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="fas fa-plus-circle me-2 text-primary"></i> Create New Lesson Plan
            </h4>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.academic.lesson-plans.store') }}">
                @include('admin.academic.lesson-plans._form')
            </form>
        </div>
    </div>
</div>
@endsection
