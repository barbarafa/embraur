<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matriculas;
use App\Models\ProgressoAula;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfessorAlunoController extends Controller
{
    public function index(Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');
        $q      = trim((string) $request->get('q', ''));

        // 1) Matrículas dos cursos do professor (com busca pelo nome)
        $matriculas = Matriculas::query()
            ->doProfessor($profId)
            ->with([
                'aluno:id,nome_completo',
                'curso:id,titulo,professor_id',
            ])
            ->when($q !== '', fn($query) => $query->whereHas('aluno', fn($aq) => $aq->where('nome_completo', 'like', "%{$q}%")))
            ->orderByDesc('matriculas.id')   // <- aqui!
            ->paginate(12);

        // 2) Formata para a view usando percentQuizzes() e lastQuizAt()
        $alunos = collect($matriculas->items())->map(function (Matriculas $m) {
            $percent = $m->percentQuizzes();                           // <- percent por quizzes
            $lastAt  = $m->lastQuizAt() ?: $m->data_matricula;         // <- última atividade ou data da matrícula

            return [
                'id'      => $m->aluno->id ?? null,
                'nome'    => $m->aluno->nome_completo ?? '—',
                'curso'   => $m->curso->titulo ?? '—',
                'percent' => max(0, min(100, (int) $percent)),
                'quando'  => $lastAt ? Carbon::parse($lastAt)->diffForHumans() : '—',
            ];
        });

        return view('prof.alunos.index', [
            'alunos'     => $alunos,
            'matriculas' => $matriculas, // mantém o paginator para links()
            'q'          => $q,
        ]);
    }

}
