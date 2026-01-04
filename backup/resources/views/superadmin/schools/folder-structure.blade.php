@extends('superadmin.layout.app')

@section('title', 'School Folder Structure')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Folder Structure for {{ $school->name }}</h4>
                    <div class="card-tools">
                        <a href="{{ route('superadmin.schools.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Schools
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>School Folders</h5>
                            <div class="folder-tree">
                                <ul class="tree">
                                    <li>
                                        <i class="bx bx-folder"></i> 
                                        <strong>{{ $school->name }}</strong>
                                        <ul>
                                            <li><i class="bx bx-folder"></i> logo</li>
                                            <li><i class="bx bx-folder"></i> students</li>
                                            <li><i class="bx bx-folder"></i> documents</li>
                                            <li><i class="bx bx-folder"></i> resources</li>
                                            <li><i class="bx bx-folder"></i> reports</li>
                                            <li><i class="bx bx-folder"></i> backups</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Student Folders</h5>
                            @if($students->count() > 0)
                                <div class="folder-tree">
                                    <ul class="tree">
                                        <li>
                                            <i class="bx bx-folder"></i> 
                                            <strong>Students</strong>
                                            <ul>
                                                @foreach($students as $student)
                                                    <li>
                                                        <i class="bx bx-folder"></i> 
                                                        {{ $student->name }} ({{ $student->admission_no ?? 'No Admission No' }})
                                                        <ul>
                                                            <li><i class="bx bx-folder"></i> documents</li>
                                                            <li><i class="bx bx-folder"></i> assignments</li>
                                                            <li><i class="bx bx-folder"></i> submissions</li>
                                                            <li><i class="bx bx-folder"></i> profile</li>
                                                            <li><i class="bx bx-folder"></i> certificates</li>
                                                            <li><i class="bx bx-folder"></i> reports</li>
                                                            <li><i class="bx bx-folder"></i> photos</li>
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <p class="text-muted">No students found for this school.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Folder Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="bx bx-folder"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Folders</span>
                                        <span class="info-box-number">{{ 7 + ($students->count() * 8) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="bx bx-user"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Students</span>
                                        <span class="info-box-number">{{ $students->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="bx bx-hdd"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Storage Used</span>
                                        <span class="info-box-number">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.folder-tree {
    font-family: 'Courier New', monospace;
}

.tree {
    list-style: none;
    padding-left: 0;
}

.tree li {
    margin: 5px 0;
}

.tree ul {
    list-style: none;
    padding-left: 20px;
    margin: 5px 0;
}

.tree i {
    margin-right: 5px;
    color: #007bff;
}

.info-box {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 10px;
}

.info-box-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 15px;
}

.info-box-content {
    flex: 1;
}

.info-box-text {
    display: block;
    font-size: 12px;
    color: #6c757d;
}

.info-box-number {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #495057;
}
</style>
@endsection
