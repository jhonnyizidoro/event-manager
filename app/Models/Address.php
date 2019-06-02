<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
		'city_id', 'complement', 'latitude', 'longitude', 'neighborhood', 'number', 'street', 'zip_code', 'name'
	];

	public function city()
	{
		return $this->belongsTo('App\Models\City');
    }

    public static function boot() {
        parent::boot();

        static::creating(function(Address $address) {
            // Get latitude e longitude
        });
    }
}
