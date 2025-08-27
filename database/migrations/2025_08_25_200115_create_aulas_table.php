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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->string('titulo');
            $table->enum('tipo', ['video', 'pdf', 'link', 'outro'])->default('video');
            $table->string('url_conteudo')->nullable();   // link do vídeo, pdf etc
            $table->integer('ordem')->default(0);
            $table->integer('percentual_minimo')->default(80); // ex: 80% para marcar concluída
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
