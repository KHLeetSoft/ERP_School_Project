@extends('admin.layout.app')

@section('title', 'Room Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Room Details - {{ $room->room_no }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.rooms.edit', $room->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.accommodation.rooms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Rooms
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Room Number:</th>
                                    <td>{{ $room->room_no }}</td>
                                </tr>
                                <tr>
                                    <th>Hostel:</th>
                                    <td>{{ $room->hostel->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $room->type ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Capacity:</th>
                                    <td>{{ $room->capacity }} students</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>{{ $room->gender ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Floor:</th>
                                    <td>{{ $room->floor ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $room->status == 'available' ? 'success' : ($room->status == 'maintenance' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $room->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated:</th>
                                    <td>{{ $room->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($room->notes)
                    <div class="row">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p class="text-muted">{{ $room->notes }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($room->assignments->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Current Assignments:</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Bed No</th>
                                            <th>Join Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($room->assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->student->user->name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->bed_no ?? 'N/A' }}</td>
                                            <td>{{ $assignment->join_date ? $assignment->join_date->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $assignment->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
