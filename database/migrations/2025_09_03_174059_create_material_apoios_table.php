<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('materiais_apoio', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('aula_id');
            $table->string('nome_arquivo');
            $table->string('tipo_arquivo')->nullable();
            $table->string('url_download');
            $table->integer('tamanho_kb')->nullable();

            $table->foreign('aula_id')->references('id')->on('aulas')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('materiais_apoio');
    }
};
