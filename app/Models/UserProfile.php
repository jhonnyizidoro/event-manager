<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	protected $fillable = [
		'cover', 'picture', 'description', 'custom_url', 'user_id'
	];

	protected $appends = [
		'followers_count',
		'followings_count',
		'posts_count'
	];

	public function getPictureAttribute($picture)
    {
		if ($picture) {
			return env('AWS_URL') . $picture;
		}
	}

	public function getCoverAttribute($cover)
    {
		if ($cover) {
			return env('AWS_URL') . $cover;
		}
	}

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

	public function getPostsCountAttribute()
	{
		return $this->user->posts->count();
    }

    public function posts()
    {
        return $this->morphMany('App\Models\Post', 'postable');
    }
}
