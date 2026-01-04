<?php

namespace App\Exports;

use App\Models\StudentDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentDetailExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return StudentDetail::with(['user','class','section','school'])
            ->get()
            ->map(function($student) {
                return [
                    'Name' => $student->user->first_name . ' ' . $student->user->last_name,
                    'Email' => $student->user->email,
                    'Admission No' => $student->admission_no,
                    'Roll No' => $student->roll_no,
                    'Class' => $student->class->name ?? '',
                    'Section' => $student->section->name ?? '',
                    'School' => $student->school->name ?? '',
                    'Status' => $student->user->is_active ? 'Active' : 'Inactive',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Name', 'Email', 'Admission No', 'Roll No', 'Class', 'Section', 'School', 'Status'
        ];
    }
}
