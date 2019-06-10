<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserProfile extends Model
{
	protected $fillable = [
		'cover', 'picture', 'description', 'custom_url', 'user_id'
	];

	protected $appends = [
		'followers_count',
		'followings_count',
        'posts_count',
        'events_count'
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

	public function getFollowersCountAttribute()
	{
		return DB::table('follows')->where([ 'followable_type' => App\Models\User::class, 'followable_id' => $this->user->id ])->count();
	}

	public function getFollowingsCountAttribute()
	{
		return DB::table('follows')->where([ 'followable_type' => App\Models\User::class, 'user_id' => $this->user->id ])->count();
	}

	public function getPostsCountAttribute()
	{
		return DB::table('posts')->where([ 'user_id' => $this->user->id ])->count();
    }

    public function getEventsCountAttribute()
    {
		return DB::table('events')->where([ 'user_id' => $this->user->id ])->count();
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

    public function posts()
    {
        return $this->morphMany('App\Models\Post', 'postable');
    }
}
