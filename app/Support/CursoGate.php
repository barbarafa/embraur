<?php

namespace App\Support;

use App\Models\Cursos;
use App\Models\Matricula;
use App\Models\Quiz;

class CursoGate
{
    // retorna true se aluno pode acessar $moduloIndex (0-based)
    public static function podeAcessarModulo(Cursos $curso, Matricula $matricula, int $moduloIndex): bool
    {
        if ($moduloIndex === 0) return true;

        $notaMin = (float)($curso->nota_minima_aprovacao ?? 0);
        // pega quiz de escopo=modulo do módulo anterior, se existir
        $modulos = $curso->modulos()->orderBy('ordem')->get();
        $prev = $modulos[$moduloIndex-1] ?? null;
        if (!$prev) return true;

        $quiz = Quiz::where('modulo_id', $prev->id)->first();
        if (!$quiz) return true; // se não tem quiz, não trava

        $tent = $quiz->tentativas()->where('matricula_id',$matricula->id)->latest()->first();
        if (!$tent) return false;

        return (float)$tent->nota_obtida >= $notaMin;
    }
}
