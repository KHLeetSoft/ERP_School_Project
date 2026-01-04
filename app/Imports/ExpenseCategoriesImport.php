<?php

namespace App\Imports;

use App\Models\ExpenseCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoriesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $schoolId;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function model(array $row)
    {
        return new ExpenseCategory([
            'school_id' => $this->schoolId,
            'name' => $row['name'] ?? $row['Name'] ?? '',
            'code' => strtoupper($row['code'] ?? $row['Code'] ?? ''),
            'description' => $row['description'] ?? $row['Description'] ?? null,
            'color' => $row['color'] ?? $row['Color'] ?? '#3b82f6',
            'icon' => $row['icon'] ?? $row['Icon'] ?? 'bx bx-category',
            'budget_limit' => $row['budget_limit'] ?? $row['Budget Limit'] ?? null,
            'budget_period' => $row['budget_period'] ?? $row['Budget Period'] ?? 'monthly',
            'is_active' => $this->parseBoolean($row['status'] ?? $row['Status'] ?? 'Active'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
            'budget_limit' => 'nullable|numeric|min:0',
            'budget_period' => 'nullable|in:monthly,quarterly,yearly',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Category name is required.',
            'code.required' => 'Category code is required.',
            'budget_limit.numeric' => 'Budget limit must be a number.',
            'budget_period.in' => 'Budget period must be monthly, quarterly, or yearly.',
        ];
    }

    private function parseBoolean($value)
    {
        if (is_bool($value)) return $value;
        
        $value = strtolower(trim($value));
        return in_array($value, ['active', 'true', '1', 'yes', 'on']);
    }
}
