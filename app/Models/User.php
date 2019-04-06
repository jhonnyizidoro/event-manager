<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;
use Hash;

class User extends Authenticatable implements JWTSubject
{
	protected $fillable = [
		'name', 'email', 'password', 'nickname', 'birthdate', 'is_active', 'is_admin', 'address_id',
	];

	protected $hidden = [
		'password'
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
}
