@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Edit Syllabus</h4>
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('admin.academic.syllabus.update', $syllabus) }}">
        @method('PUT')
        @include('admin.academic.syllabus._form', ['syllabus' => $syllabus])
    </form>
</div>
@endsection


