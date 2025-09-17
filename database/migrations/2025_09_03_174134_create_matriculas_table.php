<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('aluno_id'); // users.id
            $table->unsignedBigInteger('curso_id');
            $table->timestamp('data_matricula')->useCurrent();
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_conclusao')->nullable();
            $table->timestamp('data_vencimento')->nullable();
            $table->decimal('progresso_porcentagem', 5, 2)->default(0);
            $table->enum('status', ['ativo','concluido','expirado','cancelado'])->default('ativo');
            $table->decimal('nota_final', 5, 2)->nullable();

            $table->foreign('aluno_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('curso_id')->references('id')->on('cursos')->cascadeOnDelete();
            $table->unique(['aluno_id','curso_id']); // evita matrícula duplicada
        });
    }
    public function down(): void {
        Schema::dropIfExists('matriculas');
    }
};
