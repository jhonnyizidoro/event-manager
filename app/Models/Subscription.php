<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
	protected $fillable = [
		'check_in', 'check_out', 'user_id', 'event_id',
	];
}
