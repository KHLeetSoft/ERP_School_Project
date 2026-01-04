<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Category;
use App\Models\Dormitory;
use App\Models\Room;
use App\Models\Route;
use App\Models\Vehicle;
use App\Models\Guardian;

class StudentDetail extends Model
{
        use HasFactory;
        protected $table = 'student_details';
    protected $fillable = [
        'user_id',
        'school_id',
        'class_id',
        'section_id',
        'roll_no',
        'admission_no',
        'dob',
        'gender',
        'blood_group',
        'religion',
        'nationality',
        'category',
        'guardian_name',
        'guardian_contact',
        'address',
        'profile_image',
    ];

   public function user()
   {
    return $this->belongsTo(User::class);
  }
    public function class() {
        return $this->belongsTo(SchoolClass::class); // use SchoolClass if your class model is named that way
    }

    public function section() {
        return $this->belongsTo(Section::class);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }
}