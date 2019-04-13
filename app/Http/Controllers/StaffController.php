<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\Staff\NewStaff as NewStaffRequest;
use Auth;

class StaffController extends Controller
{
    /**
     * TODO: lista as equipes de administradores do usuário logado
     * @return Resource equipes encontradas
     */
    public function index()
    {
		$staff = Auth::user()->staff;
		return json($staff, 'Busca realizada.');
    }


    /**
     * TODO: cria uma nova equipe de administradores
     * @return Resource equipe criada
     */
    public function store(NewStaffRequest $request)
    {
		$request->merge(['user_id' => Auth::user()->id]);
		$staff = Staff::create($request->all());
		return json($staff, 'Equipe de administradores criada.');
    }

    /**
     * Ativa ou desativa uma equipe de administrador
	 * @return Resource: equipe ativada/desativada
     */
    public function destroy($id)
    {
		$staff = Staff::findOrFail($id);
		$staff->update([
			'is_active' => !$staff->is_active
		]);
		return json($staff, 'Equipe ativada/desativada com sucesso.');
    }
}
