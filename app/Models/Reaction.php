<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'name', 'icon',
	];
}
