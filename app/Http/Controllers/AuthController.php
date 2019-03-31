<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\Login as LoginRequest;
use Auth;

class AuthController extends Controller
{
	public function login(LoginRequest $request)
	{
		$credentials = $request->only(['email', 'password']);
		$token = Auth::attempt($credentials);

		if ($token) {
			return json(self::getTokenInfo($token), 'Login efetuado com sucesso.');
		}
		return json([], 'Não encontramos nenhum usuário com as credênciais informadas.');
	}

	public function logout()
	{
		Auth::logout();
		return json([], 'Logout efetuado com sucesso.');
	}

	public function refresh()
	{
		$token = Auth::refresh();
		return json(self::getTokenInfo($token), 'Token atualizado com sucesso.');
	}

	protected static function getTokenInfo($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
