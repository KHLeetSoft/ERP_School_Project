@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Attendance Reports</h6>
            <a href="{{ route('admin.attendance.reports.dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bx bx-bar-chart"></i> Dashboard</a>
        </div>
        <div class="card-body">
            <form class="row g-2 align-items-end mb-3" method="GET">
                <div class="col-md-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $start }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $end }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="both" @selected($type==='both')>Both</option>
                        <option value="staff" @selected($type==='staff')>Staff</option>
                        <option value="students" @selected($type==='students')>Students</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-primary"><i class="bx bx-filter-alt"></i> Filter</button>
                    <a class="btn btn-success" href="{{ route('admin.attendance.reports.export', request()->query()) }}"><i class="bx bx-download"></i> Export</a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            @if(in_array($type, ['both','staff']))
                                <th>Staff Present</th>
                                <th>Staff Absent</th>
                            @endif
                            @if(in_array($type, ['both','students']))
                                <th>Student Present</th>
                                <th>Student Absent</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($r['date'])->format('d M Y') }}</td>
                                @if(in_array($type, ['both','staff']))
                                    <td>{{ $r['staff_present'] ?? 0 }}</td>
                                    <td>{{ $r['staff_absent'] ?? 0 }}</td>
                                @endif
                                @if(in_array($type, ['both','students']))
                                    <td>{{ $r['student_present'] ?? 0 }}</td>
                                    <td>{{ $r['student_absent'] ?? 0 }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


