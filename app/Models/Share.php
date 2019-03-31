<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
	protected $fillable = [
		'shareable_type', 'shareable_id', 'user_id',
	];
}
