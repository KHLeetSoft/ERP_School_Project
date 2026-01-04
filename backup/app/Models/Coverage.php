<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User; 
use App\Models\School;

class Coverage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_date',
        'date',
        'completed_date',
        'school_id',
        'class_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'status',
        'priority',
        'remarks',
        'attachments',
        'is_active',
    ];

    // School relationship
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Class relationship
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Section relationship
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Subject relationship
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Teacher relationship
    public function teacher()
{
    return $this->belongsTo(User::class, 'teacher_id')->where('role_id', 3);
}
}
