<?php

namespace App\Imports;

use App\Models\LeaveManagement;
use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveManagementImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsErrors
{
    protected $schoolId;
    protected $errors = [];

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function model(array $row)
    {
        // Find staff by employee ID
        $staff = Staff::where('school_id', $this->schoolId)
            ->where('employee_id', $row['employee_id'])
            ->first();

        if (!$staff) {
            $this->errors[] = "Staff with Employee ID '{$row['employee_id']}' not found.";
            return null;
        }

        // Parse dates
        $startDate = $this->parseDate($row['start_date']);
        $endDate = $this->parseDate($row['end_date']);

        if (!$startDate || !$endDate) {
            $this->errors[] = "Invalid date format for Employee ID '{$row['employee_id']}'. Expected format: dd/mm/yyyy";
            return null;
        }

        if ($startDate > $endDate) {
            $this->errors[] = "Start date cannot be after end date for Employee ID '{$row['employee_id']}'";
            return null;
        }

        // Check for overlapping leaves
        $overlappingLeave = LeaveManagement::where('staff_id', $staff->id)
            ->where('school_id', $this->schoolId)
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->first();

        if ($overlappingLeave) {
            $this->errors[] = "Leave request overlaps with existing approved or pending leave for Employee ID '{$row['employee_id']}'";
            return null;
        }

        // Calculate total days
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $halfDay = $this->parseBoolean($row['half_day'] ?? false);
        $halfDayType = null;

        if ($halfDay && $startDate->isSameDay($endDate)) {
            $totalDays = 0.5;
            $halfDayType = $this->normalizeHalfDayType($row['half_day_type'] ?? 'morning');
        }

        // Normalize leave type and status
        $leaveType = $this->normalizeLeaveType($row['leave_type'] ?? 'casual');
        $status = $this->normalizeStatus($row['status'] ?? 'pending');

        // Parse approval/rejection data
        $approvedBy = null;
        $approvedAt = null;
        $rejectedBy = null;
        $rejectedAt = null;

        if ($status === 'approved' && !empty($row['approved_by'])) {
            $approvedBy = $this->findUserByName($row['approved_by']);
            $approvedAt = $this->parseDateTime($row['approved_at'] ?? now());
        }

        if ($status === 'rejected' && !empty($row['rejected_by'])) {
            $rejectedBy = $this->findUserByName($row['rejected_by']);
            $rejectedAt = $this->parseDateTime($row['rejected_at'] ?? now());
        }

        return new LeaveManagement([
            'school_id' => $this->schoolId,
            'staff_id' => $staff->id,
            'leave_type' => $leaveType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $row['reason'] ?? 'Imported from file',
            'status' => $status,
            'half_day' => $halfDay,
            'half_day_type' => $halfDayType,
            'emergency_contact' => $row['emergency_contact'] ?? null,
            'emergency_contact_phone' => $row['emergency_contact_phone'] ?? null,
            'address_during_leave' => $row['address_during_leave'] ?? null,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedAt,
            'rejected_by' => $rejectedBy,
            'rejected_at' => $rejectedAt,
            'rejection_reason' => $row['rejection_reason'] ?? null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'leave_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'reason' => 'required|string|max:1000',
            'status' => 'nullable|in:pending,approved,rejected,cancelled',
            'half_day' => 'nullable|boolean',
            'half_day_type' => 'nullable|in:morning,afternoon',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'address_during_leave' => 'nullable|string|max:500',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.required' => 'Employee ID is required.',
            'leave_type.required' => 'Leave type is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.required' => 'End date is required.',
            'reason.required' => 'Reason is required.',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function parseDate($value)
    {
        if (empty($value) || $value === 'N/A') {
            return null;
        }

        // Try different date formats
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y', 'd.m.Y'];
        
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }

    private function parseDateTime($value)
    {
        if (empty($value) || $value === 'N/A') {
            return now();
        }

        // Try different datetime formats
        $formats = ['d/m/Y H:i:s', 'd-m-Y H:i:s', 'Y-m-d H:i:s', 'd/m/Y H:i', 'd-m-Y H:i'];
        
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Exception $e) {
                continue;
            }
        }

        // If datetime parsing fails, try date parsing
        $date = $this->parseDate($value);
        return $date ? $date->setTime(0, 0, 0) : now();
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = strtolower(trim($value));
            return in_array($value, ['yes', 'true', '1', 'on']);
        }

        return (bool) $value;
    }

    private function normalizeLeaveType($type)
    {
        $typeMap = [
            'casual' => 'casual',
            'casual leave' => 'casual',
            'sick' => 'sick',
            'sick leave' => 'sick',
            'annual' => 'annual',
            'annual leave' => 'annual',
            'maternity' => 'maternity',
            'maternity leave' => 'maternity',
            'paternity' => 'paternity',
            'paternity leave' => 'paternity',
            'bereavement' => 'bereavement',
            'bereavement leave' => 'bereavement',
            'study' => 'study',
            'study leave' => 'study',
            'other' => 'other',
            'other leave' => 'other',
        ];

        $normalized = strtolower(trim($type));
        return $typeMap[$normalized] ?? 'casual';
    }

    private function normalizeStatus($status)
    {
        $statusMap = [
            'pending' => 'pending',
            'approved' => 'approved',
            'rejected' => 'rejected',
            'cancelled' => 'cancelled',
            'canceled' => 'cancelled',
        ];

        $normalized = strtolower(trim($status));
        return $statusMap[$normalized] ?? 'pending';
    }

    private function normalizeHalfDayType($type)
    {
        $typeMap = [
            'morning' => 'morning',
            'am' => 'morning',
            'afternoon' => 'afternoon',
            'pm' => 'afternoon',
        ];

        $normalized = strtolower(trim($type));
        return $typeMap[$normalized] ?? 'morning';
    }

    private function findUserByName($name)
    {
        // This is a simplified implementation
        // In a real application, you might want to implement proper user lookup
        return Auth::id();
    }
}
