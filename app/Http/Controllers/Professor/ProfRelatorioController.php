<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Matriculas;
use App\Models\Cursos;
use App\Models\Pedido;

class ProfRelatorioController extends Controller
{
    public function index(Request $request)
    {
        return view('prof.relatorios.index', ['active' => 'relatorios']);
    }

    /** Relatório de Alunos (filtros: nome, curso, status, período de matrícula) */
    public function alunos(Request $request)
    {
        $profId     = (int) $request->session()->get('prof_id');
        $q          = trim((string) $request->get('q', ''));
        $cursoId    = (int) $request->get('curso_id', 0);
        $status     = $request->get('status', '');
        $from       = $request->get('from', '');
        $to         = $request->get('to', '');

        $query = Matriculas::query()
            ->whereIn('curso_id', Cursos::where('professor_id',$profId)->pluck('id'))
            ->with(['aluno:id,nome_completo','curso:id,titulo'])
            ->when($q !== '', fn($qq)=>$qq->whereHas('aluno', fn($aq)=>$aq->where('nome_completo','like',"%{$q}%")))
            ->when($cursoId>0, fn($qq)=>$qq->where('curso_id',$cursoId))
            ->when($status !== '', fn($qq)=>$qq->where('status',$status))
            ->when($from && $to, fn($qq)=>$qq->whereBetween('data_matricula', [$from, $to]))
            ->orderByDesc('id');

        $rows = $query->paginate(15)->withQueryString();

        return view('prof.relatorios.alunos', [
            'active'   => 'relatorios',
            'rows'     => $rows,
            'filtro'   => compact('q','cursoId','status','from','to'),
            'cursos'   => Cursos::where('professor_id',$profId)->orderBy('titulo')->get(['id','titulo']),
        ]);
    }

    /** Relatório de Pedidos/Financeiro (filtros: status, período, método, curso) */
    public function pedidos(Request $request)
    {
        $profId  = (int) $request->session()->get('prof_id');
        $status  = $request->get('status','');         // pendente|pago|cancelado|estornado
        $from    = $request->get('from','');
        $to      = $request->get('to','');
        $metodo  = $request->get('metodo_pagamento','');
        $cursoId = (int) $request->get('curso_id', 0);

        // Listagem por pedido com total somente dos itens do professor
        $list = DB::table('pedidos as p')
            ->join('itens_pedido as ip','ip.pedido_id','=','p.id')
            ->join('cursos as c','c.id','=','ip.curso_id')
            ->join('users as u','u.id','=','p.aluno_id')
            ->where('c.professor_id',$profId)
            ->when($status !== '', fn($q)=>$q->where('p.status',$status))
            ->when($metodo !== '', fn($q)=>$q->where('p.metodo_pagamento',$metodo))
            ->when($cursoId>0, fn($q)=>$q->where('c.id',$cursoId))
            ->when($from && $to, fn($q)=>$q->whereBetween('p.data_pagamento', [$from, $to]))
            ->groupBy('p.id','u.nome_completo','p.status','p.metodo_pagamento','p.data_pagamento')
            ->select([
                'p.id',
                'u.nome_completo as aluno',
                'p.status',
                'p.metodo_pagamento',
                'p.data_pagamento',
                DB::raw('SUM(ip.subtotal) as valor_prof'),
                DB::raw('COUNT(ip.id) as itens_prof')
            ])
            ->orderByDesc('p.id')
            ->paginate(15)->withQueryString();

        // KPIs rápidos (no mesmo filtro)
        $totais = DB::table('pedidos as p')
            ->join('itens_pedido as ip','ip.pedido_id','=','p.id')
            ->join('cursos as c','c.id','=','ip.curso_id')
            ->where('c.professor_id',$profId)
            ->when($status !== '', fn($q)=>$q->where('p.status',$status))
            ->when($metodo !== '', fn($q)=>$q->where('p.metodo_pagamento',$metodo))
            ->when($cursoId>0, fn($q)=>$q->where('c.id',$cursoId))
            ->when($from && $to, fn($q)=>$q->whereBetween('p.data_pagamento', [$from, $to]))
            ->selectRaw('SUM(ip.subtotal) as soma, COUNT(DISTINCT p.id) as pedidos')
            ->first();

        return view('prof.relatorios.pedidos', [
            'active' => 'relatorios',
            'rows'   => $list,
            'totais' => $totais,
            'filtro' => compact('status','from','to','metodo','cursoId'),
            'cursos' => Cursos::where('professor_id',$profId)->orderBy('titulo')->get(['id','titulo']),
        ]);
    }

    /** Relatório de Cursos (filtros: curso, status do curso) */
    public function cursos(Request $request)
    {
        $profId   = (int) $request->session()->get('prof_id');
        $cursoId  = (int) $request->get('curso_id', 0);
        $status   = $request->get('status',''); // ex.: publicado|rascunho|oculto (ajuste ao seu enum)

        // Métricas por curso do professor
        $base = Cursos::query()
            ->where('professor_id',$profId)
            ->when($cursoId>0, fn($q)=>$q->where('id',$cursoId))
            ->when($status !== '', fn($q)=>$q->where('status',$status));

        $rows = $base
            ->leftJoin('matriculas as m','m.curso_id','=','cursos.id')
            ->leftJoin('itens_pedido as ip','ip.curso_id','=','cursos.id')
            ->leftJoin('pedidos as p', function($j){
                $j->on('p.id','=','ip.pedido_id')->where('p.status','pago');
            })
            ->groupBy('cursos.id','cursos.titulo','cursos.status')
            ->select([
                'cursos.id',
                'cursos.titulo',
                'cursos.status',
                DB::raw('COUNT(DISTINCT m.aluno_id) as alunos'),
                DB::raw('COUNT(DISTINCT p.id) as pedidos_pagos'),
                DB::raw('COALESCE(SUM(ip.subtotal),0) as receita_total')
            ])
            ->orderBy('cursos.titulo')
            ->paginate(15)->withQueryString();

        return view('prof.relatorios.cursos', [
            'active' => 'relatorios',
            'rows'   => $rows,
            'filtro' => compact('cursoId','status'),
            'cursos' => Cursos::where('professor_id',$profId)->orderBy('titulo')->get(['id','titulo']),
        ]);
    }
}
