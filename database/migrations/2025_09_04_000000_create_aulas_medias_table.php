<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('aula_medias', function (Blueprint $table) {
            // se existir FK antiga, remova silenciosamente
            try { $table->dropForeign(['aula_id']); } catch (\Throwable $e) {}

            // cria a FK agora que 'aulas' já existe
            $table->foreign('aula_id')
                ->references('id')->on('aulas')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('aula_medias', function (Blueprint $table) {
            try { $table->dropForeign(['aula_id']); } catch (\Throwable $e) {}
        });
    }
};
