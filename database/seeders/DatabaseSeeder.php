<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Aluno;
use App\Models\Professor;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categorias
        $cats = [
            ['nome'=>'Segurança do Trabalho','slug'=>'seguranca-do-trabalho'],
            ['nome'=>'Saúde','slug'=>'saude'],
            ['nome'=>'Meio Ambiente','slug'=>'meio-ambiente'],
            ['nome'=>'Gestão','slug'=>'gestao'],
            ['nome'=>'Tecnologia','slug'=>'tecnologia'],
            ['nome'=>'Qualidade','slug'=>'qualidade'],
        ];
        foreach ($cats as $c) Categoria::firstOrCreate(['slug'=>$c['slug']], $c);

        // Cursos (os 6 da tela)
        $cursos = [
            // ... seus cursos aqui ...
        ];
        foreach ($cursos as $c) {
            $cat = Categoria::where('nome',$c['categoria'])->first();
            $data = $c;
            $data['categoria_id'] = $cat->id;
            unset($data['categoria']);
            Curso::firstOrCreate(['slug'=>$data['slug']], $data);
        }

        // Aluno demo
        Aluno::firstOrCreate(
            ['email'=>'demo@eadpro.com'],
            ['nome'=>'Aluno Demo','password'=>Hash::make('senha123')]
        );

        // Professor demo
        Professor::firstOrCreate(
            ['email'=>'prof@eadpro.com'],
            ['nome'=>'Professor Demo','password'=>Hash::make('senha123')]
        );
    }
}
