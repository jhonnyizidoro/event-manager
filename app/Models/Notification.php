<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = [
		'is_ridden', 'link', 'text', 'user_id',
	];
}
