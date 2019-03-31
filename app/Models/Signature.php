<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'name', 'description', 'image', 'user_id',
	];
}
