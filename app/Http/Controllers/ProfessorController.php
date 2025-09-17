<?php

namespace App\Http\Controllers;

use App\Models\Cursos;
use App\Models\User;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function dashboard()
    {
        $professor = auth()->user();

        $cursos = Cursos::where('instrutor_id', $professor->id)
            ->withCount(['matriculas', 'avaliacoes'])
            ->get();

        $stats = [
            'total_cursos' => $cursos->count(),
            'total_alunos' => $cursos->sum('matriculas_count'),
            'receita_total' => $this->calcularReceitaTotal($professor->id),
            'receita_mensal' => $this->calcularReceitaMensal($professor->id)
        ];

        $alunosRecentes = User::whereIn('id', function($query) use ($professor) {
            $query->select('aluno_id')
                ->from('matriculas')
                ->whereIn('curso_id', function($subquery) use ($professor) {
                    $subquery->select('id')
                        ->from('cursos')
                        ->where('instrutor_id', $professor->id);
                })
                ->orderBy('created_at', 'desc')
                ->limit(10);
        })->get();

        return response()->json([
            'professor' => $professor,
            'cursos' => $cursos,
            'stats' => $stats,
            'alunos_recentes' => $alunosRecentes
        ]);
    }

    private function calcularReceitaTotal($professorId)
    {
        // Implementar cálculo baseado em pagamentos aprovados
        return 0; // Placeholder
    }

    private function calcularReceitaMensal($professorId)
    {
        // Implementar cálculo para o mês atual
        return 0; // Placeholder
    }
}
