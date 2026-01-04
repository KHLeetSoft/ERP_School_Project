<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentEmployeeConductCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_employee_conduct_certificates';

    protected $fillable = [
        'school_id',
        'employee_id',
        'employee_name',
        'designation',
        'department',
        'conduct',
        'ecc_number',
        'issue_date',
        'remarks',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];
}


