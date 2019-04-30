<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	protected $fillable = [
		'cover', 'picture', 'description', 'custom_url', 'user_id',
	];

	protected $appends = [
		'followers_count',
		'followings_count'
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function getFollowersCountAttribute()
	{
		return $this->user->followers->count();
	}

	public function getFollowingsCountAttribute()
	{
		return $this->user->followings->count();
	}
}
