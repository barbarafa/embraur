<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use Illuminate\Http\Request;

class CursoConteudoController extends Controller
{
    public function show(Request $request, Cursos $curso)
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $curso->load(['modulos.aulas' => fn($q) => $q->orderBy('ordem')]); // traz estrutura
        $matricula = \App\Models\Matricula::where('aluno_id', $alunoId)
            ->where('curso_id', $curso->id)
            ->firstOrFail();

        return view('aluno.curso-conteudo', compact('curso', 'matricula'));
    }
}
