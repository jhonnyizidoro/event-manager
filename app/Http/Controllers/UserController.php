<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPreference;
use App\Models\UserProfile;
use App\Models\Address;
use App\Models\Follow;
use App\Models\Category;
use App\Http\Requests\User\NewUser as NewUserRequest;
use App\Http\Requests\User\UpdateUser as UpdateUserRequest;
use App\Http\Requests\UserProfile\UpdateUserProfile as UpdateUserProfileRequest;
use App\Helpers\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
		//Endereço
		$address = Address::create();

		//Cria usuário e vincula endereço à ele
		$request->merge(['address_id' => $address->id]);
		$user = User::create($request->except(['is_admin']));

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
		$user->address = Auth::user()->address;
		$user->profile = Auth::user()->profile;
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
			$request->offsetUnset('is_admin');
        }

        $user = User::find($request->user_id);
        $user->update($request->except(['birthdate']));

        if (!is_null($request->post('birthdate')))
            $user->birthdate = Carbon::createFromFormat('d/m/Y', $request->post('birthdate'))->format('Y-m-d');

        if (!is_null($user->preference))
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

    public function address()
    {
        $address = Auth::user()->address()->with('city.state')->first();
        return json($address, 'Endereço do usuário localizado.');
    }

    public function profile($user_id = null)
    {
        $user = is_null($user_id) ? (Auth::user()) : User::findOrFail($user_id);
        $user = User::with([
            'address.city.state',
            'profile'
        ])->findOrFail($user->id);

        if (! Auth::user()->is($user)) {
            $user->profile->follows = Auth::user()->followings->contains($user);
        }

        return json($user, 'Dados do perfil localizados.');
    }

    public function updateProfile(UpdateUserProfileRequest $request)
    {
		$profile = Auth::user()->profile;
		if ($request->picture) {
			$request->merge([
				'picture' => File::uploadBase64($request->picture, 'users/pictures'),
			]);
		}
		if ($request->cover) {
			$request->merge([
				'cover' => File::uploadBase64($request->cover, 'users/cover'),
			]);
		}
        $profile->update($request->all());
        return json($profile, 'Dados do perfil atualizado com sucesso.');
    }

    public function saveFcmWebToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = Auth::user();
        $user->fcm_web_token = $request->post('token');
        $user->save();

        return json(['msg' => 'FCM Web Token atualizado com sucesso.'], 200);
    }

    public function saveFcmMobileToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = Auth::user();
        $user->fcm_mobile_token = $request->post('token');
        $user->save();

        return json(['msg' => 'FCM Mobile Token atualizado com sucesso.'], 200);
    }

    public function searchByEmail($email)
    {
        $users = User::where('email', 'like', "%$email%")->pluck(['id', 'name', 'email'])->get();
        return json($users, 'Usuários buscados.');
    }

    public function getFollowers()
    {
        $users = Auth::user()->followers()->with('address.city.state', 'profile')->get();
        return json($users, 'Seguidores buscados');
    }

    public function getFollowings()
    {
        $users = Auth::user()->followings()->with('address.city.state', 'profile')->get();
        return json($users, 'Seguidores buscados.');
    }

    public function unfollow($user_id)
    {
        Auth::user()->followings()->where('followable_id', $user_id)->first()->pivot->delete();
        return json([], 'Deixou de seguir com sucesso.');
    }

    public function follow($user_id)
    {
        $user = User::findOrFail($user_id);

        if (!Auth::user()->followings->contains($user))
            Auth::user()->followings()->save($user);

        return json([], 'Começou a seguir com sucesso.');
    }

    public function findByEmail(Request $request)
    {
        $user = User::where('email', $request->get('email'))->firstOrFail();
        return response()->json($user, 200);
    }

    public function myInterests(Request $request)
    {
        $interests = Auth::user()->interests;
        return response()->json($interests, 200);
    }

    public function addInterest(Request $request)
    {
        $category = Category::findOrFail($request->post('category_id'));

        if (Auth::user()->interests->contains($category)) {
            return response()->json('Usuário já possui esta categoria como interesse.', 500);
        }

        Auth::user()->interests()->save($category);
        return response()->json($category, 200);
    }

    public function deleteInterest($category_id)
    {
        $pivot = Auth::user()->interests()->where('category_id', $category_id)->firstOrFail();
        $pivot->pivot->delete();

        return response()->json(['msg' => 'Interesse excluído.'], 200);
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        return response()->json($notifications, 200);
    }

    public function events()
    {
        $events = Auth::user()->events()->with([
            'address',
            'address.city',
            'address.city.state'
        ])->orderBy('starts_at', 'asc')->where('is_active', true)->get();

        return response()->json($events, 200);
    }
}
