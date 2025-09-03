<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('progresso_aulas', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('aula_id');
            $table->integer('tempo_assistido_segundos')->default(0);
            $table->decimal('porcentagem_assistida', 5, 2)->default(0);
            $table->boolean('concluida')->default(false);
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_conclusao')->nullable();

            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
            $table->foreign('aula_id')->references('id')->on('aulas')->cascadeOnDelete();
            $table->unique(['matricula_id','aula_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('progresso_aulas');
    }
};
