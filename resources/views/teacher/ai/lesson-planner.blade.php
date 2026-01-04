@extends('teacher.layout.app')

@section('title', 'AI Lesson Planner')
@section('page-title', 'AI Lesson Planner')
@section('page-description', 'Generate comprehensive lesson plans with AI assistance')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Lesson Plan Generator
                </h5>
            </div>
            <div class="card-body">
                <form id="lessonPlanForm">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g., Mathematics" required>
                        <div class="form-text">Enter the subject you're teaching</div>
                    </div>

                    <div class="mb-3">
                        <label for="topic" class="form-label">Topic *</label>
                        <input type="text" class="form-control" id="topic" name="topic" placeholder="e.g., Quadratic Equations" required>
                        <div class="form-text">Specific topic or concept to teach</div>
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
                        <label for="duration" class="form-label">Duration (minutes) *</label>
                        <input type="number" class="form-control" id="duration" name="duration" min="15" max="180" value="45" required>
                        <div class="form-text">Lesson duration in minutes (15-180)</div>
                    </div>

                    <div class="mb-3">
                        <label for="teaching_style" class="form-label">Teaching Style</label>
                        <select class="form-select" id="teaching_style" name="teaching_style">
                            <option value="interactive">Interactive</option>
                            <option value="lecture">Lecture-based</option>
                            <option value="hands-on">Hands-on</option>
                            <option value="collaborative">Collaborative</option>
                            <option value="inquiry-based">Inquiry-based</option>
                            <option value="project-based">Project-based</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="learning_objectives" class="form-label">Learning Objectives</label>
                        <textarea class="form-control" id="learning_objectives" name="learning_objectives" rows="3" placeholder="What should students learn from this lesson?"></textarea>
                        <div class="form-text">Optional: Specific learning goals for this lesson</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="generateButton">
                        <i class="fas fa-magic me-2"></i>
                        Generate Lesson Plan
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    Quick Tips
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Be specific with your topic for better results
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Include learning objectives for targeted planning
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Choose appropriate duration for your class
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Select teaching style that fits your approach
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Generated Lesson Plan
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
                <div id="lessonPlanContent">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-calendar-plus fa-3x mb-3"></i>
                        <h5>No Lesson Plan Generated Yet</h5>
                        <p>Fill out the form on the left and click "Generate Lesson Plan" to create your AI-powered lesson plan.</p>
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
                <p class="mb-0">Generating your lesson plan...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('lessonPlanForm');
    const generateButton = document.getElementById('generateButton');
    const lessonPlanContent = document.getElementById('lessonPlanContent');
    const copyButton = document.getElementById('copyButton');
    const downloadButton = document.getElementById('downloadButton');
    const printButton = document.getElementById('printButton');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    let currentLessonPlan = '';

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Validate required fields
        if (!data.subject || !data.topic || !data.grade_level || !data.duration) {
            alert('Please fill in all required fields.');
            return;
        }

        // Show loading
        loadingModal.show();
        generateButton.disabled = true;
        generateButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';

        try {
            const response = await fetch('{{ route("teacher.ai.lesson-planner.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                currentLessonPlan = result.lesson_plan;
                displayLessonPlan(result.lesson_plan, result.metadata);
                enableActionButtons();
            } else {
                showError(result.message || 'Failed to generate lesson plan. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Network error. Please check your connection and try again.');
        } finally {
            loadingModal.hide();
            generateButton.disabled = false;
            generateButton.innerHTML = '<i class="fas fa-magic me-2"></i>Generate Lesson Plan';
        }
    });

    function displayLessonPlan(lessonPlan, metadata) {
        const formattedPlan = lessonPlan.replace(/\n/g, '<br>');
        
        lessonPlanContent.innerHTML = `
            <div class="lesson-plan-header mb-4 p-3 bg-light rounded">
                <h4 class="mb-2">${metadata.subject} - ${metadata.topic}</h4>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Grade Level:</strong> ${metadata.grade_level}
                    </div>
                    <div class="col-md-3">
                        <strong>Duration:</strong> ${metadata.duration} minutes
                    </div>
                    <div class="col-md-3">
                        <strong>Generated:</strong> ${new Date(metadata.generated_at).toLocaleDateString()}
                    </div>
                </div>
            </div>
            <div class="lesson-plan-body">
                ${formattedPlan}
            </div>
        `;
    }

    function enableActionButtons() {
        copyButton.disabled = false;
        downloadButton.disabled = false;
        printButton.disabled = false;
    }

    function showError(message) {
        lessonPlanContent.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
    }

    // Copy functionality
    copyButton.addEventListener('click', function() {
        if (currentLessonPlan) {
            navigator.clipboard.writeText(currentLessonPlan).then(function() {
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
        if (currentLessonPlan) {
            const blob = new Blob([currentLessonPlan], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `lesson-plan-${Date.now()}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    });

    // Print functionality
    printButton.addEventListener('click', function() {
        if (currentLessonPlan) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Lesson Plan</title>
                        <style>
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            h1, h2, h3 { color: #333; }
                            .lesson-plan-header { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                        </style>
                    </head>
                    <body>
                        ${lessonPlanContent.innerHTML}
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
.lesson-plan-body {
    line-height: 1.6;
}

.lesson-plan-body h1,
.lesson-plan-body h2,
.lesson-plan-body h3 {
    color: #495057;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.lesson-plan-body ul,
.lesson-plan-body ol {
    padding-left: 1.5rem;
}

.lesson-plan-body li {
    margin-bottom: 0.5rem;
}

.lesson-plan-header {
    border-left: 4px solid #0d6efd;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

#lessonPlanContent {
    min-height: 400px;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection
