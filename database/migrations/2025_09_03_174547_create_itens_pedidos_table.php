<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('itens_pedido', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('curso_id');
            $table->integer('quantidade')->default(1);
            $table->decimal('preco_unitario', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);

            $table->foreign('pedido_id')->references('id')->on('pedidos')->cascadeOnDelete();
            $table->foreign('curso_id')->references('id')->on('cursos')->restrictOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('itens_pedido');
    }
};
