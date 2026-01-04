<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkAttendanceBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'batch_date',
        'file_name',
        'total',
        'present',
        'absent',
        'late',
        'half_day',
        'leave',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'batch_date' => 'datetime',
    ];
}


