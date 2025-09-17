<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) progresso_aulas: garantir timestamps (o Model usa $timestamps = true)
        if (!Schema::hasColumn('progresso_aulas', 'created_at')) {
            Schema::table('progresso_aulas', function (Blueprint $t) {
                $t->timestamps(); // created_at, updated_at
            });
            // inicializa updated_at/created_at para registros antigos
            DB::table('progresso_aulas')->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2) Backfill desde aula_progresso (se existir)
        if (Schema::hasTable('aula_progresso')) {
            // Fazemos um merge por JOIN com matriculas (aluno+curso)
            // Observação: usamos duracao_total quando disponível; senão, queda para aulas.duracao_minutos
            $rows = DB::table('aula_progresso as ap')
                ->join('aulas as a', 'a.id', '=', 'ap.aula_id')
                ->join('modulos as m', 'm.id', '=', 'a.modulo_id')
                ->join('cursos as c', 'c.id', '=', 'm.curso_id')
                ->join('matriculas as mat', function ($j) {
                    $j->on('mat.aluno_id', '=', 'ap.aluno_id')
                        ->on('mat.curso_id', '=', 'c.id');
                })
                ->selectRaw('mat.id as matricula_id, ap.aula_id,
                             ap.segundos_assistidos,
                             ap.duracao_total,
                             a.duracao_minutos')
                ->get();

            foreach ($rows as $r) {
                $durTotal = (int)($r->duracao_total ?: ($r->duracao_minutos * 60));
                $durTotal = max(1, $durTotal); // evita divisão por zero
                $pct = min(100, round(($r->segundos_assistidos / $durTotal) * 100, 2));

                DB::table('progresso_aulas')->upsert([
                    'matricula_id' => $r->matricula_id,
                    'aula_id'      => $r->aula_id,
                    'tempo_assistido_segundos' => (int)$r->segundos_assistidos,
                    'porcentagem_assistida'    => $pct,
                    'concluida'    => $pct >= 90, // regra padrão
                    'data_inicio'  => now(),
                    'data_conclusao'=> $pct >= 90 ? now() : null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ], ['matricula_id','aula_id'], [
                    'tempo_assistido_segundos','porcentagem_assistida','concluida','data_conclusao','updated_at'
                ]);
            }

            // 3) Drop da tabela antiga
            Schema::drop('aula_progresso');
        }

        // 4) Certificados: padroniza nomes se alguém migrou errado no passado
        if (Schema::hasTable('certificados')) {
            // renomeia coluna codigo_validacao -> codigo_verificacao, se existir
            if (Schema::hasColumn('certificados', 'codigo_validacao') && !Schema::hasColumn('certificados','codigo_verificacao')) {
                Schema::table('certificados', function (Blueprint $t) {
                    $t->renameColumn('codigo_validacao', 'codigo_verificacao');
                });
            }
        }
    }

    public function down(): void
    {
        // não recriamos aula_progresso; rollback só remove timestamps adicionados
        if (Schema::hasTable('progresso_aulas')) {
            Schema::table('progresso_aulas', function (Blueprint $t) {
                if (Schema::hasColumn('progresso_aulas','created_at')) {
                    $t->dropTimestamps();
                }
            });
        }
    }
};
