<?php

namespace App\Exports;

use App\Models\LeaveManagement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeaveManagementExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schoolId;
    protected $filters;

    public function __construct($schoolId, $filters = [])
    {
        $this->schoolId = $schoolId;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = LeaveManagement::with(['staff', 'approvedBy', 'rejectedBy', 'createdBy'])
            ->where('school_id', $this->schoolId);

        // Apply filters
        if (!empty($this->filters['leave_type'])) {
            $query->where('leave_type', $this->filters['leave_type']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['staff_id'])) {
            $query->where('staff_id', $this->filters['staff_id']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->where('start_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->where('end_date', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('start_date', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee ID',
            'Employee Name',
            'Leave Type',
            'Start Date',
            'End Date',
            'Total Days',
            'Reason',
            'Status',
            'Half Day',
            'Half Day Type',
            'Emergency Contact',
            'Emergency Contact Phone',
            'Address During Leave',
            'Approved By',
            'Approved At',
            'Rejected By',
            'Rejected At',
            'Rejection Reason',
            'Created By',
            'Created At',
            'Updated By',
            'Updated At'
        ];
    }

    public function map($leave): array
    {
        return [
            $leave->id,
            $leave->staff ? $leave->staff->employee_id : 'N/A',
            $leave->staff ? $leave->staff->first_name . ' ' . $leave->staff->last_name : 'N/A',
            $leave->leave_type_label,
            $leave->start_date->format('d/m/Y'),
            $leave->end_date->format('d/m/Y'),
            $leave->total_days,
            $leave->reason,
            ucfirst($leave->status),
            $leave->half_day ? 'Yes' : 'No',
            $leave->half_day_type ? ucfirst($leave->half_day_type) : 'N/A',
            $leave->emergency_contact ?? 'N/A',
            $leave->emergency_contact_phone ?? 'N/A',
            $leave->address_during_leave ?? 'N/A',
            $leave->approvedBy ? $leave->approvedBy->name : 'N/A',
            $leave->approved_at ? $leave->approved_at->format('d/m/Y H:i:s') : 'N/A',
            $leave->rejectedBy ? $leave->rejectedBy->name : 'N/A',
            $leave->rejected_at ? $leave->rejected_at->format('d/m/Y H:i:s') : 'N/A',
            $leave->rejection_reason ?? 'N/A',
            $leave->createdBy ? $leave->createdBy->name : 'N/A',
            $leave->created_at->format('d/m/Y H:i:s'),
            $leave->updatedBy ? $leave->updatedBy->name : 'N/A',
            $leave->updated_at->format('d/m/Y H:i:s')
        ];
    }
}
