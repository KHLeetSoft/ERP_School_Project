<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTransferCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_transfer_certificates';

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
        'admission_date',
        'leaving_date',
        'reason_for_leaving',
        'conduct',
        'tc_number',
        'issue_date',
        'remarks',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'leaving_date' => 'date',
        'issue_date' => 'date',
    ];
}



