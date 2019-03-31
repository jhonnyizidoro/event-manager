<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'path', 'user_id',
	];
}
