<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'section_id',
        'date',
        'status', // Present, Absent, Leave etc.
    ];

    protected $dates = ['date'];

    // Student Relation
    public function student()
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    // Class Relation
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Section Relation
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }
}
