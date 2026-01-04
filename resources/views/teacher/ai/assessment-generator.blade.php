@extends('teacher.layout.app')

@section('title', 'AI Assessment Generator')
@section('page-title', 'AI Assessment Generator')
@section('page-description', 'Create comprehensive assessments and quizzes with AI assistance')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Assessment Generator
                </h5>
            </div>
            <div class="card-body">
                <form id="assessmentForm">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g., Mathematics" required>
                    </div>

                    <div class="mb-3">
                        <label for="topic" class="form-label">Topic *</label>
                        <input type="text" class="form-control" id="topic" name="topic" placeholder="e.g., Quadratic Equations" required>
                    </div>

                    <div class="mb-3">
                        <label for="grade_level" class="form-label">Grade Level *</label>
                        <select class="form-select" id="grade_level" name="grade_level" required>
                            <option value="">Select Grade Level</option>
                            <optgroup label="Elementary">
                                <option value="K">Kindergarten</option>
                                <option value="1st Grade">1st Grade</option>
                                <option value="2nd Grade">2nd Grade</option>
                                <option value="3rd Grade">3rd Grade</option>
                                <option value="4th Grade">4th Grade</option>
                                <option value="5th Grade">5th Grade</option>
                            </optgroup>
                            <optgroup label="Middle School">
                                <option value="6th Grade">6th Grade</option>
                                <option value="7th Grade">7th Grade</option>
                                <option value="8th Grade">8th Grade</option>
                            </optgroup>
                            <optgroup label="High School">
                                <option value="9th Grade">9th Grade</option>
                                <option value="10th Grade">10th Grade</option>
                                <option value="11th Grade">11th Grade</option>
                                <option value="12th Grade">12th Grade</option>
                            </optgroup>
                            <optgroup label="Higher Education">
                                <option value="College Level">College Level</option>
                                <option value="University Level">University Level</option>
                                <option value="Graduate Level">Graduate Level</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assessment_type" class="form-label">Assessment Type *</label>
                        <select class="form-select" id="assessment_type" name="assessment_type" required>
                            <option value="">Select Type</option>
                            <option value="quiz">Quiz</option>
                            <option value="test">Test</option>
                            <option value="exam">Exam</option>
                            <option value="project">Project</option>
                            <option value="assignment">Assignment</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="question_count" class="form-label">Number of Questions *</label>
                        <input type="number" class="form-control" id="question_count" name="question_count" min="5" max="50" value="10" required>
                        <div class="form-text">5-50 questions</div>
                    </div>

                    <div class="mb-3">
                        <label for="difficulty_level" class="form-label">Difficulty Level *</label>
                        <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                            <option value="">Select Difficulty</option>
                            <option value="easy">Easy - Basic recall and understanding</option>
                            <option value="medium">Medium - Application and analysis</option>
                            <option value="hard">Hard - Synthesis and evaluation</option>
                            <option value="mixed">Mixed - Variety of difficulty levels</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Question Types *</label>
                        <div class="row" id="questionTypesContainer">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="multiple_choice" id="mc" checked>
                                    <label class="form-check-label" for="mc">Multiple Choice</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="true_false" id="tf">
                                    <label class="form-check-label" for="tf">True/False</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="short_answer" id="sa">
                                    <label class="form-check-label" for="sa">Short Answer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="essay" id="essay">
                                    <label class="form-check-label" for="essay">Essay</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="fill_blank" id="fb">
                                    <label class="form-check-label" for="fb">Fill in the Blank</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="matching" id="match">
                                    <label class="form-check-label" for="match">Matching</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="numerical" id="num">
                                    <label class="form-check-label" for="num">Numerical</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="question_types[]" value="diagram" id="diag">
                                    <label class="form-check-label" for="diag">Diagram/Visual</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="time_limit" class="form-label">Time Limit (minutes)</label>
                        <input type="number" class="form-control" id="time_limit" name="time_limit" min="10" max="180" placeholder="Optional">
                        <div class="form-text">Optional: Assessment time limit</div>
                    </div>

                    <div class="mb-3">
                        <label for="learning_objectives" class="form-label">Learning Objectives</label>
                        <textarea class="form-control" id="learning_objectives" name="learning_objectives" rows="3" placeholder="What should this assessment evaluate?"></textarea>
                        <div class="form-text">Optional: Specific learning goals to assess</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="generateButton">
                        <i class="fas fa-magic me-2"></i>
                        Generate Assessment
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Assessment Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Mix question types for better assessment
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Consider appropriate difficulty level
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Set realistic time limits
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Align with learning objectives
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>
                    Generated Assessment
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="copyButton" disabled>
                        <i class="fas fa-copy me-1"></i>Copy
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="downloadButton" disabled>
                        <i class="fas fa-download me-1"></i>Download
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="printButton" disabled>
                        <i class="fas fa-print me-1"></i>Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="assessmentContent">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-question-circle fa-3x mb-3"></i>
                        <h5>No Assessment Generated Yet</h5>
                        <p>Fill out the form on the left and click "Generate Assessment" to create your AI-powered assessment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Generating your assessment...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('assessmentForm');
    const generateButton = document.getElementById('generateButton');
    const assessmentContent = document.getElementById('assessmentContent');
    const copyButton = document.getElementById('copyButton');
    const downloadButton = document.getElementById('downloadButton');
    const printButton = document.getElementById('printButton');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    let currentAssessment = '';

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Get selected question types
        const questionTypes = Array.from(document.querySelectorAll('input[name="question_types[]"]:checked')).map(cb => cb.value);
        data.question_types = questionTypes;
        
        // Validate required fields
        if (!data.subject || !data.topic || !data.grade_level || !data.assessment_type || !data.question_count || !data.difficulty_level) {
            alert('Please fill in all required fields.');
            return;
        }

        if (questionTypes.length === 0) {
            alert('Please select at least one question type.');
            return;
        }

        // Show loading
        loadingModal.show();
        generateButton.disabled = true;
        generateButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';

        try {
            const response = await fetch('{{ route("teacher.ai.assessment-generator.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                currentAssessment = result.assessment;
                displayAssessment(result.assessment, result.metadata);
                enableActionButtons();
            } else {
                showError(result.message || 'Failed to generate assessment. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Network error. Please check your connection and try again.');
        } finally {
            loadingModal.hide();
            generateButton.disabled = false;
            generateButton.innerHTML = '<i class="fas fa-magic me-2"></i>Generate Assessment';
        }
    });

    function displayAssessment(assessment, metadata) {
        const formattedAssessment = assessment.replace(/\n/g, '<br>');
        
        assessmentContent.innerHTML = `
            <div class="assessment-header mb-4 p-3 bg-light rounded">
                <h4 class="mb-2">${metadata.subject} - ${metadata.topic}</h4>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Type:</strong> ${metadata.assessment_type.charAt(0).toUpperCase() + metadata.assessment_type.slice(1)}
                    </div>
                    <div class="col-md-3">
                        <strong>Grade Level:</strong> ${metadata.grade_level}
                    </div>
                    <div class="col-md-3">
                        <strong>Questions:</strong> ${metadata.question_count}
                    </div>
                    <div class="col-md-3">
                        <strong>Difficulty:</strong> ${metadata.difficulty_level.charAt(0).toUpperCase() + metadata.difficulty_level.slice(1)}
                    </div>
                </div>
            </div>
            <div class="assessment-body">
                ${formattedAssessment}
            </div>
        `;
    }

    function enableActionButtons() {
        copyButton.disabled = false;
        downloadButton.disabled = false;
        printButton.disabled = false;
    }

    function showError(message) {
        assessmentContent.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
    }

    // Copy functionality
    copyButton.addEventListener('click', function() {
        if (currentAssessment) {
            navigator.clipboard.writeText(currentAssessment).then(function() {
                const originalText = copyButton.innerHTML;
                copyButton.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
                copyButton.classList.add('btn-success');
                copyButton.classList.remove('btn-outline-secondary');
                
                setTimeout(function() {
                    copyButton.innerHTML = originalText;
                    copyButton.classList.remove('btn-success');
                    copyButton.classList.add('btn-outline-secondary');
                }, 2000);
            });
        }
    });

    // Download functionality
    downloadButton.addEventListener('click', function() {
        if (currentAssessment) {
            const blob = new Blob([currentAssessment], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `assessment-${Date.now()}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    });

    // Print functionality
    printButton.addEventListener('click', function() {
        if (currentAssessment) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Assessment</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            h1, h2, h3 { color: #333; }
                            .assessment-header { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                        </style>
                    </head>
                    <body>
                        ${assessmentContent.innerHTML}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    });
});
</script>

<style>
.assessment-body {
    line-height: 1.6;
}

.assessment-body h1,
.assessment-body h2,
.assessment-body h3 {
    color: #495057;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.assessment-body ul,
.assessment-body ol {
    padding-left: 1.5rem;
}

.assessment-body li {
    margin-bottom: 0.5rem;
}

.assessment-header {
    border-left: 4px solid #0d6efd;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

#assessmentContent {
    min-height: 400px;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
</style>
@endsection
