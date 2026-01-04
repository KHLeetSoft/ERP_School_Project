<?php

namespace App\Exports;

use App\Models\VisitorsPurpose;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitorsPurposeExport implements FromCollection, WithHeadings
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function collection(): Collection
    {
        return VisitorsPurpose::where('user_id', $this->userId)
            ->select('name', 'description', 'status', 'created_at')
            ->get()
            ->map(function ($purpose) {
                return [
                    'name' => $purpose->name,
                    'description' => $purpose->description,
                    'status' => $purpose->status ? 'Active' : 'Inactive',
                    'created_at' => $purpose->created_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Status',
            'Created At',
        ];
    }
}