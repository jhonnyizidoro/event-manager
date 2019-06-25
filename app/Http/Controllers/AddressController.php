<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Event;
use App\Http\Requests\Address\UpdateUserAddress as UpdateUserAddressRequest;
use App\Http\Requests\Address\UpdateEventAddress as UpdateEventAddressRequest;
use App\Http\Controllers\Webservices\GoogleController;
use Illuminate\Http\Request;
use Auth;

class AddressController extends Controller
{
    public function updateUserAddress(UpdateUserAddressRequest $request)
    {
		$user = Auth::user();
		return $this->update($user, $request);
	}

    public function updateEventAddress(UpdateEventAddressRequest $request)
    {
		$event = Event::find($request->event_id);
		return $this->update($event, $request);
	}

	/**
	 * TODO: atualiza um endereço de um evento ou de um usuário
	 * TODO: se existir id da cidade busca as geolocalizações
	 * @return Resource: endereço
     */
	public function update($id, Request $request)
	{
		$model = Address::findOrFail($id);
		$model->update($request->all());
		return json($model->address, 'Endereço atualizado com sucesso.');
	}
}
