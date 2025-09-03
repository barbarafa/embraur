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
        Schema::table('cursos', function (Blueprint $table) {
            $table->unsignedBigInteger('professor_id')->nullable()->after('id');
            // se tiver tabela 'professores', pode ativar a FK:
            // $table->foreign('professor_id')->references('id')->on('professores')->onDelete('cascade');
            $table->index('professor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // $table->dropForeign(['professor_id']); // se tiver criado a FK
            $table->dropIndex(['professor_id']);
            $table->dropColumn('professor_id');
        });
    }
};
