<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quizzes', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $t->foreignId('modulo_id')->nullable()->constrained('modulos')->nullOnDelete();
            $t->string('titulo');
            $t->text('descricao')->nullable();
            $t->enum('escopo', ['curso','modulo'])->default('curso'); // onde será exigido
            $t->boolean('correcao_manual')->default(false); // se houver questões de texto
            $t->timestamps();
        });

        Schema::create('quiz_questoes', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $t->text('enunciado');
            $t->enum('tipo', ['texto','multipla'])->default('multipla');
            $t->decimal('pontuacao', 5, 2)->default(1);
            $t->timestamps();
        });

        Schema::create('quiz_opcoes', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('questao_id')->constrained('quiz_questoes')->cascadeOnDelete();
            $t->string('texto');
            $t->boolean('correta')->default(false);
            $t->timestamps();
        });

        Schema::create('quiz_tentativas', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $t->foreignId('aluno_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $t->decimal('nota_obtida', 5, 2)->nullable();
            $t->decimal('nota_maxima', 5, 2)->nullable();
            $t->boolean('aprovado')->default(false);
            $t->timestamp('concluido_em')->nullable();
            $t->timestamps();
        });

        Schema::create('quiz_respostas', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('tentativa_id')->constrained('quiz_tentativas')->cascadeOnDelete();
            $t->foreignId('questao_id')->constrained('quiz_questoes')->cascadeOnDelete();
            $t->foreignId('opcao_id')->nullable()->constrained('quiz_opcoes')->nullOnDelete();
            $t->text('resposta_texto')->nullable();
            $t->decimal('pontuacao_obtida', 5, 2)->nullable();
            $t->timestamps();
            $t->unique(['tentativa_id','questao_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('quiz_respostas');
        Schema::dropIfExists('quiz_tentativas');
        Schema::dropIfExists('quiz_opcoes');
        Schema::dropIfExists('quiz_questoes');
        Schema::dropIfExists('quizzes');
    }
};
