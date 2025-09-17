<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('modulos', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('curso_id');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->integer('ordem')->default(0);
            $table->timestamp('data_criacao')->useCurrent();

            $table->foreign('curso_id')->references('id')->on('cursos')->cascadeOnDelete();
            $table->index(['curso_id','ordem']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('modulos');
    }
};
