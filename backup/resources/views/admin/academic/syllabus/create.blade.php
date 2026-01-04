@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Add Syllabus</h4>
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('admin.academic.syllabus.store') }}">
        @include('admin.academic.syllabus._form')
    </form>
@endsection


