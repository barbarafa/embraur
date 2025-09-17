<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('duvidas_aluno', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('aula_id');
            $table->text('pergunta');
            $table->text('resposta')->nullable();
            $table->boolean('respondida')->default(false);
            $table->timestamp('data_pergunta')->useCurrent();
            $table->timestamp('data_resposta')->nullable();

            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
            $table->foreign('aula_id')->references('id')->on('aulas')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('duvidas_aluno');
    }
};
