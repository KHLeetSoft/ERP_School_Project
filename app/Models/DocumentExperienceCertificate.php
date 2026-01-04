<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentExperienceCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_experience_certificates';

    protected $fillable = [
        'school_id',
        'employee_id',
        'employee_name',
        'designation',
        'department',
        'joining_date',
        'relieving_date',
        'total_experience',
        'ec_number',
        'issue_date',
        'remarks',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'relieving_date' => 'date',
        'issue_date' => 'date',
    ];
}


