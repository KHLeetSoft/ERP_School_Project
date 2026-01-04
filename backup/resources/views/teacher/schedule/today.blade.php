@extends('teacher.layout.app')

@section('title', "Today's Schedule")
@section('page-title', "Today's Schedule")
@section('page-description', 'Your classes for today')

@section('content')
<div class="card modern-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="fas fa-calendar-day me-2"></i>Classes Today</h5>
        <a href="{{ route('teacher.schedule.weekly') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-calendar-week me-1"></i>Weekly View
        </a>
    </div>
    <div class="card-body">
        @if(isset($schedules) && $schedules->count())
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Room</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $item)
                            <tr>
                                <td><strong>{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}</strong></td>
                                <td>{{ $item->subject_name }}</td>
                                <td>{{ $item->class_name }}</td>
                                <td>{{ $item->room_number ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($item->schedule_type ?? 'regular') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">No classes scheduled for today. Coming soon… enhanced filters and live timer.</div>
        @endif
    </div>
</div>
@endsection


