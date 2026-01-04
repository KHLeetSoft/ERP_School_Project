@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="bx bx-edit me-2 text-primary"></i> Edit Online Exam</h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.online-exam.show', $onlineExam) }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back me-1"></i> Back to Details
                </a>
                <a href="{{ route('admin.online-exam.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bx bx-list-ul me-1"></i> All Exams
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6><i class="fas fa-exclamation-triangle me-2"></i> Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($onlineExam->isActive() || $onlineExam->isCompleted() || $onlineExam->attempts()->exists())
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Note:</strong> This exam cannot be edited because it has either started, completed, or has student attempts.
        </div>
        <script>
            // Disable form if exam cannot be edited
            $(document).ready(function() {
                $('form input, form select, form textarea, form button[type="submit"]').prop('disabled', true);
            });
        </script>
    @endif

    <form action="{{ route('admin.online-exam.update', $onlineExam) }}" method="POST" id="onlineExamForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i> Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">Exam Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="{{ old('title', $onlineExam->title) }}" required>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $onlineExam->description) }}</textarea>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                <select class="form-select" id="class_id" name="class_id" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $onlineExam->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                <select class="form-select" id="section_id" name="section_id" required>
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id', $onlineExam->section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $onlineExam->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exam Configuration -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-cog me-2"></i> Exam Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="duration_minutes" class="form-label">Duration (Minutes) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" 
                                       value="{{ old('duration_minutes', $onlineExam->duration_minutes) }}" min="1" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="total_marks" name="total_marks" 
                                       value="{{ old('total_marks', $onlineExam->total_marks) }}" min="1" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="passing_marks" class="form-label">Passing Marks <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="passing_marks" name="passing_marks" 
                                       value="{{ old('passing_marks', $onlineExam->passing_marks) }}" min="1" required>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="max_attempts" class="form-label">Max Attempts <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="max_attempts" name="max_attempts" 
                                       value="{{ old('max_attempts', $onlineExam->max_attempts) }}" min="1" max="10" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="start_datetime" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" 
                                       value="{{ old('start_datetime', $onlineExam->start_datetime->format('Y-m-d\TH:i')) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_datetime" class="form-label">End Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" 
                                       value="{{ old('end_datetime', $onlineExam->end_datetime->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>
                        
                        <!-- Negative Marking -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="negative_marking" name="negative_marking" 
                                           {{ old('negative_marking', $onlineExam->negative_marking) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="negative_marking">
                                        Enable Negative Marking
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="negative_marks_container" style="{{ old('negative_marking', $onlineExam->negative_marking) ? 'display: block;' : 'display: none;' }}">
                                <label for="negative_marks" class="form-label">Negative Marks</label>
                                <input type="number" class="form-control" id="negative_marks" name="negative_marks" 
                                       value="{{ old('negative_marks', $onlineExam->negative_marks) }}" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Selection -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-help-circle me-2"></i> Questions Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="question_category" class="form-label">Filter by Category</label>
                                <select class="form-select" id="question_category">
                                    <option value="">All Categories</option>
                                    @foreach($questionCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-info d-block" id="loadQuestions">
                                    <i class="bx bx-search me-1"></i> Load Questions
                                </button>
                            </div>
                        </div>
                        
                        <div id="questionsContainer">
                            <div class="text-center text-muted py-4">
                                <i class="bx bx-help-circle" style="font-size: 48px;"></i>
                                <p>Click "Load Questions" to view available questions</p>
                            </div>
                        </div>
                        
                        <div id="selectedQuestionsContainer">
                            <h6 class="mt-4 mb-3">Selected Questions</h6>
                            <div id="selectedQuestionsList">
                                @foreach($onlineExam->questions as $index => $question)
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <small>{{ Str::limit($question->question_text, 100) }}</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="hidden" name="questions[{{ $index }}][question_id]" value="{{ $question->id }}">
                                                    <input type="number" name="questions[{{ $index }}][marks]" class="form-control form-control-sm" 
                                                           value="{{ $question->pivot->marks }}" min="1" onchange="updateQuestionMarks({{ $question->id }}, this.value)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm btn-danger remove-selected" 
                                                            data-question-id="{{ $question->id }}">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Exam Settings -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-cog me-2"></i> Exam Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="randomize_questions" name="randomize_questions" 
                                   {{ old('randomize_questions', $onlineExam->randomize_questions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="randomize_questions">
                                Randomize Questions
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="show_result_immediately" name="show_result_immediately" 
                                   {{ old('show_result_immediately', $onlineExam->show_result_immediately) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_result_immediately">
                                Show Result Immediately
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="allow_calculator" name="allow_calculator" 
                                   {{ old('allow_calculator', $onlineExam->allow_calculator) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_calculator">
                                Allow Calculator
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="allow_notes" name="allow_notes" 
                                   {{ old('allow_notes', $onlineExam->allow_notes) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_notes">
                                Allow Notes
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="enable_proctoring" name="enable_proctoring" 
                                   {{ old('enable_proctoring', $onlineExam->enable_proctoring) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_proctoring">
                                Enable Proctoring
                            </label>
                        </div>
                        
                        <div class="mb-3">
                            <label for="instructions" class="form-label">Instructions</label>
                            <textarea class="form-control" id="instructions" name="instructions" rows="5">{{ old('instructions', $onlineExam->instructions) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Update Exam
                            </button>
                            <a href="{{ route('admin.online-exam.show', $onlineExam) }}" class="btn btn-secondary">
                                <i class="bx bx-x me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize selected questions from existing exam
    let selectedQuestions = @json($onlineExam->questions->map(function($question) {
        return [
            'question_id' => $question->id,
            'question_text' => $question->question_text,
            'marks' => $question->pivot->marks
        ];
    })->values());
    
    // Toggle negative marks input
    $('#negative_marking').change(function() {
        if ($(this).is(':checked')) {
            $('#negative_marks_container').show();
        } else {
            $('#negative_marks_container').hide();
        }
    });
    
    // Load questions
    $('#loadQuestions').click(function() {
        const categoryId = $('#question_category').val();
        loadQuestions(categoryId);
    });
    
    // Load sections based on class
    $('#class_id').change(function() {
        const classId = $(this).val();
        if (classId) {
            $.get("{{ route('admin.online-exam.sections-by-class') }}", { class_id: classId })
                .done(function(sections) {
                    const currentSectionId = "{{ $onlineExam->section_id }}";
                    $('#section_id').empty().append('<option value="">Select Section</option>');
                    sections.forEach(function(section) {
                        const selected = section.id == currentSectionId ? 'selected' : '';
                        $('#section_id').append(`<option value="${section.id}" ${selected}>${section.name}</option>`);
                    });
                });
        } else {
            $('#section_id').empty().append('<option value="">Select Section</option>');
        }
    });
    
    function loadQuestions(categoryId = '') {
        const url = "{{ route('admin.online-exam.questions-by-category') }}";
        $.get(url, { category_id: categoryId })
            .done(function(questions) {
                displayQuestions(questions);
            })
            .fail(function() {
                alert('Failed to load questions. Please try again.');
            });
    }
    
    function displayQuestions(questions) {
        let html = '';
        if (questions.length === 0) {
            html = '<div class="text-center text-muted py-4"><p>No questions found for selected category.</p></div>';
        } else {
            html = '<div class="table-responsive"><table class="table table-striped"><thead><tr><th>Question</th><th>Type</th><th>Marks</th><th>Action</th></tr></thead><tbody>';
            questions.forEach(function(question) {
                const isSelected = selectedQuestions.find(q => q.question_id == question.id);
                const buttonClass = isSelected ? 'btn-danger' : 'btn-primary';
                const buttonText = isSelected ? 'Remove' : 'Add';
                
                html += `
                    <tr>
                        <td>${question.question_text.substring(0, 100)}...</td>
                        <td><span class="badge bg-info">${question.type.toUpperCase()}</span></td>
                        <td>
                            <input type="number" class="form-control form-control-sm question-marks" 
                                   data-question-id="${question.id}" value="5" min="1" style="width: 80px;">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm ${buttonClass} toggle-question" 
                                    data-question-id="${question.id}" data-question-text="${question.question_text}">
                                ${buttonText}
                            </button>
                        </td>
                    </tr>
                `;
            });
            html += '</tbody></table></div>';
        }
        $('#questionsContainer').html(html);
    }
    
    $(document).on('click', '.toggle-question', function() {
        const questionId = $(this).data('question-id');
        const questionText = $(this).data('question-text');
        const marks = $(`.question-marks[data-question-id="${questionId}"]`).val();
        
        const existingIndex = selectedQuestions.findIndex(q => q.question_id == questionId);
        
        if (existingIndex > -1) {
            // Remove question
            selectedQuestions.splice(existingIndex, 1);
            $(this).removeClass('btn-danger').addClass('btn-primary').text('Add');
        } else {
            // Add question
            selectedQuestions.push({
                question_id: questionId,
                question_text: questionText,
                marks: parseInt(marks)
            });
            $(this).removeClass('btn-primary').addClass('btn-danger').text('Remove');
        }
        
        updateSelectedQuestions();
    });
    
    function updateSelectedQuestions() {
        let html = '';
        let totalMarks = 0;
        
        selectedQuestions.forEach(function(question, index) {
            totalMarks += question.marks;
            html += `
                <div class="card mb-2">
                    <div class="card-body py-2">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <small>${question.question_text.substring(0, 100)}...</small>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" name="questions[${index}][question_id]" value="${question.question_id}">
                                <input type="number" name="questions[${index}][marks]" class="form-control form-control-sm" 
                                       value="${question.marks}" min="1" onchange="updateQuestionMarks(${question.question_id}, this.value)">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-selected" 
                                        data-question-id="${question.question_id}">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (selectedQuestions.length > 0) {
            html += `<div class="alert alert-info mt-2">Total Selected Questions: ${selectedQuestions.length} | Total Marks: ${totalMarks}</div>`;
        }
        
        $('#selectedQuestionsList').html(html);
    }
    
    window.updateQuestionMarks = function(questionId, marks) {
        const question = selectedQuestions.find(q => q.question_id == questionId);
        if (question) {
            question.marks = parseInt(marks);
            updateSelectedQuestions();
        }
    };
    
    $(document).on('click', '.remove-selected', function() {
        const questionId = $(this).data('question-id');
        const index = selectedQuestions.findIndex(q => q.question_id == questionId);
        if (index > -1) {
            selectedQuestions.splice(index, 1);
            updateSelectedQuestions();
            
            // Update the main questions table
            $(`.toggle-question[data-question-id="${questionId}"]`)
                .removeClass('btn-danger').addClass('btn-primary').text('Add');
        }
    });
    
    // Form validation
    $('#onlineExamForm').submit(function(e) {
        if (selectedQuestions.length === 0) {
            e.preventDefault();
            alert('Please select at least one question for the exam.');
            return false;
        }
        
        const totalMarks = selectedQuestions.reduce((sum, q) => sum + q.marks, 0);
        const expectedMarks = parseInt($('#total_marks').val());
        
        if (totalMarks !== expectedMarks) {
            e.preventDefault();
            alert(`Total marks of selected questions (${totalMarks}) must equal the exam total marks (${expectedMarks}).`);
            return false;
        }
        
        return true;
    });
});
</script>
@endsection
