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
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_id')->constrained('provas')->onDelete('cascade');
            $table->enum('tipo', ['objetiva','multipla','dissertativa']);
            $table->longText('enunciado');
            $table->json('opcoes')->nullable(); // alternativas
            $table->json('resposta_correta')->nullable();
            $table->integer('peso')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questaos');
    }
};
