<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function store(Request $request, Curso $curso)
    {
        $alunoId = $request->session()->get('aluno_id');

        Matricula::firstOrCreate([
            'aluno_id' => $alunoId,
            'curso_id' => $curso->id,
        ]);

        return redirect()->route('aluno.dashboard')->with('ok','Matrícula realizada com sucesso!');
    }
}
