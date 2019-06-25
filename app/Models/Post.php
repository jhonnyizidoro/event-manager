<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Post extends Model
{
	protected $fillable = [
		'text', 'image_path', 'is_active', 'postable_type', 'postable_id', 'user_id', 'shareable_type', 'shareable_id',
	];

	protected $appends =  [
		'is_owner', 'likes_count', 'has_liked'
	];

	public function getImagePathAttribute($imagePath)
    {
		if ($imagePath) {
			return env('AWS_URL') . $imagePath;
		}
	}

	public function getLikesCountAttribute()
	{
		return DB::table('likes')->where([ 'likeable_type' => Post::class, 'likeable_id' => $this->id ])->count();
	}

	public function getIsOwnerAttribute()
	{
        if (is_null(Auth::user())) return false;
		return $this->user_id == Auth::user()->id;
	}

	public function getHasLikedAttribute()
	{
        if (is_null(Auth::user())) return false;
		return DB::table('likes')->where([ 'likeable_type' => Post::class, 'likeable_id' => $this->id, 'user_id' => Auth::user()->id ])->exists();
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function postable()
	{
		return $this->morphTo();
	}

	public function shareable()
	{
		return $this->morphTo();
	}

	public function comments()
	{
		return $this->morphMany('App\Models\Comment', 'commentable');
	}

	public function likes()
    {
        return $this->morphMany('App\Models\Like', 'likeable');
	}

	public static function boot()
	{
		parent::boot();

		static::created(function(Post $model) {
			$model->notificateOwner();
		});
	}

	public function notificateOwner()
	{
		$notification = new Notification();

		if ($this->postable_type == \App\Models\Event::class) {
			$notification->text = $this->user->name . ' acabou de adicionar uma postagem no seu evento ' . $this->postable->name . '.';
			$user = $this->postable->owner;
		} else {
			$notification->text = $this->user->name . ' acabou de adicionar uma postagem no seu perfil!';
			$user = $this->postable->user;
		}

		$notification->save();

		$user->notifications()->save($notification);
		$notification->send($user);
	}
}
