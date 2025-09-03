<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('duvidas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('professor_id')->constrained('professores')->cascadeOnDelete();

            $table->string('assunto', 180)->nullable();
            $table->longText('texto');
            $table->boolean('lida')->default(false);

            $table->timestamps();

            $table->index(['professor_id', 'lida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duvidas');
    }
};
