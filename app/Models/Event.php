<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $fillable = [
		'address_id', 'category_id', 'cover', 'description', 'ends_at', 'event_serie_id', 'is_active', 'is_certified', 'min_age', 'name', 'starts_at', 'user_id',
	];
}
