<?php

namespace App\Services;

use App\Models\{Matriculas, ProgressoAula, Quiz, QuizTentativa, Certificados, Cursos};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CourseCompletionService
{
    // regra padrão para concluir vídeo
    private int $pctMin = 90;

    public function touchAndMaybeComplete(Matriculas $matricula): void
    {
        DB::transaction(function() use ($matricula) {
            $curso = $matricula->curso()->with(['modulos.quiz'])->first();

            // 1) Atualiza % de progresso baseada nas aulas concluídas
            $totalAulas = $curso->aulas()->count();
            $concluidas = ProgressoAula::where('matricula_id',$matricula->id)->where('concluida',1)->count();
            $pctCurso   = $totalAulas ? round(($concluidas / $totalAulas) * 100, 2) : 0;

            $matricula->progresso_porcentagem = $pctCurso;
            $matricula->save();

            // 2) Decide critério de conclusão
            $quizzes = Quiz::query()
                ->where(function($q) use ($curso){
                    $q->where('curso_id',$curso->id)->where('escopo','curso');
                })
                ->orWhereIn('modulo_id',$curso->modulos->pluck('id')->all())
                ->get();

            $temProvas = $quizzes->count() > 0;

            $concluir = false;

            if ($temProvas) {
                $notaMin = (float)($curso->nota_minima_aprovacao ?? 7.0); // 0..10
                $todosAprovados = $quizzes->every(function($quiz) use ($matricula, $notaMin){
                    $ult = QuizTentativa::where('quiz_id',$quiz->id)
                        ->where('matricula_id',$matricula->id)
                        ->orderByDesc('id')->first();
                    return $ult && (bool)$ult->aprovado && (float)$ult->nota_obtida >= $notaMin;
                });
                $concluir = $todosAprovados;
            } else {
                $concluir = ($totalAulas > 0) && ($concluidas >= $totalAulas);
            }

            if ($concluir && $matricula->status !== 'concluido') {
                $matricula->status = 'concluido';
                $matricula->data_conclusao = now();
                // nota_final: se houver provas, média das últimas notas aprovadas; senão null
                if ($temProvas) {
                    $notas = $quizzes->map(function($q) use ($matricula){
                        $t = QuizTentativa::where('quiz_id',$q->id)->where('matricula_id',$matricula->id)->orderByDesc('id')->first();
                        return $t? (float)$t->nota_obtida : null;
                    })->filter();
                    $matricula->nota_final = $notas->count() ? round($notas->avg(), 2) : null;
                }
                $matricula->save();

//                 emite certificado se ainda não houver
                $existe = Certificados::where('matricula_id',$matricula->id)->exists();
                if (!$existe) {
                    Certificados::create([
                        'matricula_id'       => $matricula->id,
                        'codigo_verificacao' => strtoupper(Str::random(10)),
                        'data_emissao'       => now(),
                        'valido'             => true,
                        'url_certificado'    => null, // se gerar PDF/URL, preencha aqui
                        'qr_code_url'        => null,
                    ]);
                }
            }
        });
    }
}
