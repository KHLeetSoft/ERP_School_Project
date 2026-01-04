<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentMarksheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_marksheets';

    protected $fillable = [
        'school_id',
        'student_id',
        'student_name',
        'admission_no',
        'roll_no',
        'class_name',
        'section_name',
        'exam_name',
        'term',
        'academic_year',
        'ms_number',
        'issue_date',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'result_status',
        'remarks',
        'marks_json',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];
}


