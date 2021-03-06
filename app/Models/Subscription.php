<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Subscription extends Model
{
	use HasUuid;

	protected $fillable = [
		'check_in', 'check_out', 'user_id', 'event_id', 'user_responsible_id'
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}

	public function responsible()
	{
		return $this->belongsTo('App\Models\User', 'user_responsible_id');
	}

	public function event()
	{
		return $this->belongsTo('App\Models\Event');
	}
}
