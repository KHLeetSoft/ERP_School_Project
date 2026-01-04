<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StudentResult extends Model
{
    protected $fillable = [
        'school_id',
        'admin_id',
        'student_id',
        'class_id',
        'subject_id',
        'exam_type',
        'marks_obtained',
        'total_marks',
        'result_status',
        'grade'
    ];

    public function student()
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    public function schoolClass()
    {
        // school_class table से class_id match कराएगा
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }
    public function subject()
    {
        // subjects table से subject_id match कराएगा
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
}
