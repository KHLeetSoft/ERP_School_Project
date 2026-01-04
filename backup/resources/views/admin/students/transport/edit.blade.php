@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4>Edit Transport Assignment</h4>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.transport.update', $record->id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Student</label>
                        <select name="student_id" class="form-control" required>
                            @foreach($students as $s)
                                <option value="{{ $s->id }}" {{ $record->student_id == $s->id ? 'selected' : '' }}>{{ trim(($s->first_name.' '.$s->last_name)) ?: ($s->user->name ?? 'Student #'.$s->id) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $record->class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Route</label>
                        <select name="route_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($routes as $r)
                                <option value="{{ $r->id }}" {{ $record->route_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Vehicle</label>
                        <select name="vehicle_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($vehicles as $v)
                                <option value="{{ $v->id }}" {{ $record->vehicle_id == $v->id ? 'selected' : '' }}>{{ $v->vehicle_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pickup Point</label>
                        <input type="text" name="pickup_point" class="form-control" value="{{ $record->pickup_point }}"/>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Drop Point</label>
                        <input type="text" name="drop_point" class="form-control" value="{{ $record->drop_point }}"/>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $record->start_date }}"/>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $record->end_date }}"/>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fare</label>
                        <input type="number" step="0.01" name="fare" class="form-control" value="{{ $record->fare }}"/>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{ $record->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $record->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ $record->remarks }}</textarea>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a href="{{ route('admin.students.transport.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


