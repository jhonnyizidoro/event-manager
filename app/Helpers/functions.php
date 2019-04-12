<?php
/**
* @param retorno: objeto ou array com dados de retorno
* @param mensagemDeRetorno: mensagem que será retornada
* @param status: booleano que indica se a requisição teve ou não sucesso
* @param httpCode: código HTTP da requisição
* @return: JSON com os dados informados
*/
if (!function_exists('json'))
{
	function json($retorno, $mensagemDeRetorno, bool $status = true, int $httpCode = 200)
	{
		return response()->json([
			'status' => $status,
			'http_code' => $httpCode,
			'message' => $mensagemDeRetorno,
			'data' => $retorno
		], $httpCode, [], JSON_UNESCAPED_SLASHES);
	}
}