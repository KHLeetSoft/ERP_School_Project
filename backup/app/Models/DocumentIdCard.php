<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentIdCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'document_id_cards';

    protected $fillable = [
        'school_id',
        'student_id',
        'student_name',
        'class_name',
        'section_name',
        'roll_number',
        'date_of_birth',
        'blood_group',
        'address',
        'phone',
        'guardian_name',
        'issue_date',
        'expiry_date',
        'photo_path',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];
}


