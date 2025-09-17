<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProfessorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $profId = (int) $request->session()->get('prof_id');
        $profNome = $request->session()->get('prof_nome', 'Carlos Silva');

        // Contadores seguros (não quebram se tabela/coluna não existir)
        $cursos = 0;
        $alunos = 0;
        $receitaTotal = 0.0;
        $receitaMes = 0.0;

        if (Schema::hasTable('cursos') && Schema::hasColumn('cursos','professor_id')) {
            $cursos = DB::table('cursos')->where('professor_id',$profId)->count();
        }

        // Se existir tabela de matrículas, contamos alunos únicos
        if (Schema::hasTable('matriculas')) {
            $alunos = DB::table('matriculas')
                ->when(Schema::hasColumn('matriculas','professor_id'), fn($q) => $q->where('professor_id',$profId))
                ->when(Schema::hasColumn('matriculas','curso_id') && Schema::hasTable('cursos') && Schema::hasColumn('cursos','professor_id'),
                    function ($q) use ($profId) {
                        // fallback: junta com cursos para filtrar pelo professor
                        $q->join('cursos','cursos.id','=','matriculas.curso_id')
                            ->where('cursos.professor_id',$profId);
                    })
                ->distinct('aluno_id')
                ->count('aluno_id');
        }

        // Receita total / mês (se existir tabela pagamentos)
        if (Schema::hasTable('pagamentos')) {
            $receitaTotal = (float) DB::table('pagamentos')
                ->when(Schema::hasColumn('pagamentos','professor_id'), fn($q) => $q->where('professor_id',$profId))
                ->sum('valor');

            $receitaMes = (float) DB::table('pagamentos')
                ->when(Schema::hasColumn('pagamentos','professor_id'), fn($q) => $q->where('professor_id',$profId))
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('valor');
        }

        // Atividade e dúvidas (placeholders por enquanto)
        $atividade = [
            ['nome' => 'João Silva',   'curso' => 'NR10', 'percent' => 45, 'situacao' => 'Ativo',    'quando' => '2 horas atrás'],
            ['nome' => 'Maria Santos', 'curso' => 'NR35', 'percent' => 100,'situacao' => 'Concluído','quando' => '1 dia atrás'],
            ['nome' => 'Pedro Oliveira','curso'=>'NR10', 'percent' => 23, 'situacao' => 'Ativo',    'quando' => '3 dias atrás'],
        ];

        $duvidas = [
            ['aluno'=>'Ana Costa',    'curso'=>'NR10', 'texto'=>'Qual a diferença entre aterramento funcional e de proteção?','quando'=>'2 horas atrás'],
            ['aluno'=>'Roberto Lima', 'curso'=>'NR35', 'texto'=>'Como calcular a força de retenção de um EPI?','quando'=>'1 dia atrás'],
        ];

        return view('prof.dashboard', compact(
            'profNome','cursos','alunos','receitaTotal','receitaMes','atividade','duvidas'
        ));
    }
}
