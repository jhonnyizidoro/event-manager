<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserProfile;
use App\Models\Address;
use App\Http\Requests\User\NewUser as NewUserRequest;
use App\Http\Requests\User\UpdateUser as UpdateUserRequest;
use Auth;

class UserController extends Controller
{
    /**
     * TODO: Lista todos os usuários
     * @return Resource: todos os usuários
     */
    public function index()
    {
		$users = User::paginate(10);
        return json($users, 'Usuários listados com sucesso.');
    }

    /**
     * TODO: Cria um usuário. Realizado tratamento para não criar usuários administradores
	 * @return Resource: usuário criado
     */
    public function store(NewUserRequest $request)
    {
		$request->request->remove('is_admin');

		//Endereço
		$address = Address::create();

		//Cria usuário e vincula endereço à ele
		$request->merge(['address_id' => $address->id]);
		$user = User::create($request->all());

		//Cria perfil e preferências
		UserProfile::create(['user_id' => $user->id]);
		UserPreference::create(['user_id' => $user->id]);

		return json($user, 'Usuário Cadastrado com sucesso.');
    }

    /**
     * TODO: Busca o usuário logado
	 * @return Resource: usuário logado
     */
    public function me()
    {
		$user = Auth::user();
		return json($user, 'Busca realizada com sucesso.');
    }

    /**
	 * TODO: Realizado tratamento para que apenas administradores possam adicionar outros administradores
	 * TODO: Também cria/atualiza uma preferência do usuário
	 * @return Resource: usuário atualizado
     */
    public function update(UpdateUserRequest $request)
    {
		if (!Auth::user()->is_admin) {
			$request->request->remove('is_admin');
		}
		$user = User::find($request->user_id);
		$user->update($request->all());
		$user->preference->update($request->all());
		return json($user, 'Usuário atualizado com sucesso.');
    }

    /**
     * Ativa ou desativa um usuário
	 * @return Resource: usuário ativado/desativado
     */
    public function destroy($id)
    {
		$user = User::findOrFail($id);
		$user->update([
			'is_active' => !$user->is_active
		]);
		return json($user, 'Usuário ativado/desativado com sucesso.');
    }
}
