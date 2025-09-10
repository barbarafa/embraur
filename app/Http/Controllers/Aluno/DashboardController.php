<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Matricula;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1) Identifica o aluno (auth guard OU sessão)
        $alunoId = auth('aluno')->id() ?? $request->session()->get('aluno_id');
        if (!$alunoId) {
            return redirect()->route('aluno.login');
        }

        // 2) Dados do aluno
        $aluno = User::where('tipo_usuario', 'aluno')->where('id', $alunoId)->first();

        // 3) Minhas matrículas + curso/categoria (ordem: mais recente primeiro)
        $matriculas = Matricula::with([
            'curso:id,titulo,imagem_capa,carga_horaria_total,nota_minima_aprovacao,categoria_id',
            'curso.categoria:id,nome'
        ])
            ->where('aluno_id', $alunoId)
            ->latest('matriculas.data_matricula')
            ->get();

        // 4) Estatísticas simples (ajuste conforme sua regra real)
        $horas = (int) round(
            $matriculas->pluck('curso.carga_horaria_total')->filter()->sum() / 60
        );
        $progressoMedio = (int) round(
            $matriculas->avg('progresso_porcentagem') ?? 0
        );

        $stats = [
            'cursos'         => $matriculas->count(),
            'concluidos'     => $matriculas->where('status', 'concluido')->count(),
            'horas'          => $horas,
            'progressoGeral' => $progressoMedio,
        ];

        // 5) Continuar aprendendo (lista e destaque do primeiro)
        $continuar = $matriculas->map(function ($m) {
            $curso   = $m->curso;
            $percent = (int) ($m->progresso_porcentagem ?? 0);

            return [
                'titulo'  => $curso->titulo ?? 'Curso',
                'thumb'   => $curso?->imagem_capa_url,   // usa accessor do model Cursos
                'percent' => $percent,
                // 👉 leva direto para a tela que criamos
                'link'    => $curso ? route('aluno.curso.conteudo', $curso->id) : route('aluno.cursos'),
            ];
        })->values();

        // 6) Item principal para o botão "Continuar" (primeira matrícula da lista)
        $matricula = $matriculas->first(); // usado em algumas views

        // 7) Atividades recentes (placeholder)
        $recentes = [];

        return view('aluno.dashboard', compact(
            'aluno',
            'matriculas',
            'stats',
            'continuar',
            'recentes',
            'matricula'
        ));
    }
}
