<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //
    protected $fillable = ['user_id', 'school_id', 'class_id', 'name'];

public function classSections()
{
    return $this->hasMany(ClassSection::class, 'section_id');
}

}
