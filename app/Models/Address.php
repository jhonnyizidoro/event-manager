<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
		'city_id', 'complement', 'latitude', 'longitude', 'neighborhood', 'number', 'street', 'zip_code',
	];
}