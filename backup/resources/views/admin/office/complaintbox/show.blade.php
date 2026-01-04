@extends('admin.layout.app')

@section('title','Complaint Details')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Complaint Details</h4>
    <div>
      <a href="{{ route('admin.office.complaintbox.edit', $complaint->id) }}" class="btn btn-primary btn-sm"><i class="bx bx-edit"></i> Edit</a>
      <a href="{{ route('admin.office.complaintbox.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr><th width="20%">Complain By</th><td>{{ $complaint->complain_by }}</td></tr>
      <tr><th>Phone</th><td>{{ $complaint->phone }}</td></tr>
      <tr><th>Purpose</th><td>{{ $complaint->purpose }}</td></tr>
      <tr><th>Date</th><td>{{ $complaint->date }}</td></tr>
      <tr><th>Note</th><td>{{ $complaint->note }}</td></tr>
      @if($complaint->attachment)
      <tr>
        <th>Attachment</th>
        <td>
          <a href="{{ Storage::url($complaint->attachment) }}" target="_blank" class="btn btn-sm btn-info">
            <i class="bx bx-download"></i> Download Attachment
          </a>
        </td>
      </tr>
      @endif
    </table>
  </div>
</div>
@endsection