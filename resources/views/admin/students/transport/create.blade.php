@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4>Assign Transport</h4>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.transport.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}">{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Route</label>
                        <select name="route_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($routes as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}">{{ $v->vehicle_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pickup Point</label>
                        <input type="text" name="pickup_point" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Drop Point</label>
                        <input type="text" name="drop_point" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fare</label>
                        <input type="number" step="0.01" name="fare" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a href="{{ route('admin.students.transport.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


