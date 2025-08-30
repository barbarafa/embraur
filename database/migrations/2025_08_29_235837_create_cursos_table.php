<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao');
            $table->unsignedInteger('carga_horaria');           // horas
            $table->decimal('preco', 10, 2);
            $table->decimal('preco_promocional', 10, 2)->nullable();
            $table->enum('nivel', ['Básico','Intermediário','Avançado'])->default('Básico');
            $table->decimal('avaliacao', 2, 1)->default(0);
            $table->unsignedInteger('alunos')->default(0);
            $table->boolean('popular')->default(false);
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cursos');
    }
};
