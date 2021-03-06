<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Auth;
use Carbon\Carbon;

class Event extends Model
{
	protected $fillable = [
		'address_id', 'category_id', 'cover', 'description', 'ends_at', 'event_serie_id', 'is_active', 'is_certified', 'min_age', 'name', 'starts_at', 'user_id',
	];

	protected $appends = [
		'is_following', 'is_managing', 'is_subscribed', 'followers_count', 'subscribers_count', 'duration', 'checkinable', 'checkoutable'
	];

	public function getIsFollowingAttribute()
	{
		if (is_null(Auth::user())) return false;
		return DB::table('follows')->where([ 'followable_type' => Event::class, 'followable_id' => $this->id, 'user_id' => Auth::user()->id ])->exists();
	}

	public function getIsManagingAttribute()
	{
		if (is_null(Auth::user())) return false;
		$byAdmin = DB::table('event_administrators')->where([ 'event_id' => $this->id, 'user_id' => Auth::user()->id, 'is_active' => true ])->exists();
		$staffIds = DB::table('user_staff')->where([ 'user_id' => Auth::user()->id ])->pluck('staff_id')->toArray();
		$byStaff = DB::table('event_staff')->where([ 'event_id' => $this->id ])->whereIn('staff_id', $staffIds)->exists();
		return $byAdmin || $byStaff;
	}

	public function getFollowersCountAttribute()
	{
		return DB::table('follows')->where([ 'followable_type' => Event::class, 'followable_id' => $this->id ])->count();
	}

	public function getSubscribersCountAttribute()
	{
		return DB::table('subscriptions')->where([ 'event_id' => $this->id ])->count();
	}

	public function getIsSubscribedAttribute()
	{
		if (is_null(Auth::user())) return false;
		return DB::table('subscriptions')->where([ 'user_id' => Auth::user()->id, 'event_id' => $this->id ])->exists();
	}

	public function getCoverAttribute($image)
    {
		if ($image) {
			return env('AWS_URL') . $image;
		}
	}

	public function getDurationAttribute()
	{
		return Carbon::parse($this->starts_at)->diffInMinutes(Carbon::parse($this->ends_at));
	}

	public function getCheckinableAttribute()
	{
		$diff = Carbon::parse($this->starts_at)->diffInMinutes(Carbon::now());
		return $diff >= -15 && $diff <= 15;
	}

	public function getCheckoutableAttribute()
	{
		$diff = Carbon::parse($this->ends_at)->diffInMinutes(Carbon::now());
		return  $diff >= -15 && $diff <= 15;
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
        return $this->belongsToMany('App\Models\Staff', 'event_staff', 'event_id', 'staff_id')->withPivot('is_active');
    }

    public function administrators()
    {
        return $this->belongsToMany('App\Models\User', 'event_administrators', 'event_id', 'user_id')->withPivot('is_active');
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
		$notification->text = 'Novo evento que pode te interessar: ' . $this->name .  ', dia ' . Carbon::parse($this->starts_at)->isoFormat('D [de] MMMM [de] Y');
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

	public function posts()
    {
        return $this->morphMany('App\Models\Post', 'postable');
	}

	public function getNiceDurationString()
	{
		$time = $this->duration / 60;

		if (intval($time) > 0) {
            $durationString = intval($time) . (intval($time) == 1 ? ' hora' : ' horas') . ($time - intval($time) > 0 ? ' e ' . 60 * ($time - intval($time)) . ' minutos' : '');
        } else {
            $durationString = 60 * ($time - intval($time)) . ' minutos';
		}

		return $durationString;
	}
}
