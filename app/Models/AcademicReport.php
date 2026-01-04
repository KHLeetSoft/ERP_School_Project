<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'academic_reports';

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'report_date',
        'type',
        'status',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];
}


