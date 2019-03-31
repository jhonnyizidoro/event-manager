<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'name', 'state_id',
	];
}
