@extends('admin.layout.app')

@section('title', 'Hostel Rooms')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hostel Rooms Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.accommodation.rooms.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Room
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Room No</th>
                                    <th>Hostel</th>
                                    <th>Type</th>
                                    <th>Capacity</th>
                                    <th>Gender</th>
                                    <th>Floor</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $room)
                                <tr>
                                    <td>{{ $room->room_no }}</td>
                                    <td>{{ $room->hostel->name ?? 'N/A' }}</td>
                                    <td>{{ $room->type ?? 'N/A' }}</td>
                                    <td>{{ $room->capacity }}</td>
                                    <td>{{ $room->gender ?? 'N/A' }}</td>
                                    <td>{{ $room->floor ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $room->status == 'available' ? 'success' : ($room->status == 'maintenance' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($room->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.accommodation.rooms.show', $room->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accommodation.rooms.edit', $room->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRoom({{ $room->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No rooms found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($rooms->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $rooms->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteRoom(id) {
    if (confirm('Are you sure you want to delete this room?')) {
        fetch(`/admin/accommodation/rooms/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error deleting room');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting room');
        });
    }
}
</script>
@endsection
