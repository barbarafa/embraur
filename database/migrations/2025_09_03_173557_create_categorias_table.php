<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('categorias', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('icone')->nullable();
            $table->integer('ordem_exibicao')->default(0);
        });
    }
    public function down(): void {
        Schema::dropIfExists('categorias');
    }
};
