@extends('admin.layout.app')

@section('title', 'Fee Structures')
@section('page-title', 'Fee Structures Management')
@section('page-description', 'Manage fee structures for different classes and students')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card modern-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Fee Structures
                </h5>
                <div class="card-tools">
                    <a href="{{ route('admin.fee-structures.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Fee Structure
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Fee Head</th>
                                <th>Class/Section</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Frequency</th>
                                <th>Effective Period</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($feeStructures as $feeStructure)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $feeStructure->name }}</strong>
                                            @if($feeStructure->description)
                                                <br><small class="text-muted">{{ Str::limit($feeStructure->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $feeStructure->feeHead->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($feeStructure->schoolClass)
                                            <span class="badge bg-primary">{{ $feeStructure->schoolClass->name }}</span>
                                        @endif
                                        @if($feeStructure->section)
                                            <br><small class="text-muted">{{ $feeStructure->section->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($feeStructure->student)
                                            <span class="badge bg-success">{{ $feeStructure->student->first_name }} {{ $feeStructure->student->last_name }}</span>
                                        @else
                                            <span class="text-muted">All Students</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $feeStructure->formatted_amount }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $feeStructure->frequency_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $feeStructure->effective_from->format('M d, Y') }}
                                            @if($feeStructure->effective_to)
                                                <br>to {{ $feeStructure->effective_to->format('M d, Y') }}
                                            @else
                                                <br><span class="text-success">Ongoing</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($feeStructure->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.fee-structures.show', $feeStructure) }}" 
                                               class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.fee-structures.edit', $feeStructure) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.fee-structures.destroy', $feeStructure) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this fee structure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No fee structures found.</p>
                                        <a href="{{ route('admin.fee-structures.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Add First Fee Structure
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($feeStructures->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $feeStructures->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
