<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
	protected $fillable = [
		'followable_id', 'followable_type', 'user_id',
	];
}
