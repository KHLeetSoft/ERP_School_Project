@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Category Details Card -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Category Details</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.finance.expense-categories.edit', $expenseCategory->id) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.finance.expense-categories.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle me-3" style="width: 60px; height: 60px; background-color: {{ $expenseCategory->color }}; display: flex; align-items: center; justify-content: center;">
                                    <i class="{{ $expenseCategory->icon }} text-white fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $expenseCategory->name }}</h5>
                                    <span class="badge bg-secondary">{{ $expenseCategory->code }}</span>
                                </div>
                            </div>
                            
                            @if($expenseCategory->description)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description:</label>
                                    <p class="mb-0 text-muted">{{ $expenseCategory->description }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small">Budget Limit</div>
                                        <div class="fs-5 fw-bold text-primary">
                                            {{ $expenseCategory->budget_limit ? '₹' . number_format($expenseCategory->budget_limit, 2) : 'No Limit' }}
                                        </div>
                                        @if($expenseCategory->budget_limit)
                                            <div class="small text-muted">{{ ucfirst($expenseCategory->budget_period) }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small">Total Expenses</div>
                                        <div class="fs-5 fw-bold text-success">
                                            ₹{{ number_format($expenseCategory->total_expenses, 2) }}
                                        </div>
                                        <div class="small text-muted">{{ $expenseCategory->expenses->count() }} transactions</div>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small">Monthly Expenses</div>
                                        <div class="fs-5 fw-bold text-info">
                                            ₹{{ number_format($expenseCategory->monthly_expenses, 2) }}
                                        </div>
                                        <div class="small text-muted">This month</div>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small">Status</div>
                                        <div class="fs-5 fw-bold">
                                            @if($expenseCategory->is_active)
                                                <span class="text-success">Active</span>
                                            @else
                                                <span class="text-danger">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="small text-muted">
                                            @if($expenseCategory->budget_limit)
                                                @php $utilization = $expenseCategory->budget_utilization; @endphp
                                                <span class="badge bg-{{ $expenseCategory->status_color }}">{{ $utilization }}%</span>
                                            @else
                                                No budget
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Expenses Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Recent Expenses</h6>
                    <a href="{{ route('admin.finance.expenses.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Expenses
                    </a>
                </div>
                <div class="card-body">
                    @if($expenseCategory->expenses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Vendor</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expenseCategory->expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                            <td>{{ $expense->vendor }}</td>
                                            <td>{{ Str::limit($expense->description, 50) }}</td>
                                            <td class="fw-bold">₹{{ number_format($expense->amount, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'approved' => 'info',
                                                        'paid' => 'success',
                                                        'void' => 'danger'
                                                    ];
                                                    $color = $statusColors[$expense->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ ucfirst($expense->status) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-receipt fs-1 text-muted"></i>
                            <p class="text-muted mt-2">No expenses found for this category yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Budget Utilization Card -->
            @if($expenseCategory->budget_limit)
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Budget Utilization</h6>
                    </div>
                    <div class="card-body text-center">
                        @php $utilization = $expenseCategory->budget_utilization; @endphp
                        <div class="mb-3">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $expenseCategory->status_color }}" 
                                     role="progressbar" 
                                     style="width: {{ min($utilization, 100) }}%"
                                     aria-valuenow="{{ $utilization }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $utilization }}%
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted small">Used</div>
                                    <div class="fw-bold text-{{ $expenseCategory->status_color }}">
                                        ₹{{ number_format($expenseCategory->monthly_expenses, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-2">
                                    <div class="text-muted small">Remaining</div>
                                    <div class="fw-bold">
                                        @php $remaining = max(0, $expenseCategory->budget_limit - $expenseCategory->monthly_expenses); @endphp
                                        ₹{{ number_format($remaining, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($utilization > 100)
                            <div class="alert alert-danger mt-3">
                                <i class="bx bx-error-circle me-1"></i>
                                Budget exceeded by {{ number_format($utilization - 100, 1) }}%
                            </div>
                        @elseif($utilization > 90)
                            <div class="alert alert-warning mt-3">
                                <i class="bx bx-error me-1"></i>
                                Budget utilization is {{ $utilization }}%
                            </div>
                        @elseif($utilization > 75)
                            <div class="alert alert-info mt-3">
                                <i class="bx bx-info-circle me-1"></i>
                                Budget utilization is {{ $utilization }}%
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Category Info Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Category Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Created:</label>
                        <p class="mb-1 text-muted">{{ $expenseCategory->created_at->format('d M Y, h:i A') }}</p>
                        @if($expenseCategory->creator)
                            <small class="text-muted">by {{ $expenseCategory->creator->name }}</small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Last Updated:</label>
                        <p class="mb-1 text-muted">{{ $expenseCategory->updated_at->format('d M Y, h:i A') }}</p>
                        @if($expenseCategory->updater)
                            <small class="text-muted">by {{ $expenseCategory->updater->name }}</small>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon Class:</label>
                        <p class="mb-1 text-muted">{{ $expenseCategory->icon }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Color Code:</label>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-2" style="width: 20px; height: 20px; background-color: {{ $expenseCategory->color }}"></div>
                            <span class="text-muted">{{ $expenseCategory->color }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 