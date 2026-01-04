@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:.5rem;">
                        <i class="bx bx-shield-quarter fs-4 text-warning"></i>
                        <h5 class="mb-0">Plagiarism Check Results</h5>
                    </div>
                    <div class="d-flex" style="gap:.5rem;">
                        <a href="{{ route('admin.ai.plagiarism-checker.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> New Check
                        </a>
                        <a href="{{ route('admin.ai.plagiarism-checker.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-history"></i> History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($error)
                        <div class="alert alert-danger">
                            <i class="bx bx-error-circle"></i> {{ $error }}
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Plagiarism Score -->
                                <div class="card border-0 bg-light mb-4">
                                    <div class="card-body text-center">
                                        <h2 class="display-4 mb-2 {{ $result['risk_level'] === 'high' ? 'text-danger' : ($result['risk_level'] === 'medium' ? 'text-warning' : 'text-success') }}">
                                            {{ $result['plagiarism_score'] ?? 0 }}%
                                        </h2>
                                        <p class="h5 mb-3">Plagiarism Risk Score</p>
                                        <span class="badge fs-6 {{ $result['risk_level'] === 'high' ? 'bg-danger' : ($result['risk_level'] === 'medium' ? 'bg-warning' : 'bg-success') }}">
                                            {{ ucfirst($result['risk_level'] ?? 'unknown') }} Risk
                                        </span>
                                    </div>
                                </div>

                                <!-- Summary -->
                                @if(!empty($result['summary']))
                                <div class="card border-0 mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="bx bx-file-blank"></i> Analysis Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $result['summary'] }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Issues Found -->
                                @if(!empty($result['issues_found']))
                                <div class="card border-0 mb-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="bx bx-error-circle"></i> Issues Detected</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($result['issues_found'] as $issue)
                                                <li class="mb-2">
                                                    <i class="bx bx-x-circle text-danger"></i> {{ $issue }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif

                                <!-- Recommendations -->
                                @if(!empty($result['recommendations']))
                                <div class="card border-0 mb-4">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="bx bx-bulb"></i> Recommendations</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($result['recommendations'] as $recommendation)
                                                <li class="mb-2">
                                                    <i class="bx bx-check-circle text-success"></i> {{ $recommendation }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <!-- Submission Details -->
                                <div class="card border-0 mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bx bx-info-circle"></i> Submission Details</h6>
                                    </div>
                                    <div class="card-body">
                                        @if(!empty($input['assignment_title']))
                                            <p><strong>Assignment:</strong><br>{{ $input['assignment_title'] }}</p>
                                        @endif
                                        @if(!empty($input['student_name']))
                                            <p><strong>Student:</strong><br>{{ $input['student_name'] }}</p>
                                        @endif
                                        <p><strong>Check Type:</strong><br>{{ ucfirst($input['check_type']) }}</p>
                                        @if($fileName)
                                            <p><strong>File:</strong><br>{{ $fileName }}</p>
                                        @endif
                                        <p><strong>Checked:</strong><br>{{ now()->format('Y-m-d H:i') }}</p>
                                        <p><strong>Content Length:</strong><br>{{ strlen($contentPreview) }} characters</p>
                                    </div>
                                </div>

                                <!-- Content Preview -->
                                <div class="card border-0">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bx bx-file-blank"></i> Content Preview</h6>
                                    </div>
                                    <div class="card-body">
                                        <div style="max-height: 200px; overflow-y: auto; font-size: 0.85rem; line-height: 1.4;">
                                            {{ $contentPreview }}@if(strlen($contentPreview) >= 500)...@endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
