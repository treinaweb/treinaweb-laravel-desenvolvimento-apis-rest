<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlunoUnico extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'nome_aluno' => $this->nome,
            'turma' => new TurmaResource($this->whenLoaded('turma')),
            'links' => [
                [
                    'type' => 'GET',
                    'url' => route('alunos.show', $this->id),
                    'rel' => 'aluno_detalhes'
                ],
                [
                    'type' => 'PUT',
                    'url' => route('alunos.update', $this->id),
                    'rel' => 'aluno_atualizar'
                ],
                [
                    'type' => 'DELETE',
                    'url' => route('alunos.destroy', $this->id),
                    'rel' => 'aluno_remover'
                ]
            ]
        ];
    }
}
