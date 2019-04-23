<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
		'name', 'description', 'is_active', 'user_id'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function members()
	{
		return $this->belongsToMany('App\Models\User', 'user_staff', 'staff_id', 'user_id');
	}
}
