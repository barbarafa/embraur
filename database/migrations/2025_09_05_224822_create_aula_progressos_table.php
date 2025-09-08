<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('aula_progresso', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('aluno_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('aula_id')->constrained('aulas')->cascadeOnDelete();
            $t->unsignedInteger('segundos_assistidos')->default(0);
            $t->unsignedInteger('duracao_total')->default(0);
            $t->timestamps();
            $t->unique(['aluno_id','aula_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('aula_progresso');
    }
};
