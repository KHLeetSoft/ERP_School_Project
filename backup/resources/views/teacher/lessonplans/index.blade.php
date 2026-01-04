@extends('teacher.layout.app')

@section('title', 'Lesson Plans')
@section('page-title', 'Lesson Plans')
@section('page-description', 'Create and manage lesson plans with notes and attachments')

@section('content')
<div class="card modern-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Lesson Plans</h5>
        <a href="#" class="btn btn-primary" onclick="alert('Coming soon: full CRUD with attachments');">
            <i class="fas fa-plus me-1"></i>New Lesson Plan
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-0">This is a placeholder page. We will add full lesson plan CRUD, subject link, and file attachments next.</div>
    </div>
 </div>
@endsection


