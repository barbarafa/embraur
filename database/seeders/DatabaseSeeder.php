<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Aluno;

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
            [
                'categoria'=>'Segurança do Trabalho','titulo'=>'Segurança do Trabalho - NR10',
                'descricao'=>'Curso completo sobre segurança em instalações e serviços em eletricidade.',
                'carga_horaria'=>40,'preco'=>199.90,'preco_promocional'=>149.90,
                'nivel'=>'Básico','avaliacao'=>4.8,'alunos'=>2847,'popular'=>true,
                'slug'=>'nr10-seguranca-do-trabalho'
            ],
            [
                'categoria'=>'Segurança do Trabalho','titulo'=>'NR35 - Trabalho em Altura',
                'descricao'=>'Normas e práticas de segurança para trabalhos em altura.',
                'carga_horaria'=>16,'preco'=>159.90,'preco_promocional'=>129.90,
                'nivel'=>'Básico','avaliacao'=>4.9,'alunos'=>1923,'popular'=>true,
                'slug'=>'nr35-trabalho-em-altura'
            ],
            [
                'categoria'=>'Saúde','titulo'=>'Primeiros Socorros no Trabalho',
                'descricao'=>'Técnicas essenciais em ambiente corporativo.',
                'carga_horaria'=>12,'preco'=>129.90,'preco_promocional'=>89.90,
                'nivel'=>'Básico','avaliacao'=>4.7,'alunos'=>3456,'popular'=>true,
                'slug'=>'primeiros-socorros-no-trabalho'
            ],
            [
                'categoria'=>'Gestão','titulo'=>'Gestão de Projetos - PMI',
                'descricao'=>'Metodologias e boas práticas em gestão de projetos.',
                'carga_horaria'=>60,'preco'=>399.90,'preco_promocional'=>299.90,
                'nivel'=>'Intermediário','avaliacao'=>4.8,'alunos'=>1567,
                'slug'=>'gestao-de-projetos-pmi'
            ],
            [
                'categoria'=>'Meio Ambiente','titulo'=>'ISO 14001 - Sistema de Gestão Ambiental',
                'descricao'=>'Implementação e auditoria de sistemas de gestão ambiental.',
                'carga_horaria'=>32,'preco'=>259.90,'preco_promocional'=>199.90,
                'nivel'=>'Intermediário','avaliacao'=>4.6,'alunos'=>892,
                'slug'=>'iso-14001-sga'
            ],
            [
                'categoria'=>'Tecnologia','titulo'=>'Excel Avançado para Profissionais',
                'descricao'=>'Domine funcionalidades avançadas do Excel para gestão e análise.',
                'carga_horaria'=>24,'preco'=>219.90,'preco_promocional'=>159.90,
                'nivel'=>'Avançado','avaliacao'=>4.7,'alunos'=>2341,
                'slug'=>'excel-avancado-para-profissionais'
            ],
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
    }
}
