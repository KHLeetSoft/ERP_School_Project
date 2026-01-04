@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center" style="gap:.5rem;">
                <i class="bx bx-history fs-4 text-info"></i>
                <h5 class="mb-0">Plagiarism Check History</h5>
            </div>
            <div class="d-flex" style="gap:.5rem;">
                <a href="{{ route('admin.ai.plagiarism-checker.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bx bx-arrow-back"></i> New Check
                </a>
                @if(!empty($history))
                <form action="{{ route('admin.ai.plagiarism-checker.clear-history') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Clear all history?')">
                        <i class="bx bx-trash"></i> Clear History
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @forelse($history as $item)
                <div class="card border mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $item['assignment_title'] ?: 'Untitled Check' }}</h6>
                            <small class="text-muted">
                                {{ $item['timestamp'] }}
                                @if($item['student_name'])
                                    • Student: {{ $item['student_name'] }}
                                @endif
                                @if($item['file_name'])
                                    • File: {{ $item['file_name'] }}
                                @endif
                                • Type: {{ ucfirst($item['check_type']) }}
                            </small>
                        </div>
                        <div>
                            @if($item['result'])
                                <span class="badge fs-6 {{ $item['result']['risk_level'] === 'high' ? 'bg-danger' : ($item['result']['risk_level'] === 'medium' ? 'bg-warning' : 'bg-success') }}">
                                    {{ $item['result']['plagiarism_score'] ?? 0 }}% • {{ ucfirst($item['result']['risk_level'] ?? 'unknown') }} Risk
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">Error</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                @if($item['error'])
                                    <div class="alert alert-danger mb-3">
                                        <i class="bx bx-error-circle"></i> {{ $item['error'] }}
                                    </div>
                                @endif

                                @if($item['result'])
                                    @if(!empty($item['result']['summary']))
                                        <p class="mb-2"><strong>Summary:</strong> {{ $item['result']['summary'] }}</p>
                                    @endif

                                    @if(!empty($item['result']['issues_found']))
                                        <div class="mb-2">
                                            <strong>Issues Found:</strong>
                                            <ul class="small mb-0 mt-1">
                                                @foreach($item['result']['issues_found'] as $issue)
                                                    <li>{{ $issue }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($item['result']['recommendations']))
                                        <div class="mb-2">
                                            <strong>Recommendations:</strong>
                                            <ul class="small mb-0 mt-1">
                                                @foreach($item['result']['recommendations'] as $rec)
                                                    <li>{{ $rec }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light p-2 rounded">
                                    <strong class="small">Content Preview:</strong>
                                    <div class="small text-muted mt-1" style="max-height: 80px; overflow-y: auto;">
                                        {{ $item['content_preview'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bx bx-search fs-1 d-block mb-2"></i>
                    <p>No plagiarism checks found.</p>
                    <a href="{{ route('admin.ai.plagiarism-checker.index') }}" class="btn btn-warning">
                        <i class="bx bx-plus"></i> Run Your First Check
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
