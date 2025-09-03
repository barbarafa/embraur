<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aula_medias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aula_id'); // sem FK aqui!
            $table->string('tipo', 20)->default('arquivo'); // arquivo|url
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();

            $table->index(['aula_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aula_medias');
    }
};
