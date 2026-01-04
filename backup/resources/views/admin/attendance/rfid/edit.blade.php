@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit RFID Record</h6>
                    <a href="{{ route('admin.attendance.rfid.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attendance.rfid.update', $rfid->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Staff *</label>
                            <select name="user_id" class="form-select" required>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected($rfid->user_id==$u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Card UID *</label>
                            <input type="text" name="card_uid" class="form-control" value="{{ $rfid->card_uid }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Timestamp *</label>
                            <input type="datetime-local" name="timestamp" class="form-control" value="{{ $rfid->timestamp->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Direction *</label>
                            <select name="direction" class="form-select" required>
                                @foreach(['in','out'] as $d)
                                    <option value="{{ $d }}" @selected($rfid->direction==$d)>{{ ucfirst($d) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Device</label>
                            <input type="text" name="device_name" class="form-control" value="{{ $rfid->device_name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" rows="3" class="form-control">{{ $rfid->remarks }}</textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


