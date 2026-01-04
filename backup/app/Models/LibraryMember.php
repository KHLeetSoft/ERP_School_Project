<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryMember extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'school_id',
		'membership_no',
		'name',
		'email',
		'phone',
		'address',
		'member_type',
		'joined_at',
		'expiry_at',
		'status',
		'notes',
	];

	protected $casts = [
		'joined_at' => 'datetime',
		'expiry_at' => 'datetime',
	];
}


