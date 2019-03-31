<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSerie extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'cover', 'description', 'name',
	];
}
