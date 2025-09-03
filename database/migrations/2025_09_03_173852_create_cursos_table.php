<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('professor_id'); // users.id
            $table->unsignedBigInteger('categoria_id'); // categorias.id

            $table->string('titulo');
            $table->string('descricao_curta')->nullable();
            $table->longText('descricao_completa')->nullable();
            $table->string('imagem_capa')->nullable();
            $table->string('video_introducao')->nullable();
            $table->enum('nivel', ['iniciante', 'intermediario', 'avancado'])->default('iniciante');
            $table->integer('carga_horaria_total')->default(0); // minutos
            $table->decimal('preco', 10, 2)->nullable();
            $table->decimal('preco_original', 10, 2)->nullable();
            $table->decimal('nota_minima_aprovacao', 5, 2)->default(0);
            $table->integer('maximo_alunos')->nullable();
            $table->integer('validade_dias')->nullable();
            $table->enum('status', ['rascunho', 'publicado', 'arquivado'])->default('rascunho');
            $table->timestamp('data_criacao')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('professor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('categoria_id')->references('id')->on('categorias')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
