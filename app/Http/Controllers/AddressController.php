<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\Address\UpdateAddress as UpdateAddressRequest;
use App\Http\Controllers\Webservices\GoogleController;
use Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
	 * TODO: Cria/atualiza uma preferência do usuário
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
