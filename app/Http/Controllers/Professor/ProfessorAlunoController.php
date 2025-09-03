<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matricula; // cada matrícula liga aluno a curso
use App\Models\Curso;

class ProfessorAlunoController extends Controller
{
    public function index(Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');
        $q      = trim((string) $request->get('q',''));

        $matriculas = Matricula::with(['aluno','curso:id,titulo,professor_id'])
            ->whereHas('curso', fn($qz) => $qz->where('professor_id',$profId))
            ->when($q !== '', function ($qry) use ($q) {
                $qry->whereHas('aluno', fn($qa) => $qa->where('nome','like',"%{$q}%"))
                    ->orWhereHas('curso', fn($qc) => $qc->where('titulo','like',"%{$q}%"));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // monta array simples para a view
        $alunos = $matriculas->map(function($m){
            return [
                'nome'    => $m->aluno->nome,
                'curso'   => $m->curso->titulo,
                'percent' => (int)($m->progresso ?? 0), // coluna progresso na matrícula
                'quando'  => optional($m->updated_at)->diffForHumans() ?? '—',
            ];
        });

        return view('prof.alunos.index', [
            'alunos'     => $alunos,
            'matriculas' => $matriculas, // ainda disponível p/ paginação
            'q'          => $q,
        ]);
    }
}
