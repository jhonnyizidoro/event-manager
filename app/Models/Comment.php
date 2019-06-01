<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = [
		'active', 'comment_id', 'post_id', 'text', 'user_id',
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function replies()
	{
		return $this->morphMany('App\Models\Comment', 'commentable');
	}

	public function commentable()
	{
		return $this->morphTo();
	}
}
