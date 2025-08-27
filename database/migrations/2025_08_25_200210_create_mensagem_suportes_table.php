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
        Schema::create('mensagens_suporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('curso_id')->nullable()->constrained('cursos');
            $table->foreignId('aula_id')->nullable()->constrained('aulas');
            $table->string('assunto');
            $table->longText('mensagem');
            $table->enum('status', ['aberta','respondida','fechada'])->default('aberta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagem_suportes');
    }
};
