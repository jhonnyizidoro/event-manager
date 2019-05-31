<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = [
		'is_hidden', 'link', 'text'
	];

	public function users()
	{
		return $this->belongsToMany('App\Models\User', 'user_notifications', 'notification_id', 'user_id');
	}

	// public function send()
	// {

	// }
}
