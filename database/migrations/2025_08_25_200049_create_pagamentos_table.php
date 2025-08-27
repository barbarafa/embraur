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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->enum('metodo', ['pix','cartao','boleto']);
            $table->enum('status', ['pendente','aprovado','recusado','estornado'])->default('pendente');
            $table->decimal('valor', 10, 2);
            $table->json('payload_gateway')->nullable(); // resposta completa do Mercado Pago
            $table->timestamp('pago_em')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
