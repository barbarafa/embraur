<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\ProgressoAula;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $matriculas = Matricula::where('aluno_id', $user->id)
            ->with(['curso.categoria', 'curso.instrutor'])
            ->get();

        $stats = [
            'total_cursos' => $matriculas->count(),
            'cursos_concluidos' => $matriculas->where('status', 'concluido')->count(),
            'horas_estudadas' => $matriculas->sum('tempo_assistido'),
            'progresso_geral' => $matriculas->avg('progresso_geral')
        ];

        return response()->json([
            'user' => $user,
            'matriculas' => $matriculas,
            'stats' => $stats
        ]);
    }

    public function coursePlayer($courseId)
    {
        $matricula = Matricula::where('aluno_id', auth()->id())
            ->where('curso_id', $courseId)
            ->with([
                'curso.modulos.aulas',
                'progressoAulas'
            ])
            ->firstOrFail();

        return response()->json($matricula);
    }

    public function updateProgress(Request $request, $aulaId)
    {
        $validated = $request->validate([
            'tempo_assistido' => 'required|integer|min:0',
            'progresso_percentual' => 'required|integer|between:0,100',
            'concluida' => 'boolean'
        ]);

        $matricula = Matricula::where('aluno_id', auth()->id())
            ->whereHas('curso.modulos.aulas', function($q) use ($aulaId) {
                $q->where('id', $aulaId);
            })
            ->firstOrFail();

        $progresso = ProgressoAula::updateOrCreate(
            [
                'matricula_id' => $matricula->id,
                'aula_id' => $aulaId
            ],
            $validated
        );

        // Atualizar progresso geral da matrícula
        $this->updateMatriculaProgress($matricula);

        return response()->json($progresso);
    }

    private function updateMatriculaProgress($matricula)
    {
        $totalAulas = $matricula->curso->modulos->sum(function($modulo) {
            return $modulo->aulas->count();
        });

        $aulasCompletas = $matricula->progressoAulas()
            ->where('concluida', true)
            ->count();

        $progressoGeral = $totalAulas > 0 ? ($aulasCompletas / $totalAulas) * 100 : 0;

        $matricula->update([
            'progresso_geral' => $progressoGeral,
            'data_conclusao' => $progressoGeral >= 100 ? now() : null,
            'status' => $progressoGeral >= 100 ? 'concluido' : 'em_progresso'
        ]);
    }
}
