<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
	protected $fillable = [
		'name', 'email', 'password', 'nickname', 'birthdate', 'is_active', 'is_admin', 'address_id',
	];

	public function getJWTIdentifier()
    {
        return $this->getKey();
	}
	
	public function getJWTCustomClaims()
    {
        return [];
    }
}
