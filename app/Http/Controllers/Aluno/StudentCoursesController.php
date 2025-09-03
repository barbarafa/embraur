<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentCoursesController extends Controller
{
    public function index(Request $request)
    {
        $aluno = $request->user('aluno');

        // Substituir pelas queries reais dos cursos do aluno
        $cursos = [
            [
                'titulo' => 'Segurança do Trabalho - NR10',
                'progresso' => 75,
                'aulas_feitas' => 34,
                'aulas_total' => 45,
                'link' => '#'
            ],
            [
                'titulo' => 'Primeiros Socorros no Trabalho',
                'progresso' => 25,
                'aulas_feitas' => 4,
                'aulas_total' => 15,
                'link' => '#'
            ],
        ];

        return view('aluno.cursos', compact('aluno','cursos'));
    }
}
