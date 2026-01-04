<?php

namespace App\Imports;

use App\Models\ParentPortalAccess;
use App\Models\ParentDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ParentPortalAccessImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, WithProgressBar
{
    protected $errors = [];
    protected $imported = 0;
    protected $skipped = 0;

    public function model(array $row)
    {
        try {
            // Find parent by name or create if doesn't exist
            $parentName = trim($row['parent_name'] ?? '');
            if (empty($parentName)) {
                $this->errors[] = "Row " . ($this->imported + $this->skipped + 1) . ": Parent name is required";
                $this->skipped++;
                return null;
            }

            // Try to find parent by name
            $parent = ParentDetail::where('primary_contact_name', 'LIKE', '%' . $parentName . '%')
                ->orWhere('secondary_contact_name', 'LIKE', '%' . $parentName . '%')
                ->first();

            if (!$parent) {
                $this->errors[] = "Row " . ($this->imported + $this->skipped + 1) . ": Parent '$parentName' not found";
                $this->skipped++;
                return null;
            }

            // Check if username already exists
            $username = trim($row['username'] ?? '');
            if (empty($username)) {
                $this->errors[] = "Row " . ($this->imported + $this->skipped + 1) . ": Username is required";
                $this->skipped++;
                return null;
            }

            if (ParentPortalAccess::where('username', $username)->exists()) {
                $this->errors[] = "Row " . ($this->imported + $this->skipped + 1) . ": Username '$username' already exists";
                $this->skipped++;
                return null;
            }

            // Check if email already exists (if provided)
            $email = trim($row['email'] ?? '');
            if (!empty($email) && ParentPortalAccess::where('email', $email)->exists()) {
                $this->errors[] = "Row " . ($this->imported + $this->skipped + 1) . ": Email '$email' already exists";
                $this->skipped++;
                return null;
            }

            $this->imported++;

            return new ParentPortalAccess([
                'school_id' => auth()->user()->school_id ?? null,
                'parent_detail_id' => $parent->id,
                'username' => $username,
                'email' => $email ?: null,
                'password_hash' => Hash::make($row['password'] ?? 'password123'),
                'is_enabled' => $this->parseBoolean($row['status'] ?? 'enabled'),
                'access_level' => $this->parseAccessLevel($row['access_level'] ?? 'basic'),
                'force_password_reset' => $this->parseBoolean($row['force_password_reset'] ?? 'no'),
                'permissions' => $this->parsePermissions($row['access_level'] ?? 'basic'),
                'notes' => $row['notes'] ?? null,
            ]);

        } catch (\Exception $e) {
            $this->errors[] = "Row " . ($this->imported + $this->skipped + 1) . ": " . $e->getMessage();
            $this->skipped++;
            Log::error('Parent Portal Access Import Error: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'parent_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'status' => 'nullable|string|in:enabled,disabled',
            'access_level' => 'nullable|string|in:basic,standard,premium',
            'force_password_reset' => 'nullable|string|in:yes,no',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getStats()
    {
        return [
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'total' => $this->imported + $this->skipped,
        ];
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        
        $value = strtolower(trim($value));
        return in_array($value, ['yes', 'true', '1', 'enabled', 'active']);
    }

    private function parseAccessLevel($value)
    {
        $value = strtolower(trim($value));
        $validLevels = ['basic', 'standard', 'premium'];
        
        return in_array($value, $validLevels) ? $value : 'basic';
    }

    private function parsePermissions($accessLevel)
    {
        $permissions = [
            'basic' => ['view_student_info', 'view_attendance', 'view_results'],
            'standard' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule'],
            'premium' => ['view_student_info', 'view_attendance', 'view_results', 'view_fees', 'view_schedule', 'view_assignments', 'view_communications', 'download_reports']
        ];

        return $permissions[$accessLevel] ?? $permissions['basic'];
    }
}
