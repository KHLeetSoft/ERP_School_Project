@extends('superadmin.app')

@section('title', 'AI Report Generator')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-file-alt me-3"></i>AI Report Generator
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Generate intelligent reports using natural language</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">Smart Analytics</div>
                            <small class="opacity-75">Powered by AI</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Natural Language Query</h5>
                    <p class="text-muted mb-0">Ask questions in plain English to generate reports</p>
                </div>
                <div class="card-body p-4">
                    <form id="reportForm">
                        @csrf
                        <div class="mb-4">
                            <label for="query" class="form-label fw-bold">What would you like to know?</label>
                            <textarea class="form-control" id="query" name="query" rows="4" 
                                placeholder="Example: Show me schools whose plan will expire in 10 days, or What's the revenue trend for last quarter?"></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Quick Examples:</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuery('Show me schools whose plan will expire in 10 days')">
                                        Expiring Plans
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="setQuery('What is the revenue trend for last quarter?')">
                                        Revenue Analysis
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-warning btn-sm w-100" onclick="setQuery('Which schools have the highest user activity?')">
                                        User Activity
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="setQuery('Show me plan upgrade recommendations')">
                                        Upgrade Suggestions
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-magic me-2"></i>Generate Report
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                <i class="fas fa-times me-2"></i>Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">AI Tips</h5>
                    <p class="text-muted mb-0">How to get the best results</p>
                </div>
                <div class="card-body p-4">
                    <div class="ai-tips">
                        <div class="tip-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-lightbulb text-warning me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Be Specific</h6>
                                    <p class="mb-0 small text-muted">Include time periods, specific metrics, or conditions for better results.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tip-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-chart-bar text-primary me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Use Keywords</h6>
                                    <p class="mb-0 small text-muted">Keywords like "revenue", "users", "schools", "plans" work best.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tip-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-clock text-info me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Time Periods</h6>
                                    <p class="mb-0 small text-muted">Specify timeframes like "last month", "this quarter", "next 30 days".</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tip-item mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-filter text-success me-2 mt-1"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Filter Options</h6>
                                    <p class="mb-0 small text-muted">Add filters like "active schools", "premium plans", "high activity".</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Results -->
    @if(isset($result))
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Report Results
                    </h5>
                    <p class="text-muted mb-0">Query: "{{ $query }}"</p>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>{{ $result['title'] }}</h6>
                        <p class="mb-0">{{ $result['message'] }}</p>
                    </div>
                    
                    @if(isset($result['data']) && count($result['data']) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @foreach(array_keys($result['data'][0]) as $header)
                                    <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['data'] as $row)
                                <tr>
                                    @foreach($row as $value)
                                    <td>{{ $value }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function setQuery(query) {
    document.getElementById('query').value = query;
}

function clearForm() {
    document.getElementById('query').value = '';
}

document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const query = document.getElementById('query').value;
    if (!query.trim()) {
        alert('Please enter a query');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
    submitBtn.disabled = true;
    
    // Submit form
    this.submit();
});
</script>
@endsection
