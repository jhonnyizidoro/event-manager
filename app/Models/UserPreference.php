<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{	
	public $timestamps = false;
	
	protected $fillable = [
		'receive_events_email', 'receive_events_notification', 'events_notification_range', 'user_id',
	];
}
