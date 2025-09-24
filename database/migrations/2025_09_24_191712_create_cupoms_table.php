<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cupons', function (Blueprint $t) {
            $t->id();
            $t->string('codigo')->unique()->index();     // armazenar UPPER
            $t->enum('tipo', ['percentual','fixo']);
            $t->decimal('valor', 10, 2);
            $t->timestamp('inicio_em')->nullable();
            $t->timestamp('fim_em')->nullable();
            $t->boolean('ativo')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
