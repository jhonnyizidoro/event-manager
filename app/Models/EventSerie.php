<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSerie extends Model
{
	public $timestamps = false;
	
	protected $fillable = [
		'cover', 'description', 'name', 'user_id', 'is_active'
	];

	public function getCoverAttribute($cover)
    {
		if ($cover) {
			return env('AWS_URL') . $cover;
		}
	}
}
