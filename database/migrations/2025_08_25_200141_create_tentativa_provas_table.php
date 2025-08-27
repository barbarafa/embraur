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
        Schema::create('tentativas_prova', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prova_id')->constrained('provas');
            $table->foreignId('matricula_id')->constrained('matriculas');
            $table->enum('status', ['em_andamento','submetida','corrigida'])->default('em_andamento');
            $table->decimal('nota', 5, 2)->nullable();
            $table->timestamp('iniciada_em')->nullable();
            $table->timestamp('finalizada_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentativa_provas');
    }
};
