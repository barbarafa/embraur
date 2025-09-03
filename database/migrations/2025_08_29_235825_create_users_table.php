<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // PK UUID
            $table->bigIncrements('id')->primary();
            $table->string('email')->unique();
            $table->string('password'); // não use 'password' se quiser evitar conflito com Auth padrão
            $table->string('nome_completo');
            $table->string('telefone')->nullable();
            $table->string('cpf')->unique();
            $table->date('data_nascimento')->nullable();
            $table->string('foto_perfil')->nullable(); // URL da foto

            // Enums
            $table->enum('tipo_usuario', ['aluno', 'professor', 'admin'])->default('aluno');
            $table->enum('status', ['ativo', 'inativo', 'suspenso'])->default('ativo');

            // Timestamps customizados
            $table->timestamp('data_criacao')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
