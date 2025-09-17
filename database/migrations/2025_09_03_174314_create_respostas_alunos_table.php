<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('respostas_aluno', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('questao_id');
            $table->unsignedBigInteger('opcao_escolhida_id')->nullable();
            $table->longText('resposta_dissertativa')->nullable();
            $table->decimal('pontos_obtidos', 5, 2)->nullable();
            $table->timestamp('data_resposta')->useCurrent();

            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
            $table->foreign('questao_id')->references('id')->on('questoes')->cascadeOnDelete();
            $table->foreign('opcao_escolhida_id')->references('id')->on('opcoes_resposta')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('respostas_aluno');
    }
};
