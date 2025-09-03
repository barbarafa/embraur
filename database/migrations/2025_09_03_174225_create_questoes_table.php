<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('questoes', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('aula_id');
            $table->text('pergunta');
            $table->enum('tipo', ['multipla_escolha','dissertativa','verdadeiro_falso'])->default('multipla_escolha');
            $table->decimal('pontos', 5, 2)->default(0);
            $table->integer('ordem')->default(0);

            $table->foreign('aula_id')->references('id')->on('aulas')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('questoes');
    }
};
