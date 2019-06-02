<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	protected $fillable = [
		'address_id', 'category_id', 'cover', 'description', 'ends_at', 'event_serie_id', 'is_active', 'is_certified', 'min_age', 'name', 'starts_at', 'user_id',
    ];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

	public function certificate()
	{
		return $this->hasOne('App\Models\Certificate');
	}

	public function getCoverAttribute($image)
    {
		if ($image) {
			return env('AWS_URL') . $image;
		}
	}

	public function address()
	{
		return $this->belongsTo('App\Models\Address');
    }

    public function staffs()
    {
        return $this->belongsToMany('App\Models\Staff', 'event_staff', 'event_id', 'staff_id');
    }

    public function administrators()
    {
        return $this->belongsToMany('App\Models\User', 'event_administrators', 'event_id', 'user_id');
    }

	public function followers()
	{
		return $this->morphToMany('App\Models\User', 'followable', 'follows', 'followable_id');
	}
}
