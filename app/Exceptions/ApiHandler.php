<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


trait ApiHandler
{
    public function tratarErros(Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $this->respostaPadrao(
                "registro-nao-encontrado",
                "O sistema não encontrou o registro que vocês está buscando",
                404
            );
        }

        if ($exception instanceof ValidationException) {
            return $this->respostaPadrao(
                "erro-validacao",
                "Os dados enviados são invalidos",
                400,
                $exception->errors()
            );
        }
    }

    public function respostaPadrao(string $code, string $mensagem, int $status, array $erros = null)
    {
        $dadosResposta = [
            'code' => $code,
            'message' => $mensagem,
            'status' => $status,
        ];

        if ($erros) {
            $dadosResposta = $dadosResposta + ['erros' => $erros];
        }

        return response(
            $dadosResposta,
            $status
        );
    }
}
