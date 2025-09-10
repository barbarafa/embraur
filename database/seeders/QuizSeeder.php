<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            // Cria um quiz para o curso 3 (escopo curso)
            $quizId = DB::table('quizzes')->insertGetId([
                'curso_id'        => 3,
                'modulo_id'       => 1,
                'titulo'          => 'Prova Final - Curso 3',
                'descricao'       => 'Avaliação final para verificar os conhecimentos adquiridos.',
                'escopo'          => 'curso',
                'correcao_manual' => false,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);

            // Questão 1
            $q1 = DB::table('quiz_questoes')->insertGetId([
                'quiz_id'     => $quizId,
                'enunciado'   => 'Qual é a altura mínima considerada trabalho em altura segundo a NR-35?',
                'tipo'        => 'multipla',
                'pontuacao'   => 2,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            DB::table('quiz_opcoes')->insert([
                [
                    'questao_id' => $q1,
                    'texto'      => 'Acima de 1 metro',
                    'correta'    => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'questao_id' => $q1,
                    'texto'      => 'Acima de 2 metros',
                    'correta'    => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'questao_id' => $q1,
                    'texto'      => 'Acima de 3 metros',
                    'correta'    => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            // Questão 2
            $q2 = DB::table('quiz_questoes')->insertGetId([
                'quiz_id'     => $quizId,
                'enunciado'   => 'Quais EPIs são obrigatórios em trabalho em altura?',
                'tipo'        => 'multipla',
                'pontuacao'   => 2,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            DB::table('quiz_opcoes')->insert([
                [
                    'questao_id' => $q2,
                    'texto'      => 'Capacete, cinto de segurança tipo paraquedista e talabarte',
                    'correta'    => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'questao_id' => $q2,
                    'texto'      => 'Luvas de raspa e máscara de solda',
                    'correta'    => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'questao_id' => $q2,
                    'texto'      => 'Botas comuns e óculos de sol',
                    'correta'    => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            // Questão 3 (texto, correção manual)
            DB::table('quiz_questoes')->insert([
                'quiz_id'     => $quizId,
                'enunciado'   => 'Explique os principais riscos de realizar trabalhos em altura sem planejamento.',
                'tipo'        => 'texto',
                'pontuacao'   => 3,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        });
    }
}
