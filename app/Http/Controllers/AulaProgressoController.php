<?php

namespace App\Http\Controllers;

use App\Models\AulaProgresso;
use App\Models\Aulas;
use Illuminate\Http\Request;

class AulaProgressoController extends Controller
{
    public function show(Request $rq, Aulas $aula)
    {
        $alunoId = auth('aluno')->id() ?? $rq->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $p = AulaProgresso::where('aluno_id',$alunoId)->where('aula_id',$aula->id)->first();
        return response()->json([
            'segundos_assistidos' => $p->segundos_assistidos ?? 0,
            'duracao_total' => $p->duracao_total ?? 0,
        ]);
    }

    public function store(Request $rq, Aulas $aula)
    {
        $alunoId = auth('aluno')->id() ?? $rq->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $data = $rq->validate([
            'segundos_assistidos' => 'required|integer|min:0',
            'duracao_total' => 'required|integer|min:0',
        ]);

        AulaProgresso::updateOrCreate(
            ['aluno_id'=>$alunoId, 'aula_id'=>$aula->id],
            $data
        );

        return response()->json(['ok'=>true]);
    }
}
