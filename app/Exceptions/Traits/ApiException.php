<?php

namespace App\Exceptions\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiException {

    // Trata as exceções da API
    protected function getJsonException($request, $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return $this->notFoundException();
        }

        if ($e instanceof HttpException) {
            return $this->httpException($e);
        }

        if ($e instanceof ValidationException) {
            return $this->validationException($e);
        }

        return $this->genericException($e);
    }

    // Retorna o error 404
    protected function notFoundException()
    {
        return $this->getResponse(
            "Recurso não encontrado",
            "04",
            404
        );
    }

    //Retornar error 500
    protected function genericException($e)
    {
        return $this->getResponse(
            $e->getMessage(),
            "05",
            500
        );
    }

    // Retornar erro de http
    protected function httpException($e)
    {
        return $this->getResponse(
            $e->getMessage(),
            "03",
            $e->getStatusCode()
        );
    }

    protected function validationException($e)
    {
        return response()->json($e->errors(), $e->status);
    }

    // Monta a resposta em json
    protected function getResponse($message, $code, $status)
    {
        return response()->json([
            "errors" => [
                "status" => $status,
                "code" => $code,
                "message" => $message
            ]
        ], $status);
    }

}
