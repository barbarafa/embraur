<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aula_medias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')->constrained('aulas')->cascadeOnDelete();
            $table->string('tipo', 20)->default('arquivo'); // arquivo|url
            $table->string('path')->nullable();  // storage/app/public/...
            $table->string('url')->nullable();   // youtube/vídeo externo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aula_medias');
    }
};
