<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Cursos;
use App\Models\Matriculas;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProfessorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $profId   = (int) $request->session()->get('prof_id');
        $profNome = $request->session()->get('prof_nome', 'Carlos Silva');

        // Contadores
        $cursos       = Cursos::where('professor_id',$profId)->count();

        $alunos = Matriculas::doProfessor($profId)
            ->distinct('matriculas.aluno_id')
            ->count('matriculas.aluno_id');

        $receitaTotal = Pedido::receitaTotalProfessor($profId);
        $receitaMes   = Pedido::receitaMesProfessor($profId);

        // ATIVIDADE: paginação de 10 (nome, curso, % quizzes, situação, quando)
        $rows = Matriculas::doProfessor($profId)
            ->join('users','users.id','=','matriculas.aluno_id')
            ->select([
                'users.nome_completo as aluno_nome',
                'cursos.titulo as curso_titulo',
                'matriculas.*',
            ])
            ->orderByDesc('matriculas.data_matricula')
            ->paginate(10); // <<-- pagina 10 por vez

        $atividade = $rows->through(function (Matriculas $m) {
            $percent = $m->percentQuizzes();
            $quando  = optional($m->lastQuizAt())->diffForHumans() ?? '';

            return [
                'nome'     => $m->aluno?->nome_completo ?? $m->aluno_nome ?? 'Aluno',
                'curso'    => $m->curso?->titulo ?? $m->curso_titulo ?? 'Curso',
                'percent'  => $percent,
                'situacao' => $percent >= 100 ? 'Concluído' : 'Ativo',
                'quando'   => $quando,
            ];
        });

        // dúvidas segue vazio para manter contrato com o front
        $duvidas = [];

        return view('prof.dashboard', compact(
            'profNome','cursos','alunos','receitaTotal','receitaMes','atividade','duvidas'
        ));
    }
}
