<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('perfil_aluno', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('usuario_id');
            $table->string('empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->text('endereco_completo')->nullable();
            $table->timestamp('data_ultimo_acesso')->nullable();

            $table->foreign('usuario_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('perfil_aluno');
    }
};
