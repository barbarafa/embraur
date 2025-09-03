<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('cupons_desconto', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('codigo')->unique();
            $table->enum('tipo', ['porcentagem','valor_fixo'])->default('porcentagem');
            $table->decimal('valor_desconto', 10, 2)->default(0);
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_fim')->nullable();
            $table->integer('limite_usos')->nullable();
            $table->integer('usos_realizados')->default(0);
            $table->boolean('ativo')->default(true);
        });
    }
    public function down(): void {
        Schema::dropIfExists('cupons_desconto');
    }
};
