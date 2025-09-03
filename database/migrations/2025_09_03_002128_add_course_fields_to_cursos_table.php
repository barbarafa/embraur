<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // já tínhamos 'professor_id' em outra migration; se não tiver, descomente:
            // if (!Schema::hasColumn('cursos','professor_id')) {
            //     $table->unsignedBigInteger('professor_id')->nullable()->after('id')->index();
            // }

            if (!Schema::hasColumn('cursos','categoria_id')) {
                $table->unsignedBigInteger('categoria_id')->nullable()->after('professor_id')->index();
            }

            if (!Schema::hasColumn('cursos','slug')) {
                $table->string('slug')->unique()->after('titulo');
            }

            if (!Schema::hasColumn('cursos','resumo')) {
                $table->string('resumo', 500)->nullable()->after('titulo');
            }

            if (!Schema::hasColumn('cursos','descricao')) {
                $table->longText('descricao')->nullable()->after('resumo');
            }

            if (!Schema::hasColumn('cursos','preco')) {
                $table->decimal('preco', 10, 2)->nullable()->after('descricao');
            }

            if (!Schema::hasColumn('cursos','nivel')) {
                $table->string('nivel', 50)->nullable()->after('preco');
            }

            if (!Schema::hasColumn('cursos','carga_horaria')) {
                $table->integer('carga_horaria')->nullable()->after('nivel');
            }

            if (!Schema::hasColumn('cursos','max_alunos')) {
                $table->integer('max_alunos')->nullable()->after('carga_horaria');
            }

            if (!Schema::hasColumn('cursos','publicado')) {
                $table->boolean('publicado')->default(false)->after('max_alunos');
            }

            if (!Schema::hasColumn('cursos','capa_path')) {
                $table->string('capa_path')->nullable()->after('publicado');
            }

            // campos opcionais usados na tela
            if (!Schema::hasColumn('cursos','tags')) {
                $table->json('tags')->nullable()->after('capa_path');
            }
            if (!Schema::hasColumn('cursos','estrutura')) {
                $table->json('estrutura')->nullable()->after('tags');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            // Remova em ordem inversa (e apenas se existir)
            foreach ([
                         'estrutura','tags','capa_path','publicado','max_alunos',
                         'carga_horaria','nivel','preco','descricao','resumo',
                         'slug','categoria_id'
                     ] as $col) {
                if (Schema::hasColumn('cursos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
