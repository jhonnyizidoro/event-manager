<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use Auth;

class Event extends Model
{
	protected $fillable = [
		'address_id', 'category_id', 'cover', 'description', 'ends_at', 'event_serie_id', 'is_active', 'is_certified', 'min_age', 'name', 'starts_at', 'user_id',
	];

	protected $appends = [
		'is_following'
	];

	public function getIsFollowingAttribute()
	{
		if (is_null(Auth::user())) return false;
		return Auth::user()->events_followed->contains($this);
	}

	public function getCoverAttribute($image)
    {
		if ($image) {
			return env('AWS_URL') . $image;
		}
	}

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

	public function certificate()
	{
		return $this->hasOne('App\Models\Certificate');
	}

	public function address()
	{
		return $this->belongsTo('App\Models\Address');
    }

    public function staffs()
    {
        return $this->belongsToMany('App\Models\Staff', 'event_staff', 'event_id', 'staff_id');
    }

    public function administrators()
    {
        return $this->belongsToMany('App\Models\User', 'event_administrators', 'event_id', 'user_id');
    }

	public function followers()
	{
		return $this->morphToMany('App\Models\User', 'followable', 'follows', 'followable_id');
	}

	public function category()
	{
		return $this->belongsTo('App\Models\Category');
	}

	public function serie()
	{
		return $this->belongsTo('App\Models\EventSerie', 'event_serie_id');
	}

	public static function boot()
	{
		parent::boot();

		static::created(function(Event $model) {
			$model->notificateInterestedUsers();
		});

		static::updated(function(Event $model) {
			$old = $model->getOriginal('is_active');
			if ($old != $model->is_active) {
				$model->notificateFollowers('O evento ' . $model->name . ' acaba de ser cancelado.');
			}
		});
	}

	public function notificateInterestedUsers()
	{
		$notification = new Notification();
		$notification->text = 'Novo evento dia ' . $this->starts_at . ' na categoria ' . $this->category->name . '.';
		$notification->save();

		$users = $this->category->users_interested;

		foreach ($users as $user) {
			$user->notifications()->save($notification);
			if (!is_null($user->fcm_web_token)) {
				$notification->send($user);
			}
		}
	}

	public function notificateFollowers($text)
	{
		$notification = new Notification();
		$notification->text = $text;
		$notification->save();

		$users = $this->followers;

		foreach ($users as $user) {
			$user->notifications()->save($notification);
			if (!is_null($user->fcm_web_token)) {
				$notification->send($user);
			}
		}
	}
}
