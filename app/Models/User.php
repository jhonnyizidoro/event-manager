<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;
use Hash;
use Auth;

class User extends Authenticatable implements JWTSubject
{
	protected $fillable = [
		'name', 'email', 'password', 'nickname', 'birthdate', 'is_active', 'is_admin', 'address_id',
	];

	protected $hidden = [
		'password'
	];

	protected $appends = [
		'is_following'
	];

	public function getJWTIdentifier()
    {
        return $this->getKey();
	}

	public function getJWTCustomClaims()
    {
        return [];
	}

	public function setPasswordAttribute($password)
    {
		$this->attributes['password'] = Hash::make($password);
	}

	public function getBirthdateAttribute($date)
    {
		if ($date) {
			return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
		}
	}
	public function getIsFollowingAttribute()
	{
		if (is_null(Auth::user())) return false;
		return Auth::user()->followings->contains($this);
	}

	public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y H:i');
	}

	public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y H:i');
	}

	public function address()
	{
		return $this->belongsTo('App\Models\Address');
	}

	public function preference()
	{
		return $this->hasOne('App\Models\UserPreference');
	}

	public function certificates()
	{
		return $this->belongsToMany('App\Models\Certificate', 'user_certificates');
	}

	public function events()
	{
		return $this->hasMany('App\Models\Event');
	}

	public function posts()
	{
		return $this->hasMany('App\Models\Post');
	}

	public function staffs()
	{
		return $this->hasMany('App\Models\Staff');
	}

	public function profile()
	{
		return $this->hasOne('App\Models\UserProfile');
	}

	public function series()
	{
		return $this->hasMany('App\Models\EventSerie');
	}

	public function member_staffs()
	{
		return $this->belongsToMany('App\Models\Staff', 'user_staff', 'user_id', 'staff_id')->withPivot('is_active');
	}

	public function followings()
	{
		return $this->morphedByMany('App\Models\User', 'followable', 'follows', 'user_id');
	}

	public function followers()
	{
		return $this->morphToMany('App\Models\User', 'followable', 'follows', 'followable_id');
	}

	public function interests()
	{
		return $this->belongsToMany('App\Models\Category', 'user_interests');
	}

	public function notifications()
	{
		return $this->belongsToMany('App\Models\Notification', 'user_notifications', 'user_id', 'notification_id')->withPivot(['is_read', 'is_hidden']);
	}

	public function events_administered()
	{
		return $this->belongsToMany('App\Models\Event', 'event_administrators', 'user_id', 'event_id');
	}

	public function events_followed()
	{
		return $this->morphedByMany('App\Models\Event', 'followable', 'follows', 'user_id');
	}

	public function posts_liked()
	{
		return $this->morphedByMany('App\Models\Post', 'likeable', 'likes', 'user_id');
	}

	public function comments_liked()
	{
		return $this->morphedByMany('App\Models\Comment', 'likeable', 'likes', 'user_id');
	}

	public function subscriptions()
	{
		return $this->hasMany('App\Models\Subscription');
	}
}
