@extends('teacher.layout.app')

@section('title', 'AI Grade Analyzer')
@section('page-title', 'AI Grade Analyzer')
@section('page-description', 'Analyze student performance and get AI-powered insights')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Analysis Filters
                </h5>
            </div>
            <div class="card-body">
                <form id="analysisForm">
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select class="form-select" id="class_id" name="class_id">
                            <option value="">All Classes</option>
                            @if(isset($classrooms) && $classrooms->count() > 0)
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="e.g., Mathematics">
                    </div>

                    <div class="mb-3">
                        <label for="exam_type" class="form-label">Exam Type</label>
                        <select class="form-select" id="exam_type" name="exam_type">
                            <option value="">All Types</option>
                            <option value="quiz">Quiz</option>
                            <option value="test">Test</option>
                            <option value="exam">Exam</option>
                            <option value="assignment">Assignment</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="analysis_type" class="form-label">Analysis Type *</label>
                        <select class="form-select" id="analysis_type" name="analysis_type" required>
                            <option value="">Select Analysis Type</option>
                            <option value="overall">Overall Performance</option>
                            <option value="trends">Performance Trends</option>
                            <option value="comparison">Student Comparison</option>
                            <option value="individual">Individual Analysis</option>
                            <option value="recommendations">Teaching Recommendations</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="analyzeButton">
                        <i class="fas fa-chart-bar me-2"></i>
                        Analyze Grades
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card modern-card mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Quick Stats
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-item">
                            <div class="stat-value text-primary">{{ $recentExams->count() }}</div>
                            <div class="stat-label">Recent Grades</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-item">
                            <div class="stat-value text-success">{{ $classrooms->count() }}</div>
                            <div class="stat-label">Classes</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-value text-warning">{{ $students->count() }}</div>
                            <div class="stat-label">Students</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item">
                            <div class="stat-value text-info">AI</div>
                            <div class="stat-label">Powered</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-brain me-2"></i>
                    AI Analysis Results
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="copyButton" disabled>
                        <i class="fas fa-copy me-1"></i>Copy
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="downloadButton" disabled>
                        <i class="fas fa-download me-1"></i>Download
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="analysisContent">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <h5>No Analysis Available Yet</h5>
                        <p>Select your filters and click "Analyze Grades" to get AI-powered insights about student performance.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="card modern-card mt-4" id="statisticsCard" style="display: none;">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Performance Statistics
                </h6>
            </div>
            <div class="card-body">
                <div id="statisticsContent"></div>
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
                <p class="mb-0">AI is analyzing your data...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('analysisForm');
    const analyzeButton = document.getElementById('analyzeButton');
    const analysisContent = document.getElementById('analysisContent');
    const statisticsCard = document.getElementById('statisticsCard');
    const statisticsContent = document.getElementById('statisticsContent');
    const copyButton = document.getElementById('copyButton');
    const downloadButton = document.getElementById('downloadButton');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    let currentAnalysis = '';
    let currentStatistics = null;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Validate required fields
        if (!data.analysis_type) {
            alert('Please select an analysis type.');
            return;
        }

        // Show loading
        loadingModal.show();
        analyzeButton.disabled = true;
        analyzeButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Analyzing...';

        try {
            const response = await fetch('{{ route("teacher.ai.grade-analyzer.analyze") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                currentAnalysis = result.analysis;
                currentStatistics = result.statistics;
                displayAnalysis(result.analysis, result.statistics, result.metadata);
                enableActionButtons();
            } else {
                showError(result.message || 'Failed to analyze grades. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('Network error. Please check your connection and try again.');
        } finally {
            loadingModal.hide();
            analyzeButton.disabled = false;
            analyzeButton.innerHTML = '<i class="fas fa-chart-bar me-2"></i>Analyze Grades';
        }
    });

    function displayAnalysis(analysis, statistics, metadata) {
        const formattedAnalysis = analysis.replace(/\n/g, '<br>');
        
        analysisContent.innerHTML = `
            <div class="analysis-header mb-4 p-3 bg-light rounded">
                <h4 class="mb-2">${metadata.analysis_type.charAt(0).toUpperCase() + metadata.analysis_type.slice(1)} Analysis</h4>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Students:</strong> ${metadata.total_students}
                    </div>
                    <div class="col-md-3">
                        <strong>Date Range:</strong> ${metadata.date_range}
                    </div>
                    <div class="col-md-3">
                        <strong>Generated:</strong> ${new Date(metadata.generated_at).toLocaleDateString()}
                    </div>
                </div>
            </div>
            <div class="analysis-body">
                ${formattedAnalysis}
            </div>
        `;

        // Display statistics if available
        if (statistics) {
            displayStatistics(statistics);
        }
    }

    function displayStatistics(stats) {
        statisticsCard.style.display = 'block';
        statisticsContent.innerHTML = `
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="stat-card text-center p-3 bg-primary text-white rounded">
                        <div class="stat-value h3 mb-1">${stats.average}%</div>
                        <div class="stat-label">Average</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card text-center p-3 bg-success text-white rounded">
                        <div class="stat-value h3 mb-1">${stats.highest}%</div>
                        <div class="stat-label">Highest</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card text-center p-3 bg-warning text-white rounded">
                        <div class="stat-value h3 mb-1">${stats.lowest}%</div>
                        <div class="stat-label">Lowest</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card text-center p-3 bg-info text-white rounded">
                        <div class="stat-value h3 mb-1">${stats.pass_rate}%</div>
                        <div class="stat-label">Pass Rate</div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <h6>Grade Distribution</h6>
                    <div class="row">
                        <div class="col-2 text-center">
                            <div class="grade-badge bg-success text-white rounded p-2">
                                <div class="h5 mb-1">${stats.grade_distribution.A}</div>
                                <div class="small">A</div>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <div class="grade-badge bg-primary text-white rounded p-2">
                                <div class="h5 mb-1">${stats.grade_distribution.B}</div>
                                <div class="small">B</div>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <div class="grade-badge bg-info text-white rounded p-2">
                                <div class="h5 mb-1">${stats.grade_distribution.C}</div>
                                <div class="small">C</div>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <div class="grade-badge bg-warning text-white rounded p-2">
                                <div class="h5 mb-1">${stats.grade_distribution.D}</div>
                                <div class="small">D</div>
                            </div>
                        </div>
                        <div class="col-2 text-center">
                            <div class="grade-badge bg-danger text-white rounded p-2">
                                <div class="h5 mb-1">${stats.grade_distribution.F}</div>
                                <div class="small">F</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function enableActionButtons() {
        copyButton.disabled = false;
        downloadButton.disabled = false;
    }

    function showError(message) {
        analysisContent.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
        statisticsCard.style.display = 'none';
    }

    // Copy functionality
    copyButton.addEventListener('click', function() {
        if (currentAnalysis) {
            navigator.clipboard.writeText(currentAnalysis).then(function() {
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
        if (currentAnalysis) {
            const content = currentStatistics ? 
                `ANALYSIS RESULTS\n\n${currentAnalysis}\n\nSTATISTICS\n\n${JSON.stringify(currentStatistics, null, 2)}` : 
                currentAnalysis;
            
            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `grade-analysis-${Date.now()}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    });
});
</script>

<style>
.analysis-body {
    line-height: 1.6;
}

.analysis-body h1,
.analysis-body h2,
.analysis-body h3 {
    color: #495057;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.analysis-body ul,
.analysis-body ol {
    padding-left: 1.5rem;
}

.analysis-body li {
    margin-bottom: 0.5rem;
}

.analysis-header {
    border-left: 4px solid #0d6efd;
}

.stat-card {
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.grade-badge {
    transition: transform 0.2s ease;
}

.grade-badge:hover {
    transform: scale(1.05);
}

.stat-value {
    font-weight: bold;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
}

#analysisContent {
    min-height: 400px;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection
