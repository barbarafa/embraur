<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('configuracoes_sistema', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->string('chave')->unique();
            $table->text('valor')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamp('data_atualizacao')->useCurrent()->useCurrentOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('configuracoes_sistema');
    }
};
