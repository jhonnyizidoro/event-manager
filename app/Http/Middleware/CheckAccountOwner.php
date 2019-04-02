<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class CheckAccountOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$user = JWTAuth::parseToken()->authenticate();
		if ($user->is_admin || $request->id == $user->id) {
			return $next($request);
		}		
		return json([], 'O usuário não tem permissão para acessar essa rota.', false, 403);
    }
}
