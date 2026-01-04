<?php

namespace App\Imports;

use App\Models\ParentCommunication;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class ParentCommunicationImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsErrors, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        // Find parent detail by name or create if not exists
        $parentDetail = $this->findParentDetail($row['parent_name'] ?? '');
        if (!$parentDetail) {
            Log::warning("Parent not found: " . ($row['parent_name'] ?? 'N/A'));
            return null;
        }

        // Find student by name if provided
        $studentId = null;
        if (!empty($row['student_name']) && $row['student_name'] !== 'N/A') {
            $student = $this->findStudent($row['student_name']);
            $studentId = $student ? $student->id : null;
        }

        // Find admin by name if provided
        $adminId = null;
        if (!empty($row['admin_name']) && $row['admin_name'] !== 'N/A') {
            $admin = User::where('name', $row['admin_name'])->first();
            $adminId = $admin ? $admin->id : null;
        }

        // Parse dates
        $sentAt = $this->parseDate($row['sent_date'] ?? '');
        $deliveredAt = $this->parseDate($row['delivered_date'] ?? '');
        $readAt = $this->parseDate($row['read_date'] ?? '');
        $responseAt = $this->parseDate($row['response_date'] ?? '');

        return new ParentCommunication([
            'parent_detail_id' => $parentDetail->id,
            'student_id' => $studentId,
            'admin_id' => $adminId,
            'communication_type' => strtolower($row['communication_type'] ?? 'email'),
            'subject' => $row['subject'] ?? null,
            'message' => $row['message'] ?? '',
            'status' => strtolower($row['status'] ?? 'sent'),
            'sent_at' => $sentAt,
            'delivered_at' => $deliveredAt,
            'read_at' => $readAt,
            'priority' => strtolower($row['priority'] ?? 'normal'),
            'category' => !empty($row['category']) && $row['category'] !== 'N/A' ? strtolower($row['category']) : null,
            'response' => !empty($row['response']) && $row['response'] !== 'N/A' ? $row['response'] : null,
            'response_at' => $responseAt,
            'communication_channel' => !empty($row['communication_channel']) && $row['communication_channel'] !== 'N/A' ? $row['communication_channel'] : null,
            'cost' => !empty($row['cost']) && $row['cost'] !== '0.00' ? (float) $row['cost'] : null,
            'notes' => !empty($row['notes']) && $row['notes'] !== 'N/A' ? $row['notes'] : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'parent_name' => 'required',
            'communication_type' => 'required|in:email,sms,phone,meeting,letter',
            'message' => 'required',
            'status' => 'nullable|in:sent,delivered,read,failed',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'category' => 'nullable|in:academic,behavior,attendance,fee,general',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'parent_name.required' => 'Parent name is required.',
            'communication_type.required' => 'Communication type is required.',
            'communication_type.in' => 'Communication type must be one of: email, sms, phone, meeting, letter.',
            'message.required' => 'Message is required.',
            'status.in' => 'Status must be one of: sent, delivered, read, failed.',
            'priority.in' => 'Priority must be one of: low, normal, high, urgent.',
            'category.in' => 'Category must be one of: academic, behavior, attendance, fee, general.',
        ];
    }

    protected function findParentDetail($parentName)
    {
        if (empty($parentName)) {
            return null;
        }

        // First try to find by primary_contact_name
        $parent = ParentDetail::where('primary_contact_name', $parentName)->first();
        if ($parent) {
            return $parent;
        }

        // Then try to find by user name
        $parent = ParentDetail::whereHas('user', function ($query) use ($parentName) {
            $query->where('name', $parentName);
        })->first();
        if ($parent) {
            return $parent;
        }

        return null;
    }

    protected function findStudent($studentName)
    {
        if (empty($studentName)) {
            return null;
        }

        // Try to find by full name
        $student = StudentDetail::whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$studentName])->first();
        if ($student) {
            return $student;
        }

        // Try to find by first name only
        $student = StudentDetail::where('first_name', $studentName)->first();
        if ($student) {
            return $student;
        }

        return null;
    }

    protected function parseDate($dateString)
    {
        if (empty($dateString) || $dateString === 'N/A') {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($dateString);
        } catch (\Exception $e) {
            Log::warning("Could not parse date: " . $dateString);
            return null;
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
