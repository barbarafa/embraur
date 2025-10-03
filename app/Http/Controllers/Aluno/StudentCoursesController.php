<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\User;
use Illuminate\Http\Request;

class StudentCoursesController extends Controller
{
    public function index(Request $request)
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $aluno = User::where('id', $alunoId)->where('tipo_usuario', 'aluno')->firstOrFail();

        $rows = Cursos::getCursosByAlunoId($alunoId);

        $cursos = $rows->map(function ($curso) {
            $progresso = (int) ($curso->progresso_porcentagem ?? 0);
            $total = (int) ($curso->aulas_total ?? 0);
            $feitas = $total > 0 ? (int) round($progresso * $total / 100) : 0;

            return [
                'titulo'        => $curso->titulo,
                'progresso'     => $progresso,
                'aulas_feitas'  => $feitas,
                'aulas_total'   => $total,
                'link'          => $curso ? route('aluno.curso.conteudo', $curso->id) : route('aluno.cursos'),
                '_model'        => $curso,
            ];
        });

        return view('aluno.cursos', compact('aluno','cursos'));
    }
}
