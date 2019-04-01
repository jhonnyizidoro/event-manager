<?php

namespace App\Http\Controllers;

use App\Models\User;
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
		$users = User::get();
        return json($users, 'Usuários listados com sucesso.');
    }

    /**
     * TODO: Cria um usuário. Realizado tratamento para não criar usuários administradores
	 * @return Resource: usuário criado
     */
    public function store(NewUserRequest $request)
    {
		$request->request->remove('is_admin');
		if ($user = User::create($request->all())) {
			return json($user, 'Usuário Cadastrado com sucesso.');
		}
		return json([], 'Erro ao cadastrar usuário.', false, 500);
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
     * TODO: Verifica se o usuário é administrador ou o dono da conta antes de atualizar
	 * TODO: Realizado tratamento para que apenas administradores possam adicionar outros administradores
	 * @return Resource: usuário atualizado
     */
    public function update(UpdateUserRequest $request)
    {
		$user = User::find($request->id);
		$loggedUser = Auth::user();
		if ($loggedUser->is_admin || $loggedUser == $user) {
			if (!$loggedUser->is_admin) {
				$request->request->remove('is_admin');
			}
			$user->update($request->all());
			return json($user, 'Usuário atulizado com sucesso.');
		}
		return json([], 'Você não tem permissão para atualizar esse usuário.');
    }

    /**
     * Ativa ou desativa um usuário
	 * @return Resource: usuário ativado/desativado
     */
    public function destroy($id)
    {
		$user = User::findOrFail($id);
		$loggedUser = Auth::user();
		if ($loggedUser->is_admin || $loggedUser == $user) {
			$user->update([
				'is_active' => !$user->is_active
			]);
			return json($user, 'Usuário ativado/desativado com sucesso.');
		}
		return json([], 'Você não tem permissão para ativar/desativar esse usuário.');
    }
}
