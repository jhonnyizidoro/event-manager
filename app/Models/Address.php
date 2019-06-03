<?php

namespace App\Models;

use App\Models\Google;
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
            $address->getGeolocation();
        });

        static::updating(function(Address $address) {
            $address->getGeolocation();
        });
    }

    public function getGeolocation()
    {
        $geolocation = Google::getGeolocation($this);
        if ($geolocation) {
            $this->latitude = $geolocation->latitude;
            $this->longitude = $geolocation->longitude;
        }
    }
}
