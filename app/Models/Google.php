<?php

namespace App\Models;

use App\Models\Address;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Database\Eloquent\Model;

class Google extends Model
{
    public static function getGeolocation(Address $address)
    {
        $response = Curl::to('https://maps.googleapis.com/maps/api/geocode/json')
		->asJson()
		->withTimeout(2)
		->withData([
			'address' => self::formatAddress($address),
			'key' => env('GOOGLE_GEOCODE_KEY')
		])
		->get();

        if (!count($response->results)) return false;

		return (object) [
			'latitude' => $response->results[0]->geometry->location->lat,
			'longitude' => $response->results[0]->geometry->location->lng
		];
    }

	private static function formatAddress(Address $address)
	{
		$formattedAddress = false;

		if ($address->zip_code) {
			$formattedAddress = "{$address->street} {$address->number} {$address->neighborhood} {$address->zip_code}";
		}

		if ($address->city_id) {
			$formattedAddress .= " {$address->city->name} {$address->city->state->code} Brasil";
		}

		return $formattedAddress;
	}
}
