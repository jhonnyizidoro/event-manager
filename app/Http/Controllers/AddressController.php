<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Http\Requests\Address\UpdateAddress as UpdateAddressRequest;

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
    public function update(UpdateAddressRequest $request)
    {
        dd($request);
    }
}
