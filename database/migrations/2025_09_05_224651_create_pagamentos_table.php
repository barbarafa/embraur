<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pagamentos', function (Blueprint $t) {
            $t->bigIncrements('id')->primary();
            $t->foreignId('aluno_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('matricula_id')->nullable()->constrained('matriculas')->nullOnDelete();
            $t->decimal('valor', 10, 2);
            $t->string('moeda', 10)->default('BRL');
            $t->string('status')->default('pendente'); // aprovado, recusado, devolvido
            $t->string('gateway')->default('mercadopago');
            $t->string('mp_preference_id')->nullable();
            $t->string('mp_payment_id')->nullable();
            $t->string('external_reference')->nullable();
            $t->json('raw_payload')->nullable();
            $t->timestamps();
        });


    }
    public function down(): void {
        Schema::dropIfExists('pagamentos');
        Schema::table('matriculas', function (Blueprint $t) {
            $t->dropUnique(['aluno_id','curso_id']);
        });
    }
};
