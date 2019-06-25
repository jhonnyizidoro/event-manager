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

	public static function boot()
	{
		parent::boot();

		static::created(function(Comment $model) {
			$model->notificateOwner();
		});
	}

	public function notificateOwner()
	{
		$notification = new Notification();

		if ($this->commentable_type == \App\Models\Post::class) {
			$notification->text = $this->user->name . ' postou um comentário em um post seu.';
		} else {
			$notification->text = $this->user->name . ' postou uma resposta a um comentário seu.';
		}

		$notification->save();

		$this->commentable->user->notifications()->save($notification);
		$notification->send($this->commentable->user);
	}
}
