@extends('student.layout.app')

@section('title', 'Room Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-bed me-2"></i>Room Details
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.hostel.index') }}">Hostel</a></li>
                        <li class="breadcrumb-item active">Rooms</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-home me-2"></i>Room Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Room Number:</label>
                                <p class="mb-0 fs-4 text-primary">{{ $roomDetails['room_number'] ?? 'Not Assigned' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Location:</label>
                                <p class="mb-0">{{ $roomDetails['floor'] ?? 'N/A' }}, {{ $roomDetails['block'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Room Type:</label>
                                <p class="mb-0">{{ $roomDetails['room_type'] ?? 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Area:</label>
                                <p class="mb-0">{{ $roomDetails['area'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Room Condition:</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $roomDetails['room_condition'] === 'Good' ? 'success' : 'warning' }} fs-6">
                                        {{ $roomDetails['room_condition'] ?? 'Unknown' }}
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Inspection:</label>
                                <p class="mb-0">{{ $roomDetails['last_inspection'] ? \Carbon\Carbon::parse($roomDetails['last_inspection'])->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Next Inspection:</label>
                                <p class="mb-0">{{ $roomDetails['next_inspection'] ? \Carbon\Carbon::parse($roomDetails['next_inspection'])->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Furniture and Amenities -->
    <div class="row mb-4">
        <!-- Furniture -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chair me-2"></i>Furniture
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($roomDetails['furniture']) && count($roomDetails['furniture']) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Condition</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roomDetails['furniture'] as $item)
                                    <tr>
                                        <td>{{ $item['item'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item['condition'] === 'Good' ? 'success' : ($item['condition'] === 'Fair' ? 'warning' : 'danger') }}">
                                                {{ $item['condition'] }}
                                            </span>
                                        </td>
                                        <td>{{ $item['quantity'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chair fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No furniture information available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Amenities -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-wifi me-2"></i>Amenities
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($roomDetails['amenities']) && count($roomDetails['amenities']) > 0)
                        <div class="row">
                            @foreach($roomDetails['amenities'] as $amenity)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>{{ $amenity }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-wifi fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No amenities information available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Roommates -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Roommates
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($roommates) > 0)
                        <div class="row">
                            @foreach($roommates as $roommate)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-user-circle fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">{{ $roommate['name'] }}</h6>
                                        <p class="card-text text-muted">{{ $roommate['course'] }} - {{ $roommate['year'] }}</p>
                                        <div class="mb-2">
                                            <span class="badge bg-{{ $roommate['status'] === 'Active' ? 'success' : 'warning' }}">
                                                {{ $roommate['status'] }}
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                <a href="tel:{{ $roommate['phone'] }}" class="text-decoration-none">
                                                    {{ $roommate['phone'] }}
                                                </a>
                                            </small>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-envelope me-1"></i>
                                                <a href="mailto:{{ $roommate['email'] }}" class="text-decoration-none">
                                                    {{ $roommate['email'] }}
                                                </a>
                                            </small>
                                        </div>
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Joined: {{ \Carbon\Carbon::parse($roommate['join_date'])->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No roommates found</h5>
                            <p class="text-muted">You are currently the only occupant of this room.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Room Rules -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Room Rules & Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($roomRules) > 0)
                        <div class="row">
                            @foreach($roomRules as $index => $rule)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-primary me-3 mt-1">{{ $index + 1 }}</span>
                                    <p class="mb-0">{{ $rule }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No room rules available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Requests -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2"></i>Maintenance Requests
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($maintenanceRequests) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Date</th>
                                        <th>Issue</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assigned To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($maintenanceRequests as $request)
                                    <tr>
                                        <td class="fw-bold">{{ $request['id'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request['date'])->format('M d, Y') }}</td>
                                        <td>{{ $request['issue'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request['status'] === 'Completed' ? 'success' : ($request['status'] === 'In Progress' ? 'warning' : 'secondary') }}">
                                                {{ $request['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $request['priority'] === 'High' ? 'danger' : ($request['priority'] === 'Medium' ? 'warning' : 'info') }}">
                                                {{ $request['priority'] }}
                                            </span>
                                        </td>
                                        <td>{{ $request['assigned_to'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No maintenance requests found</h5>
                            <p class="text-muted">Your maintenance requests will appear here once submitted.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
