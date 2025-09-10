<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\{Cursos, Matricula, Modulos, Aulas, QuizTentativa};
use Illuminate\Http\Request;

class CursoConteudoController extends Controller
{
    public function show(Request $request, Cursos $curso)
    {
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $matricula = Matricula::where('aluno_id', $alunoId)
            ->where('curso_id', $curso->id)
            ->firstOrFail();

        // carrega tudo para a página (sidebar + player)
        $curso->load(['modulos.aulas', 'modulos.quiz']);


        // escolhe módulo/aula atuais (padrão: primeiro módulo/aula)
        $moduloAtual = $curso->modulos->sortBy('ordem')->values()->first();
        abort_if(!$moduloAtual, 404, 'Curso sem módulos.');
        $aulaAtual = $moduloAtual->aulas->sortBy('ordem')->values()->first();
        abort_if(!$aulaAtual, 404, 'Módulo sem aulas.');

        // Gate de acesso ao módulo (se tiver helper)
        $modIndex = $curso->modulos->sortBy('ordem')->values()
            ->search(fn($m) => (int)$m->id === (int)$moduloAtual->id);
        if (class_exists(\App\Support\CursoGate::class)) {
            $pode = \App\Support\CursoGate::podeAcessarModulo($curso, $matricula, $modIndex);
            abort_if(!$pode, 403, 'Módulo bloqueado até aprovação no anterior.');
        }

        // navegação (dentro do módulo)
        $aulasOrdenadas = $moduloAtual->aulas->sortBy('ordem')->values();
        $idx = $aulasOrdenadas->search(fn($x) => (int)$x->id === (int)$aulaAtual->id);
        $prevAula = $idx > 0 ? $aulasOrdenadas[$idx - 1] : null;
        $nextAula = $idx < ($aulasOrdenadas->count() - 1) ? $aulasOrdenadas[$idx + 1] : null;

        // status das provas por módulo (para os badges Pend/OK/Reprovado)
        $quizIds = $curso->modulos->pluck('quiz.id')->filter()->values();
        $ultimaTentativaPorQuiz = collect();
        if ($quizIds->isNotEmpty()) {
            $ultimaTentativaPorQuiz = QuizTentativa::where('aluno_id', $alunoId)
                ->whereIn('quiz_id', $quizIds)
                ->orderByDesc('id')
                ->get()
                ->groupBy('quiz_id')
                ->map->first();
        }

        return view('aluno.curso-conteudo', [
            'curso'                  => $curso,
            'matricula'              => $matricula,
            'modulo'                 => $moduloAtual,
            'aula'                   => $aulaAtual,
            'prevAula'               => $prevAula,
            'nextAula'               => $nextAula,
            'modIndex'               => $modIndex,
            'ultimaTentativaPorQuiz' => $ultimaTentativaPorQuiz,
        ]);
    }
}
