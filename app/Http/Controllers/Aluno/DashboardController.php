<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\PerfilAluno;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1) garante sessão do aluno
        $alunoId = $request->session()->get('aluno_id');
        if (!$alunoId) {
            return redirect()->route('aluno.login');
        }


        $aluno = User::where('tipo_usuario', 'aluno')->where('id',$alunoId)->first();

        $matriculas = Matricula::with(['curso:id,titulo,imagem_capa', 'curso.categoria:id,nome'])
            ->where('aluno_id', $alunoId)
            ->latest('matriculas.data_matricula')
            ->get();

        // 3) estatísticas (placeholder simples)
        $stats = [
            'cursos'         => $matriculas->count(),
            'concluidos'     => $matriculas->where('status', 'concluido')->count(),
            'horas'          => 40,
            'progressoGeral' => 33,
        ];

        // 4) continuar aprendendo (mapeia com segurança)
        $continuar = $matriculas->map(function ($m) {
            $curso   = $m->curso;
            $percent = $m->progresso_porcentagem  ?? 0;
           // $feitas  = $m->aulas_concluidas ?? 0;
           // $total   = $curso->aulas_count ?? 0;

            return [
                'titulo'       => $curso->titulo ?? 'Curso',
                'thumb'        => $curso->capa ?? null,
             //   'aulas_feitas' => $feitas,
             //   'aulas_total'  => $total,
              //  'percent'      => (int) $percent,
                'link'         => route('aluno.cursos'),
            ];
        })->values();

        // 5) atividades recentes (preenche depois)
        $recentes = [];

        return view('aluno.dashboard', compact('aluno','matriculas','stats','continuar','recentes'));
    }
}
