<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Post extends Model
{
	protected $fillable = [
		'text', 'image_path', 'is_active', 'postable_type', 'postable_id', 'user_id',
	];

	protected $appends =  [
		'is_owner'
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function getIsOwnerAttribute()
	{
		return $this->user_id == Auth::user()->id;
	}

	public function postable()
	{
		return $this->morphTo();
	}
}
