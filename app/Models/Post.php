<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Post extends Model
{
	protected $fillable = [
		'text', 'image_path', 'is_active', 'postable_type', 'postable_id', 'user_id',
	];

	protected $appends =  [
		'is_owner'
	];

	public function getImagePathAttribute($imagePath)
    {
		if ($imagePath) {
			return env('AWS_URL') . $imagePath;
		}
	}

	public function getIsOwnerAttribute()
	{
        if (is_null(Auth::user())) return false;
		return $this->user_id == Auth::user()->id;
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function postable()
	{
		return $this->morphTo();
	}

	public function comments()
	{
		return $this->morphMany('App\Models\Comment', 'commentable');
	}
}
