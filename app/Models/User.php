<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $fillable = [
		'name', 'email', 'password', 'nickname', 'birthdate', 'is_active', 'is_admin', 'address_id',
	];
}
