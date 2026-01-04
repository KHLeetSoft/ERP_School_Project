<?php

namespace App\Exports;

use App\Models\AdmissionEnquiry;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdmissionEnquiryExport implements FromCollection, WithHeadings
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $query = AdmissionEnquiry::query();
        
        // Apply admin filter
        if (auth()->guard('admin')->check()) {
            $query->where('admin_id', auth()->guard('admin')->id());
        }
        
        // Apply filters if provided
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        
        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $query->whereBetween('date', [$this->filters['date_from'], $this->filters['date_to']]);
        }
        
        if (!empty($this->filters['counselor_id'])) {
            $query->where('counselor_id', $this->filters['counselor_id']);
        }
        
        return $query->select(
            'student_name',
            'parent_name',
            'contact_number',
            'email',
            'address',
            'class',
            'date',
            'status',
            'note',
            'first_contacted_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Parent Name',
            'Contact Number',
            'Email',
            'Address',
            'Class',
            'Date',
            'Status',
            'Note',
            'First Contacted At'
        ];
    }
}