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

        // 1) Matrículas dos cursos do professor
        $matriculas = Matriculas::query()
            ->with([
                // users tem 'nome_completo' (não 'nome' / 'name')
                'aluno:id,nome_completo',
                // relação 'curso' aponta para App\Models\Cursos (plural)
                'curso:id,titulo,professor_id',
            ])
            ->whereHas('curso', fn($cq) => $cq->where('professor_id', $profId))
            ->when($q, function ($query) use ($q) {
                // filtra por 'nome_completo' em users
                $query->whereHas('aluno', fn($aq) => $aq->where('nome_completo', 'like', "%{$q}%"));
            })
            ->orderByDesc('id') // evita updated_at (matriculas não tem)
            ->paginate(12);

        // 2) Agrega progresso na tabela progresso_aulas
        $matriculaIds = $matriculas->pluck('id')->all();

        $progresso = ProgressoAula::query()
            ->select([
                'matricula_id',
                DB::raw("
            ROUND(AVG(
                COALESCE(porcentagem_assistida,
                         CASE WHEN concluida = 1 THEN 100 ELSE 0 END)
            ), 0) AS percent
        "),
                DB::raw(" MAX(data_inicio) AS last_at "),
            ])
            ->whereIn('matricula_id', $matriculaIds)
            ->groupBy('matricula_id')
            ->get()
            ->keyBy('matricula_id');

        // 3) Formato para a view
        $alunos = collect($matriculas->items())->map(function ($m) use ($progresso) {
            $p   = $progresso->get($m->id);
            $pct = (int) ($p->percent ?? ($m->progresso_porcentagem ?? 0)); // <- usa progresso_porcentagem
            // fallback de data: usa last_at do progresso; senão, data_matricula
            $dt  = $p && $p->last_at ? Carbon::parse($p->last_at)
                : ($m->data_matricula ? Carbon::parse($m->data_matricula) : null);

            return [
                'id'      => $m->aluno->id ?? null,
                'nome'    => $m->aluno->nome_completo ?? '—', // <- pega nome_completo e mantém a chave 'nome' pra view
                'curso'   => $m->curso->titulo ?? '—',
                'percent' => max(0, min(100, $pct)),
                'quando'  => $dt ? $dt->diffForHumans() : '—',
            ];
        });

        return view('prof.alunos.index', [
            'alunos'     => $alunos,
            'matriculas' => $matriculas,
            'q'          => $q,
        ]);
    }

}
