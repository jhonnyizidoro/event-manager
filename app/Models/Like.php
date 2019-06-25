<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'likeable_type', 'likeable_id', 'user_id'
    ];

    public function likeable()
	{
		return $this->morphTo();
    }

    public function user()
	{
		return $this->belongsTo('App\Models\User');
    }
}
