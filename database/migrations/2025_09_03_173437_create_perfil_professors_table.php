<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('perfil_professor', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('usuario_id');
            $table->string('especialidade')->nullable();
            $table->text('biografia')->nullable();
            $table->string('curriculo_url')->nullable();
            $table->boolean('aprovado')->default(false);

            $table->foreign('usuario_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('perfil_professor');
    }
};
