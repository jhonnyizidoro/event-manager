<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
	protected $fillable = [
		'is_active', 'name', 'user_id',
	];
}
