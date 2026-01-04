<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentsHealth extends Model
{
    protected $table = 'students_health';
    protected $fillable = [
        'school_id',
        'student_id',
        'height_cm',
        'weight_kg',
        'blood_group',
        'vision_left',
        'vision_right',
        'dental',
        'remarks',
    ];
}
