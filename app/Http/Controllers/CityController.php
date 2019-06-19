<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Http\Resources\City as CityResource;
use App\Http\Requests\City\NewCity as NewCityRequest;
use App\Http\Requests\City\UpdateCity as UpdateCityRequest;

class CityController extends Controller
{
    /**
     * @return Resource paginação de todas as cidades
     */
    public function index($search = '')
    {
		$cities = City::where('name', 'LIKE', "%{$search}%")->with('state')->paginate(10);
		// $cities = CityResource::collection($cities);
		return json($cities, 'Sucesso ao buscar as cidades');
    }

    /**
     * TODO: insere uma cidade
     * @return City cidade inserida
     */
    public function store(NewCityRequest $request)
    {
		$city = City::create($request->all());
		$city = new CityResource($city);
		return json($city, 'Cidade inserida com sucesso.');
    }

    /**
     * Busca uma cidade
     * @return CityResource cidade encontrada
     */
    public function show($id)
    {
		$city = City::findOrFail($id);
		$city = new CityResource($city);
		return json($city, 'Cidade encontrada.');
    }

    /**
     * Atualiza uma cidade
     * @return CityResource cidade atualizada
     */
    public function update(UpdateCityRequest $request)
    {
		$city = City::find($request->city_id);
		$city->update($request->all());
		$city = new CityResource($city);
        return json($city, 'Cidade atualizada.');
    }

    /**
     * Ativa ou desativa uma cidade
	 * @return CityResource: cidade ativada/desativada
     */
    public function destroy($id)
    {
		$city = City::findOrFail($id);
		$city->update([
			'is_active' => !$city->is_active
		]);
		$city = new CityResource($city);
        return json($city, 'Cidade ativada/desativada.');
    }
}
