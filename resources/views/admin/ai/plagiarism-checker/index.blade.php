@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:.5rem;">
                        <i class="bx bx-shield-quarter fs-4 text-warning"></i>
                        <h5 class="mb-0">AI Plagiarism Checker</h5>
                    </div>
                    <div class="d-flex" style="gap:.5rem;">
                        <a href="{{ route('admin.ai.plagiarism-checker.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-history"></i> History
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('admin.ai.plagiarism-checker.check') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Assignment Title (Optional)</label>
                                    <input type="text" name="assignment_title" class="form-control" value="{{ old('assignment_title') }}" placeholder="e.g., Essay on Climate Change">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Student Name (Optional)</label>
                                    <input type="text" name="student_name" class="form-control" value="{{ old('student_name') }}" placeholder="e.g., John Doe">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Check Type</label>
                            <select name="check_type" class="form-select" required>
                                <option value="general" {{ old('check_type') === 'general' ? 'selected' : '' }}>General Text</option>
                                <option value="academic" {{ old('check_type') === 'academic' ? 'selected' : '' }}>Academic Paper</option>
                                <option value="technical" {{ old('check_type') === 'technical' ? 'selected' : '' }}>Technical Document</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Upload File</label>
                                    <input type="file" name="upload_file" class="form-control" accept=".txt,.pdf,.doc,.docx">
                                    <div class="form-text">Supported: TXT, PDF, DOC, DOCX (Max: 10MB)</div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center justify-content-center">
                                <div class="text-center text-muted">
                                    <strong>OR</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Paste Text Content</label>
                            <textarea name="text_content" class="form-control" rows="10" placeholder="Paste the text content you want to check for plagiarism...">{{ old('text_content') }}</textarea>
                            <div class="form-text">Maximum 50,000 characters</div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bx bx-search-alt"></i> Check for Plagiarism
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(!empty($history))
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Recent Checks</h6>
                </div>
                <div class="card-body">
                    @foreach(array_slice($history, -5) as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <strong>{{ $item['assignment_title'] ?: 'Untitled' }}</strong>
                                @if($item['student_name'])
                                    <span class="text-muted">by {{ $item['student_name'] }}</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ $item['timestamp'] }}</small>
                            </div>
                            <div class="text-end">
                                @if($item['result'])
                                    <span class="badge {{ $item['result']['risk_level'] === 'high' ? 'bg-danger' : ($item['result']['risk_level'] === 'medium' ? 'bg-warning' : 'bg-success') }}">
                                        {{ $item['result']['plagiarism_score'] ?? 0 }}% Risk
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Error</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    // Auto-clear the other input when one is filled
    $('input[name="upload_file"]').change(function(){
        if(this.files.length > 0){
            $('textarea[name="text_content"]').val('');
        }
    });
    
    $('textarea[name="text_content"]').on('input', function(){
        if($(this).val().trim().length > 0){
            $('input[name="upload_file"]').val('');
        }
    });
});
</script>
@endsection
