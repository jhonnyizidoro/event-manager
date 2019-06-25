<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\UserStaff;
use App\Http\Requests\Staff\NewStaff as NewStaffRequest;
use Illuminate\Http\Request;
use Auth;

class StaffController extends Controller
{
    /**
     * TODO: lista as equipes de administradores do usuário logado
     * @return Resource equipes encontradas
     */
    public function index()
    {
		$staff = Auth::user()->staffs()->where('is_active', true)->get();
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

    public function members($id)
    {
        $staff = Staff::findOrFail($id);
        $members = $staff->members()->wherePivot('is_active', true)->get();

        return json($members, 'Membros listados.');
    }

    public function addMember($id)
    {
        try {
            $user = User::where('email', request('email'))->first();
            $staff = Staff::findOrFail($id);

            if ($staff->owner->is($user) || $staff->members()->wherePivot('is_active', true)->get()->contains($user)) {
                return response()->json([], 'Membro não pode ser adicionado à equipe.')->setStatusCode(500);
            }

            if ($staff->members->contains($user)) {
                $model = $staff->members()->where('user_id', $user->id)->wherePivot('is_active', false)->first();
				$model->pivot->update([
					'is_active' => true
				]);
            } else {
                $staff->members()->save($user);
            }

            return json($user, 'Membro adicionado.');
        } catch (\Exception $e) {
            return json([], $e->getMessage());
        }
    }

    public function removeMember($id, $user_id)
    {
        $staff = Staff::findOrFail($id);
        $staff->members()->find($user_id)->pivot->update([ 'is_active' => false ]);

        return json([], 'Membro removido');
    }
}
