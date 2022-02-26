<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Response;
use App\Http\Requests\AlunoRequest;
use App\Http\Resources\AlunoColecao;
use App\Http\Resources\AlunoUnico;
use Illuminate\Http\Request;
use SimpleXMLElement;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AlunoCollection
     */
    public function index(Request $request)
    {
        if ($request->query('relacoes') === 'turma') {
            $alunos = Aluno::with('turma')->paginate(2);
        } else {
            $alunos = Aluno::paginate(2);
        }

        return new AlunoColecao($alunos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AlunoRequest  $request
     * @return Response
     */
    public function store(AlunoRequest $request): Response
    {
        return response(
            Aluno::create($request->all()),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  Aluno $aluno
     * @return AlunoResource
     */
    public function show(Aluno $aluno)
    {
        if (request()->header("Accept") === "application/xml") {
            return $this->pegarAlunoXMLResponse($aluno);
        }

        if (request()->wantsJson()) {
            return new AlunoUnico($aluno);
        }

        return response('Formato de dado desconhecido');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AlunoRequest $request
     * @param  Aluno $aluno
     * @return Aluno
     */
    public function update(AlunoRequest $request, Aluno $aluno): Aluno
    {
        $aluno->update($request->all());

        return $aluno;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Aluno $aluno
     * @return array
     */
    public function destroy(Aluno $aluno)
    {
        $aluno->delete();

        return [];
    }

    /**
     * Retorna uma response com xml do aluno
     *
     * @param Aluno $aluno
     * @return Response
     */
    private function pegarAlunoXMLResponse(Aluno $aluno): Response
    {
        $aluno = $aluno->toArray();

        $xml = new SimpleXMLElement('<aluno/>');

        array_walk_recursive($aluno, function ($valor, $chave) use ($xml) {
            $xml->addChild($chave, $valor);
        });

        return response($xml->asXML())
            ->header('Content-Type', 'application/xml');
    }
}
