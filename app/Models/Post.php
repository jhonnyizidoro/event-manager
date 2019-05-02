<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $fillable = [
		'text', 'image_path', 'is_active', 'postable_type', 'postable_id', 'user_id',
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
