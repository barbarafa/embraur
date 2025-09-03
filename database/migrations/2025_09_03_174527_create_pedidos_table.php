<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id')->primary();
            $table->unsignedBigInteger('aluno_id'); // users.id
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->decimal('desconto_aplicado', 10, 2)->default(0);
            $table->string('cupom_usado')->nullable();
            $table->enum('status', ['pendente','pago','cancelado','estornado'])->default('pendente');
            $table->string('metodo_pagamento')->nullable();
            $table->string('referencia_pagamento_externa')->nullable();
            $table->timestamp('data_pedido')->useCurrent();
            $table->timestamp('data_pagamento')->nullable();

            $table->foreign('aluno_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pedidos');
    }
};
