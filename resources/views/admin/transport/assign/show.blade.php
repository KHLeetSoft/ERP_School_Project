@extends('admin.layout.app')

@section('title', 'Transport Assignment Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Transport Assignment #{{ $assignment->id }}</h4>
                    <div class="float-end">
                        <a href="{{ route('admin.transport.assign.edit', $assignment->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.transport.assign.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Assignment Info -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5><i class="fas fa-info-circle"></i> Assignment Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Assignment ID:</strong></td>
                                            <td>#{{ $assignment->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date:</strong></td>
                                            <td>{{ $assignment->assignment_date->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Start Time:</strong></td>
                                            <td>{{ $assignment->start_time->format('H:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>End Time:</strong></td>
                                            <td>{{ $assignment->end_time->format('H:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Shift Type:</strong></td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $assignment->shift_type)) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : ($assignment->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($assignment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle & Route Info -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5><i class="fas fa-bus"></i> Vehicle & Route</h5>
                                </div>
                                <div class="card-body">
                                    <h6>Vehicle Information</h6>
                                    @if($assignment->vehicle)
                                        <p><strong>Vehicle:</strong> {{ $assignment->vehicle->vehicle_number }}</p>
                                        <p><strong>Brand/Model:</strong> {{ $assignment->vehicle->brand }} {{ $assignment->vehicle->model }}</p>
                                        <p><strong>Registration:</strong> {{ $assignment->vehicle->registration_number }}</p>
                                    @else
                                        <p class="text-muted">No vehicle assigned</p>
                                    @endif

                                    <hr>
                                    <h6>Route Information</h6>
                                    @if($assignment->route)
                                        <p><strong>Route:</strong> {{ $assignment->route->route_name }}</p>
                                        <p><strong>Route Number:</strong> {{ $assignment->route->route_number }}</p>
                                        @if($assignment->route->start_location && $assignment->route->end_location)
                                            <p><strong>Path:</strong> {{ $assignment->route->start_location }} → {{ $assignment->route->end_location }}</p>
                                        @endif
                                    @else
                                        <p class="text-muted">No route assigned</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Information -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5><i class="fas fa-users"></i> Staff Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Driver</h6>
                                            @if($assignment->driver)
                                                <p><strong>Name:</strong> {{ $assignment->driver->name }}</p>
                                                <p><strong>Email:</strong> {{ $assignment->driver->email }}</p>
                                            @else
                                                <p class="text-muted">No driver assigned</p>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <h6>Conductor</h6>
                                            @if($assignment->conductor)
                                                <p><strong>Name:</strong> {{ $assignment->conductor->name }}</p>
                                                <p><strong>Email:</strong> {{ $assignment->conductor->email }}</p>
                                            @else
                                                <p class="text-muted">No conductor assigned</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes & Additional Info -->
                    @if($assignment->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5><i class="fas fa-sticky-note"></i> Notes</h5>
                                </div>
                                <div class="card-body">
                                    <p>{{ $assignment->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- System Information -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5><i class="fas fa-cog"></i> System Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Created:</strong> {{ $assignment->created_at->format('M d, Y H:i A') }}</p>
                                            @if($assignment->createdBy)
                                                <p><strong>Created By:</strong> {{ $assignment->createdBy->name }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <p><strong>Last Updated:</strong> {{ $assignment->updated_at->format('M d, Y H:i A') }}</p>
                                            @if($assignment->updatedBy)
                                                <p><strong>Updated By:</strong> {{ $assignment->updatedBy->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
