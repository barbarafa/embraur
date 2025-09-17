<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Matriculas;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function store(Request $request, Cursos $curso)
    {
        $alunoId = $request->session()->get('aluno_id');

        Matriculas::firstOrCreate([
            'aluno_id' => $alunoId,
            'curso_id' => $curso->id,
        ]);

        return redirect()->route('aluno.dashboard')->with('ok','Matrícula realizada com sucesso!');
    }
}
