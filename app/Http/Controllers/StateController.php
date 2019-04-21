<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;
use App\Http\Requests\State\NewState as NewStateRequest;
use App\Http\Requests\State\UpdateState as UpdateStateRequest;

class StateController extends Controller
{
    /**
     * Mostra os estados cadastrados
     * @return Resource lista de todos os estados cadastrados
     */
    public function index()
    {
		$states = State::get();
		return json($states, 'Estados buscados.');
	}

    /**
     * Cria um novo estado
     * @return State estado que foi criado
     */
    public function store(NewStateRequest $request)
    {
		$state = State::create($request->all());
		return json($state, 'Estado criado.');
    }

    /**
     * Mostra um estado e suas cidades
     * @return Resource com um estados e suas respectivas cidades
     */
    public function show($id)
    {
		$state = State::findOrFail($id);
		$state->cities = $state->cities;
		return json($state, 'Estado encontrado.');
    }

    /**
     * Atualiza um estado
     * @return State estado atualizado
     */
    public function update(UpdateStateRequest $request)
    {
		$state = State::find($request->state_id);
		$state->update($request->all());
		return json($state, 'Estado atualizado.');
    }

    /**
     * Ativa ou desativa um estado
	 * @return State: estado ativado/desativado
     */
    public function destroy($id)
    {
		$state = State::findOrFail($id);
		$state->update([
			'is_active' => !$state->is_active
		]);
        return json($state, 'Estado ativado/desativado.');
    }

    public function cities($id)
    {
        $cities = State::findOrFail($id)->cities;
        return json($cities, 'Cidades buscadas.');
    }
}
