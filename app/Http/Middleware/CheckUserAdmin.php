<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class CheckUserAdmin
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
		if ($user->is_admin) {
			return $next($request);
		}		
		return json([], 'O usuário não ter permissão para acessar essa rota.', false, 403);
    }
}
