@csrf
<div class="row g-3">
    {{-- Basic Information --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3">
            <i class="fas fa-info-circle me-2 text-primary"></i> Basic Information
        </h5>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Subject <span class="text-danger">*</span></label>
        <select name="subject_id" class="form-select" required>
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" @selected(old('subject_id', $lessonPlan->subject_id ?? null) == $subject->id)>
                    {{ $subject->name }} ({{ $subject->code }})
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Syllabus (Optional)</label>
        <select name="syllabus_id" class="form-select">
            <option value="">Select Syllabus</option>
            @foreach($syllabi as $syllabus)
                <option value="{{ $syllabus->id }}" @selected(old('syllabus_id', $lessonPlan->syllabus_id ?? null) == $syllabus->id)>
                    {{ $syllabus->title }}
                </option>
            @endforeach
        </select>
    </div>
    
    <div class="col-md-8">
        <label class="form-label">Lesson Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $lessonPlan->title ?? '') }}" required />
    </div>
    
    <div class="col-md-2">
        <label class="form-label">Lesson #</label>
        <input type="number" name="lesson_number" class="form-control" min="1" max="999" value="{{ old('lesson_number', $lessonPlan->lesson_number ?? '') }}" />
    </div>
    
    <div class="col-md-2">
        <label class="form-label">Unit #</label>
        <input type="number" name="unit_number" class="form-control" min="1" max="999" value="{{ old('unit_number', $lessonPlan->unit_number ?? '') }}" />
    </div>
    
    {{-- Learning Objectives --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-bullseye me-2 text-success"></i> Learning Objectives
        </h5>
    </div>
    
    <div class="col-12">
        <div id="learning-objectives-container">
            @if(old('learning_objectives') && is_array(old('learning_objectives')))
                @foreach(old('learning_objectives') as $index => $objective)
                    <div class="input-group mb-2">
                        <input type="text" name="learning_objectives[]" class="form-control" value="{{ $objective }}" placeholder="Enter learning objective" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="learning-objectives-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @elseif(isset($lessonPlan) && $lessonPlan->learning_objectives_list)
                @foreach($lessonPlan->learning_objectives_list as $objective)
                    <div class="input-group mb-2">
                        <input type="text" name="learning_objectives[]" class="form-control" value="{{ $objective }}" placeholder="Enter learning objective" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="learning-objectives-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="addArrayItem('learning-objectives-container', 'learning_objectives[]')">
            <i class="fas fa-plus me-1"></i> Add Objective
        </button>
    </div>
    
    {{-- Prerequisites --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-list-check me-2 text-warning"></i> Prerequisites
        </h5>
    </div>
    
    <div class="col-12">
        <div id="prerequisites-container">
            @if(old('prerequisites') && is_array(old('prerequisites')))
                @foreach(old('prerequisites') as $index => $prerequisite)
                    <div class="input-group mb-2">
                        <input type="text" name="prerequisites[]" class="form-control" value="{{ $prerequisite }}" placeholder="Enter prerequisite" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="prerequisites-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @elseif(isset($lessonPlan) && $lessonPlan->prerequisites_list)
                @foreach($lessonPlan->prerequisites_list as $prerequisite)
                    <div class="input-group mb-2">
                        <input type="text" name="prerequisites[]" class="form-control" value="{{ $prerequisite }}" placeholder="Enter prerequisite" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="prerequisites-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="addArrayItem('prerequisites-container', 'prerequisites[]')">
            <i class="fas fa-plus me-1"></i> Add Prerequisite
        </button>
    </div>
    
    {{-- Materials and Resources --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-tools me-2 text-info"></i> Materials and Resources
        </h5>
    </div>
    
    <div class="col-12">
        <div id="materials-container">
            @if(old('materials_needed') && is_array(old('materials_needed')))
                @foreach(old('materials_needed') as $index => $material)
                    <div class="input-group mb-2">
                        <input type="text" name="materials_needed[]" class="form-control" value="{{ $material }}" placeholder="Enter material or resource" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="materials-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @elseif(isset($lessonPlan) && $lessonPlan->materials_list)
                @foreach($lessonPlan->materials_list as $material)
                    <div class="input-group mb-2">
                        <input type="text" name="materials_needed[]" class="form-control" value="{{ $material }}" placeholder="Enter material or resource" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="materials-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="addArrayItem('materials-container', 'materials_needed[]')">
            <i class="fas fa-plus me-1"></i> Add Material
        </button>
    </div>
    
    {{-- Lesson Details --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-clock me-2 text-primary"></i> Lesson Details
        </h5>
    </div>
    
    <div class="col-md-4">
        <label class="form-label">Duration (minutes)</label>
        <input type="number" name="lesson_duration" class="form-control" min="15" max="480" value="{{ old('lesson_duration', $lessonPlan->lesson_duration ?? '') }}" />
        <small class="text-muted">15-480 minutes</small>
    </div>
    
    <div class="col-md-4">
        <label class="form-label">Difficulty Level <span class="text-danger">*</span></label>
        <select name="difficulty_level" class="form-select" required>
            <option value="1" @selected(old('difficulty_level', $lessonPlan->difficulty_level ?? 1) == 1)>Beginner</option>
            <option value="2" @selected(old('difficulty_level', $lessonPlan->difficulty_level ?? 1) == 2)>Intermediate</option>
            <option value="3" @selected(old('difficulty_level', $lessonPlan->difficulty_level ?? 1) == 3)>Advanced</option>
        </select>
    </div>
    
    <div class="col-md-4">
        <label class="form-label">Estimated Students</label>
        <input type="number" name="estimated_student_count" class="form-control" min="1" max="1000" value="{{ old('estimated_student_count', $lessonPlan->estimated_student_count ?? '') }}" />
    </div>
    
    {{-- Teaching Methods --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-chalkboard me-2 text-success"></i> Teaching Methods
        </h5>
    </div>
    
    <div class="col-12">
        <div id="teaching-methods-container">
            @if(old('teaching_methods') && is_array(old('teaching_methods')))
                @foreach(old('teaching_methods') as $index => $method)
                    <div class="input-group mb-2">
                        <input type="text" name="teaching_methods[]" class="form-control" value="{{ $method }}" placeholder="Enter teaching method" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="teaching-methods-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @elseif(isset($lessonPlan) && $lessonPlan->teaching_methods_list)
                @foreach($lessonPlan->teaching_methods_list as $method)
                    <div class="input-group mb-2">
                        <input type="text" name="teaching_methods[]" class="form-control" value="{{ $method }}" placeholder="Enter teaching method" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="teaching-methods-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="addArrayItem('teaching-methods-container', 'teaching_methods[]')">
            <i class="fas fa-plus me-1"></i> Add Method
        </button>
    </div>
    
    {{-- Activities --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-play-circle me-2 text-warning"></i> Activities
        </h5>
    </div>
    
    <div class="col-12">
        <div id="activities-container">
            @if(old('activities') && is_array(old('activities')))
                @foreach(old('activities') as $index => $activity)
                    <div class="input-group mb-2">
                        <input type="text" name="activities[]" class="form-control" value="{{ $activity }}" placeholder="Enter activity" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="activities-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @elseif(isset($lessonPlan) && $lessonPlan->activities_list)
                @foreach($lessonPlan->activities_list as $activity)
                    <div class="input-group mb-2">
                        <input type="text" name="activities[]" class="form-control" value="{{ $activity }}" placeholder="Enter activity" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="activities-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="addArrayItem('activities-container', 'activities[]')">
            <i class="fas fa-plus me-1"></i> Add Activity
        </button>
    </div>
    
    {{-- Assessment Methods --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-clipboard-check me-2 text-info"></i> Assessment Methods
        </h5>
    </div>
    
    <div class="col-12">
        <div id="assessment-methods-container">
            @if(old('assessment_methods') && is_array(old('assessment_methods')))
                @foreach(old('assessment_methods') as $index => $method)
                    <div class="input-group mb-2">
                        <input type="text" name="assessment_methods[]" class="form-control" value="{{ $method }}" placeholder="Enter assessment method" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="assessment-methods-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @elseif(isset($lessonPlan) && $lessonPlan->assessment_methods_list)
                @foreach($lessonPlan->assessment_methods_list as $method)
                    <div class="input-group mb-2">
                        <input type="text" name="assessment_methods[]" class="form-control" value="{{ $method }}" placeholder="Enter assessment method" />
                        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="assessment-methods-container">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-success btn-sm" onclick="addArrayItem('assessment-methods-container', 'assessment_methods[]')">
            <i class="fas fa-plus me-1"></i> Add Assessment Method
        </button>
    </div>
    
    {{-- Additional Information --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-plus-circle me-2 text-secondary"></i> Additional Information
        </h5>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Homework</label>
        <textarea name="homework" rows="3" class="form-control" placeholder="Enter homework assignment">{{ old('homework', $lessonPlan->homework ?? '') }}</textarea>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Notes</label>
        <textarea name="notes" rows="3" class="form-control" placeholder="Enter additional notes">{{ old('notes', $lessonPlan->notes ?? '') }}</textarea>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Room Requirements</label>
        <textarea name="room_requirements" rows="2" class="form-control" placeholder="Enter room requirements">{{ old('room_requirements', $lessonPlan->room_requirements ?? '') }}</textarea>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Technology Needed</label>
        <textarea name="technology_needed" rows="2" class="form-control" placeholder="Enter technology requirements">{{ old('technology_needed', $lessonPlan->technology_needed ?? '') }}</textarea>
    </div>
    
    <div class="col-12">
        <label class="form-label">Special Considerations</label>
        <textarea name="special_considerations" rows="2" class="form-control" placeholder="Enter special considerations or accommodations">{{ old('special_considerations', $lessonPlan->special_considerations ?? '') }}</textarea>
    </div>
    
    {{-- Scheduling and Status --}}
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3 mt-4">
            <i class="fas fa-calendar-alt me-2 text-primary"></i> Scheduling and Status
        </h5>
    </div>
    
    <div class="col-md-4">
        <label class="form-label">Planned Date</label>
        <input type="date" name="planned_date" class="form-control" value="{{ old('planned_date', isset($lessonPlan->planned_date) ? $lessonPlan->planned_date->format('Y-m-d') : '') }}" />
    </div>
    
    @if(isset($lessonPlan))
    <div class="col-md-4">
        <label class="form-label">Actual Date</label>
        <input type="date" name="actual_date" class="form-control" value="{{ old('actual_date', isset($lessonPlan->actual_date) ? $lessonPlan->actual_date->format('Y-m-d') : '') }}" />
    </div>
    @endif
    
    <div class="col-md-4">
        <label class="form-label">Completion Status <span class="text-danger">*</span></label>
        <select name="completion_status" class="form-select" required>
            <option value="planned" @selected(old('completion_status', $lessonPlan->completion_status ?? 'planned') == 'planned')>Planned</option>
            <option value="in_progress" @selected(old('completion_status', $lessonPlan->completion_status ?? 'planned') == 'in_progress')>In Progress</option>
            <option value="completed" @selected(old('completion_status', $lessonPlan->completion_status ?? 'planned') == 'completed')>Completed</option>
            <option value="postponed" @selected(old('completion_status', $lessonPlan->completion_status ?? 'planned') == 'postponed')>Postponed</option>
            <option value="cancelled" @selected(old('completion_status', $lessonPlan->completion_status ?? 'planned') == 'cancelled')>Cancelled</option>
        </select>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Status</label>
        <div class="form-check">
            <input type="checkbox" name="status" class="form-check-input" value="1" @checked(old('status', $lessonPlan->status ?? true)) />
            <label class="form-check-label">Active</label>
        </div>
    </div>
    
    {{-- Form Actions --}}
    <div class="col-12 text-end mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save Lesson Plan
        </button>
        <a href="{{ route('admin.academic.lesson-plans.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </div>
</div>

<script>
function addArrayItem(containerId, inputName) {
    const container = document.getElementById(containerId);
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2';
    newItem.innerHTML = `
        <input type="text" name="${inputName}" class="form-control" placeholder="Enter item" />
        <button type="button" class="btn btn-outline-danger remove-array-item" data-target="${containerId}">
            <i class="fas fa-minus"></i>
        </button>
    `;
    container.appendChild(newItem);
}

// Remove array item functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-array-item')) {
        const target = e.target.getAttribute('data-target');
        const container = document.getElementById(target);
        if (container.children.length > 1) {
            e.target.closest('.input-group').remove();
        }
    }
});
</script>
