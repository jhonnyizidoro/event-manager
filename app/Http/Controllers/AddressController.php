<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\Address\UpdateAddress as UpdateAddressRequest;
use App\Http\Controllers\Webservices\GoogleController;
use Auth;

class AddressController extends Controller
{
    /**
	 * TODO: atualiza um endereço
	 * TODO: se existir id da cidade busca as geolocalizações
	 * @return Resource: endereço
     */
    public function updateUserAddress(UpdateAddressRequest $request)
    {
		$user = Auth::user();
		$geolocation = GoogleController::getGeolocation($request);
		if ($geolocation) {
			$request->request->add([
				'latitude' => $geolocation->latitude,
				'longitude' => $geolocation->longitude,
			]);
		}
		$user->address()->update($request->all());
		return json($user->address, 'Endereço alterado com sucesso.');
    }
}
