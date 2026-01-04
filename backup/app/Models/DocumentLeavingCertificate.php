<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentLeavingCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_leaving_certificates';

    protected $fillable = [
        'school_id',
        'student_id',
        'student_name',
        'admission_no',
        'class_name',
        'section_name',
        'date_of_birth',
        'father_name',
        'mother_name',
        'reason_for_leaving',
        'conduct',
        'lc_number',
        'issue_date',
        'remarks',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date' => 'date',
    ];
}


