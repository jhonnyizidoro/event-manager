<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = [
		'active', 'comment_id', 'post_id', 'text', 'user_id',
	];
}
