<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('anotacoes_aluno', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('aula_id');
            $table->longText('conteudo');
            $table->integer('tempo_video_segundos')->nullable();
            $table->timestamp('data_criacao')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
            $table->foreign('aula_id')->references('id')->on('aulas')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('anotacoes_aluno');
    }
};
