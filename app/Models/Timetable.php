<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SchoolClass; // Add this line

class Timetable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_id',
        'section_id',
        'subject_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
        'room_number',
        'status'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    // Other relationships...


    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

  public function teacher()
{
    return $this->belongsTo(User::class, 'teacher_id')->where('role_id', 3);
}



    public function getTimeSlotAttribute()
    {
        return date('h:i A', strtotime($this->start_time)) . ' - ' . date('h:i A', strtotime($this->end_time));
    }

    public function getStatusBadgeAttribute()
    {
        return '<span class="badge bg-' . ($this->status == 'active' ? 'success' : 'danger') . '">' .
            ucfirst($this->status) . '</span>';
    }
}