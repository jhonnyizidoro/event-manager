<?php

namespace App\Http\Controllers\Webservices;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Google;

class GoogleController extends Controller
{

	/**
	 * @param App\Models\Address
	 * TODO: busca a latitude e longitude baseado nos dados do endereço
	 * @return Object com propriedade latitude e longitude ou False caso o endereço não tenha dados suficientes
	 */
	public static function getGeolocation($request)
	{
		$address = new Address($request->all());

		if (!$address) {
			return false;
		}

		return Google::getGeolocation($address);
	}
}
