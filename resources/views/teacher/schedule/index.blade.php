@extends('teacher.layout.app')

@section('title', 'Schedule')
@section('page-title', 'Class Schedule')
@section('page-description', 'Manage your teaching schedule and timetable')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Periods</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $scheduleStats['total_periods'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-success shadow h-100 py-2" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">This Week</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $scheduleStats['this_week'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-calendar-week text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-warning shadow h-100 py-2" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Today</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $scheduleStats['today_periods'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-info shadow h-100 py-2" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Next Class</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if($scheduleStats['next_class'])
                                {{ $scheduleStats['next_class']->start_time->format('H:i') }}
                            @else
                                None
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.schedule.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            Add Schedule
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.schedule.today') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-calendar-day me-2"></i>
                            Today's Schedule
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.schedule.weekly') }}" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-calendar-week me-2"></i>
                            Weekly View
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-warning btn-lg w-100" onclick="printSchedule()">
                            <i class="fas fa-print me-2"></i>
                            Print Schedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Weekly Schedule Grid -->
<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    Weekly Schedule ({{ $startOfWeek->format('M d') }} - {{ $endOfWeek->format('M d, Y') }})
                </h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" onclick="refreshSchedule()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered schedule-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="time-column">Time</th>
                                @foreach($days as $day)
                                <th class="day-column {{ strtolower(now()->format('l')) === $day ? 'today' : '' }}">
                                    {{ ucfirst($day) }}
                                    @if(strtolower(now()->format('l')) === $day)
                                        <small class="text-muted d-block">Today</small>
                                    @endif
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $timeSlots = [
                                    '08:00', '09:00', '10:00', '11:00', '12:00', 
                                    '13:00', '14:00', '15:00', '16:00', '17:00'
                                ];
                            @endphp
                            
                            @foreach($timeSlots as $time)
                            <tr>
                                <td class="time-slot">{{ $time }}</td>
                                @foreach($days as $day)
                                <td class="schedule-cell {{ strtolower(now()->format('l')) === $day ? 'today' : '' }}">
                                    @if(isset($schedules[$day]))
                                        @foreach($schedules[$day] as $schedule)
                                            @if($schedule->start_time->format('H:i') === $time)
                                                <div class="schedule-item {{ $schedule->isCurrentTime() ? 'current' : '' }}" 
                                                     data-schedule-id="{{ $schedule->id }}">
                                                    <div class="schedule-header">
                                                        <strong>{{ $schedule->class_name }}</strong>
                                                        {!! $schedule->status_badge !!}
                                                    </div>
                                                    <div class="schedule-subject">{{ $schedule->subject_name }}</div>
                                                    <div class="schedule-time">{{ $schedule->time_slot }}</div>
                                                    @if($schedule->room_number)
                                                        <div class="schedule-room">
                                                            <i class="fas fa-door-open me-1"></i>{{ $schedule->room_number }}
                                                        </div>
                                                    @endif
                                                    <div class="schedule-actions">
                                                        <a href="{{ route('teacher.schedule.show', $schedule) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('teacher.schedule.edit', $schedule) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule List -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Schedules</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="schedulesTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules->flatten() as $schedule)
                            <tr class="{{ $schedule->isCurrentTime() ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $schedule->class_name }}</strong>
                                    @if($schedule->description)
                                        <br><small class="text-muted">{{ Str::limit($schedule->description, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $schedule->subject_name }}</span>
                                </td>
                                <td>
                                    <strong>{{ $schedule->day_name }}</strong>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $schedule->time_slot }}</strong>
                                        <br><small class="text-muted">{{ $schedule->getDurationFormatted() }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($schedule->room_number)
                                        <span class="badge bg-info">
                                            <i class="fas fa-door-open me-1"></i>{{ $schedule->room_number }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $schedule->type_badge !!}
                                </td>
                                <td>
                                    {!! $schedule->status_badge !!}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teacher.schedule.show', $schedule) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher.schedule.edit', $schedule) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teacher.schedule.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No schedules found</h5>
                                        <p class="text-muted">Start by creating your class schedule.</p>
                                        <a href="{{ route('teacher.schedule.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Add First Schedule
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
// Initialize AOS
AOS.init({
    duration: 1000,
    once: true
});

// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
}

// Initialize counters when page loads
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
    
    // Highlight current time slot
    highlightCurrentTime();
    
    // Auto-refresh every 5 minutes
    setInterval(highlightCurrentTime, 300000);
});

// Highlight current time slot
function highlightCurrentTime() {
    const now = new Date();
    const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    const currentDay = now.toLocaleLowerCase().substring(0, 3) + 'day';
    
    // Remove previous highlights
    document.querySelectorAll('.current-time').forEach(el => {
        el.classList.remove('current-time');
    });
    
    // Find and highlight current time slot
    const timeSlots = document.querySelectorAll('.time-slot');
    timeSlots.forEach(slot => {
        if (slot.textContent.trim() === currentTime) {
            slot.parentElement.classList.add('current-time');
        }
    });
}

// Print schedule
function printSchedule() {
    window.print();
}

// Refresh schedule
function refreshSchedule() {
    location.reload();
}

// Add hover effects to schedule items
document.querySelectorAll('.schedule-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.02)';
        this.style.transition = 'transform 0.2s ease';
    });
    
    item.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
});

// Add click effect to schedule cells
document.querySelectorAll('.schedule-cell').forEach(cell => {
    cell.addEventListener('click', function() {
        if (this.querySelector('.schedule-item')) {
            const scheduleId = this.querySelector('.schedule-item').getAttribute('data-schedule-id');
            if (scheduleId) {
                window.location.href = `/teacher/schedule/${scheduleId}`;
            }
        }
    });
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stats-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.schedule-table {
    font-size: 0.9rem;
}

.time-column {
    width: 80px;
    background-color: #f8f9fa;
    font-weight: bold;
}

.day-column {
    width: 120px;
    text-align: center;
    background-color: #f8f9fa;
    font-weight: bold;
}

.day-column.today {
    background-color: #e3f2fd;
    color: #1976d2;
}

.time-slot {
    background-color: #f8f9fa;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
}

.schedule-cell {
    height: 80px;
    vertical-align: top;
    padding: 5px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.schedule-cell:hover {
    background-color: #f8f9fa;
}

.schedule-cell.today {
    background-color: #e8f5e8;
}

.schedule-cell.current-time {
    background-color: #fff3cd;
    border: 2px solid #ffc107;
}

.schedule-item {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 2px;
    font-size: 0.8rem;
    position: relative;
    cursor: pointer;
    transition: all 0.2s ease;
}

.schedule-item:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.schedule-item.current {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.schedule-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.schedule-subject {
    font-size: 0.75rem;
    opacity: 0.9;
    margin-bottom: 2px;
}

.schedule-time {
    font-size: 0.7rem;
    opacity: 0.8;
    margin-bottom: 2px;
}

.schedule-room {
    font-size: 0.7rem;
    opacity: 0.8;
    margin-bottom: 4px;
}

.schedule-actions {
    position: absolute;
    top: 2px;
    right: 2px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.schedule-item:hover .schedule-actions {
    opacity: 1;
}

.schedule-actions .btn {
    padding: 2px 4px;
    font-size: 0.6rem;
    margin-left: 1px;
}

.empty-state {
    padding: 2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .schedule-table {
        font-size: 0.8rem;
    }
    
    .day-column {
        width: 100px;
    }
    
    .schedule-cell {
        height: 60px;
    }
    
    .schedule-item {
        padding: 4px;
        font-size: 0.7rem;
    }
    
    .schedule-actions {
        position: static;
        opacity: 1;
        margin-top: 4px;
    }
}

@media print {
    .card-header,
    .btn,
    .schedule-actions {
        display: none !important;
    }
    
    .schedule-table {
        font-size: 0.8rem;
    }
    
    .schedule-item {
        background: #f8f9fa !important;
        color: #000 !important;
        border: 1px solid #dee2e6;
    }
}
</style>
@endsection
