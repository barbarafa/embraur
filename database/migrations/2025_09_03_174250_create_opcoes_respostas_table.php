<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('opcoes_resposta', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('questao_id');
            $table->text('texto_opcao');
            $table->boolean('correta')->default(false);
            $table->integer('ordem')->default(0);

            $table->foreign('questao_id')->references('id')->on('questoes')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('opcoes_resposta');
    }
};
