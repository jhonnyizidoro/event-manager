<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		if ($exception instanceof TokenExpiredException) {
			return json([], 'Token Expirado.', false, 403);
		} elseif ($exception instanceof TokenInvalidException) {
			return json([], 'Token Inválido.', false, 403);
		} elseif ($exception instanceof JWTException) {
			return json([], 'Erro ao validar o token. Verifique se ele foi informado na requisição.', false, 403);
		} elseif ($exception instanceof MethodNotAllowedHttpException) {
			return json([], 'A rota acessada não suporta o método utilizado.', false, 404);
		} elseif ($exception instanceof ModelNotFoundException) {
			return json([], 'Nenhum registro encontrado com o ID informado.', false, 404);
		}

        return parent::render($request, $exception);
    }
}
