<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hostel extends Model
{
    use HasFactory;

    protected $table = 'hostels';

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'address',
        'warden_name',
        'warden_phone',
        'description',
        'status',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(HostelRoom::class);
    }
}


