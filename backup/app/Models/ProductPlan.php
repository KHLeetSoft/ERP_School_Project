<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPlan extends Model
{
    //
    protected $fillable = [
        'title',
        'price',
        'features',
        'max_users',
        'status',
    ];
}
