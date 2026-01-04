@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bx bx-bullhorn me-2 text-primary"></i>Edit Result Announcement</h4>
        <div>
            <a href="{{ route('admin.result-announcement.announcement.show', $resultAnnouncement) }}" 
               class="btn btn-outline-info btn-sm me-2">
                <i class="bx bx-show me-1"></i> View
            </a>
            <a href="{{ route('admin.result-announcement.announcement.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.result-announcement.announcement.update', $resultAnnouncement) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">Basic Information</h5>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Announcement Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $resultAnnouncement->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4">{{ old('description', $resultAnnouncement->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="announcement_type" class="form-label">Announcement Type *</label>
                                        <select class="form-select @error('announcement_type') is-invalid @enderror" 
                                                id="announcement_type" name="announcement_type" required>
                                            <option value="">Select Type</option>
                                            <option value="exam_result" {{ old('announcement_type', $resultAnnouncement->announcement_type) == 'exam_result' ? 'selected' : '' }}>Exam Result</option>
                                            <option value="online_exam_result" {{ old('announcement_type', $resultAnnouncement->announcement_type) == 'online_exam_result' ? 'selected' : '' }}>Online Exam Result</option>
                                            <option value="general_result" {{ old('announcement_type', $resultAnnouncement->announcement_type) == 'general_result' ? 'selected' : '' }}>General Result</option>
                                            <option value="merit_list" {{ old('announcement_type', $resultAnnouncement->announcement_type) == 'merit_list' ? 'selected' : '' }}>Merit List</option>
                                        </select>
                                        @error('announcement_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status *</label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="draft" {{ old('status', $resultAnnouncement->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="published" {{ old('status', $resultAnnouncement->status) == 'published' ? 'selected' : '' }}>Published</option>
                                            <option value="archived" {{ old('status', $resultAnnouncement->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exam Selection -->
                        <div class="mb-4" id="exam_selection" style="display: none;">
                            <h5 class="mb-3">Exam Selection</h5>
                            
                            <div class="mb-3" id="regular_exam_div" style="display: none;">
                                <label for="exam_id" class="form-label">Select Exam</label>
                                <select class="form-select @error('exam_id') is-invalid @enderror" 
                                        id="exam_id" name="exam_id">
                                    <option value="">Select Exam</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}" 
                                                {{ old('exam_id', $resultAnnouncement->exam_id) == $exam->id ? 'selected' : '' }}>
                                            {{ $exam->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('exam_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="online_exam_div" style="display: none;">
                                <label for="online_exam_id" class="form-label">Select Online Exam</label>
                                <select class="form-select @error('online_exam_id') is-invalid @enderror" 
                                        id="online_exam_id" name="online_exam_id">
                                    <option value="">Select Online Exam</option>
                                    @foreach($onlineExams as $exam)
                                        <option value="{{ $exam->id }}" 
                                                {{ old('online_exam_id', $resultAnnouncement->online_exam_id) == $exam->id ? 'selected' : '' }}>
                                            {{ $exam->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('online_exam_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Target Audience -->
                        <div class="mb-4">
                            <h5 class="mb-3">Target Audience</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Who should see this announcement?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="target_audience[]" 
                                           value="students" id="target_students" 
                                           {{ in_array('students', old('target_audience', $resultAnnouncement->target_audience ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="target_students">Students</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="target_audience[]" 
                                           value="parents" id="target_parents" 
                                           {{ in_array('parents', old('target_audience', $resultAnnouncement->target_audience ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="target_parents">Parents</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="target_audience[]" 
                                           value="teachers" id="target_teachers" 
                                           {{ in_array('teachers', old('target_audience', $resultAnnouncement->target_audience ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="target_teachers">Teachers</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="class_ids" class="form-label">Specific Classes (Optional)</label>
                                        <select class="form-select" id="class_ids" name="class_ids[]" multiple>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" 
                                                        {{ in_array($class->id, old('class_ids', $resultAnnouncement->class_ids ?? [])) ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Leave empty to target all classes</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="section_ids" class="form-label">Specific Sections (Optional)</label>
                                        <select class="form-select" id="section_ids" name="section_ids[]" multiple>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" 
                                                        {{ in_array($section->id, old('section_ids', $resultAnnouncement->section_ids ?? [])) ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Leave empty to target all sections</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Publishing Options -->
                        <div class="mb-4">
                            <h5 class="mb-3">Publishing Options</h5>
                            
                            <div class="mb-3">
                                <label for="publish_at" class="form-label">Publish Date & Time</label>
                                <input type="datetime-local" class="form-control @error('publish_at') is-invalid @enderror" 
                                       id="publish_at" name="publish_at" 
                                       value="{{ old('publish_at', $resultAnnouncement->publish_at ? $resultAnnouncement->publish_at->format('Y-m-d\TH:i') : '') }}">
                                @error('publish_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty to publish immediately</small>
                            </div>

                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expiry Date & Time</label>
                                <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                       id="expires_at" name="expires_at" 
                                       value="{{ old('expires_at', $resultAnnouncement->expires_at ? $resultAnnouncement->expires_at->format('Y-m-d\TH:i') : '') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave empty for no expiry</small>
                            </div>
                        </div>

                        <!-- Notification Options -->
                        <div class="mb-4">
                            <h5 class="mb-3">Notification Options</h5>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="send_sms" 
                                       value="1" id="send_sms" {{ old('send_sms', $resultAnnouncement->send_sms) ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_sms">Send SMS</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="send_email" 
                                       value="1" id="send_email" {{ old('send_email', $resultAnnouncement->send_email) ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_email">Send Email</label>
                            </div>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="send_push_notification" 
                                       value="1" id="send_push_notification" {{ old('send_push_notification', $resultAnnouncement->send_push_notification) ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_push_notification">Send Push Notification</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Update Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const announcementType = document.getElementById('announcement_type');
    const examSelection = document.getElementById('exam_selection');
    const regularExamDiv = document.getElementById('regular_exam_div');
    const onlineExamDiv = document.getElementById('online_exam_div');
    const examId = document.getElementById('exam_id');
    const onlineExamId = document.getElementById('online_exam_id');

    function toggleExamSelection() {
        const type = announcementType.value;
        
        if (type === 'exam_result') {
            examSelection.style.display = 'block';
            regularExamDiv.style.display = 'block';
            onlineExamDiv.style.display = 'none';
            examId.required = true;
            onlineExamId.required = false;
        } else if (type === 'online_exam_result') {
            examSelection.style.display = 'block';
            regularExamDiv.style.display = 'none';
            onlineExamDiv.style.display = 'block';
            examId.required = false;
            onlineExamId.required = true;
        } else {
            examSelection.style.display = 'none';
            regularExamDiv.style.display = 'none';
            onlineExamDiv.style.display = 'none';
            examId.required = false;
            onlineExamId.required = false;
        }
    }

    announcementType.addEventListener('change', toggleExamSelection);
    toggleExamSelection(); // Initial call
});
</script>
@endsection
