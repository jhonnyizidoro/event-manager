<?php

namespace App\Http\Controllers\Webservices;

use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;

class GoogleController extends Controller
{

	/**
	 * @param App\Models\Address
	 * TODO: busca a latitude e longitude baseado nos dados do endereço
	 * @return Object com propriedade latitude e longitude ou False caso o endereço não tenha dados suficientes
	 */
	public static function getGeolocation($address)
	{
		$addressParameter = self::formatAddress($address);

		if (!$addressParameter) {
			return false;
		}

		$response = Curl::to('https://maps.googleapis.com/maps/api/geocode/json')
		->asJson()
		->withTimeout(2)
		->withData([
			'address' => $addressParameter,
			'key' => env('GOOGLE_GEOCODE_KEY')
		])
		->get();

		return (object) [
			'latitude' => $response->results[0]->geometry->location->lat,
			'longitude' => $response->results[0]->geometry->location->lng
		];
	}

	/**
	 * @param Address recebe um objeto Address
	 * @return String com endereço formatado: Adão Sobocinski 25 Cristo Rei 80050-480 Curitiba PR Brasil
	 */
	private static function formatAddress($address)
	{
		$addressParameter = false;

		if ($address->zip_code) {
			$addressParameter = "{$address->street} {$address->number} {$address->neighborhood} {$address->zip_code}";
		}

		if ($address->city_id) {
			$addressParameter .= " {$address->city->name} {$address->city->state->code} Brasil";
		}

		return $addressParameter;
	}
}
