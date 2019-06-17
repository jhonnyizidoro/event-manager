<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Comment extends Model
{
	protected $fillable = [
		'active', 'comment_id', 'post_id', 'text', 'user_id',
	];

	protected $appends = [
		'likes_count', 'has_liked'
	];

	public function getLikesCountAttribute()
	{
		return DB::table('likes')->where([ 'likeable_type' => Comment::class, 'likeable_id' => $this->id ])->count();
	}

	public function getHasLikedAttribute()
	{
        if (is_null(Auth::user())) return false;
		return DB::table('likes')->where([ 'likeable_type' => Comment::class, 'likeable_id' => $this->id, 'user_id' => Auth::user()->id ])->exists();
	}

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

	public function likes()
    {
        return $this->morphMany('App\Models\Post', 'likeable');
    }
}
