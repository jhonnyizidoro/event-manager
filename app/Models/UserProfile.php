<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	protected $fillable = [
		'cover', 'picture', 'description', 'custom_url', 'user_id',
	];
}
