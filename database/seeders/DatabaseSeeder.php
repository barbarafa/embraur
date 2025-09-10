<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Categorias;
use App\Models\Cursos;
use App\Models\Modulos;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Duvida;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SstSeed::class,
            QuizSeeder::class,

        ]);
    }
}
