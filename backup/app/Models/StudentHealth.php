<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentHealth extends Model
{
    protected $table = 'students_health';

    protected $fillable = [
        'school_id',
        'student_id',
        'height_cm',
        'weight_kg',
        'blood_group',
        'allergies',
        'medical_conditions',
        'immunizations',
        'last_checkup_date',
        'notes'
    ];
    protected $casts = [
        'last_checkup_date' => 'date', // या 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }
    
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}

