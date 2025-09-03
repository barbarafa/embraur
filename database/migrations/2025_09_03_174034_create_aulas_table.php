<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('aulas', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('modulo_id');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['video','texto','quiz','arquivo'])->default('video');
            $table->integer('duracao_minutos')->default(0);
            $table->string('conteudo_url')->nullable();
            $table->longText('conteudo_texto')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('liberada_apos_anterior')->default(false);
            $table->timestamp('data_criacao')->useCurrent();

            $table->foreign('modulo_id')->references('id')->on('modulos')->cascadeOnDelete();
            $table->index(['modulo_id','ordem']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('aulas');
    }
};
