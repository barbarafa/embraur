<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Aula;
use App\Models\Aluno;
use App\Models\Professor;
use App\Models\Duvida;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Categorias
        |--------------------------------------------------------------------------
        */
        $cats = [
            ['nome'=>'Segurança do Trabalho','slug'=>'seguranca-do-trabalho'],
            ['nome'=>'Saúde','slug'=>'saude'],
            ['nome'=>'Meio Ambiente','slug'=>'meio-ambiente'],
            ['nome'=>'Gestão','slug'=>'gestao'],
            ['nome'=>'Tecnologia','slug'=>'tecnologia'],
            ['nome'=>'Qualidade','slug'=>'qualidade'],
        ];
        foreach ($cats as $c) {
            Categoria::firstOrCreate(['slug'=>$c['slug']], $c);
        }

        /*
        |--------------------------------------------------------------------------
        | Usuários Demo (Professor e Alunos)
        |--------------------------------------------------------------------------
        */
        $prof = Professor::firstOrCreate(
            ['email' => 'prof@eadpro.com'],
            ['nome' => 'Professor Demo', 'password' => Hash::make('senha123')]
        );

        $aluno1 = Aluno::firstOrCreate(
            ['email'=>'joao@eadpro.com'],
            ['nome'=>'João Silva','password'=>Hash::make('senha123')]
        );
        $aluno2 = Aluno::firstOrCreate(
            ['email'=>'maria@eadpro.com'],
            ['nome'=>'Maria Santos','password'=>Hash::make('senha123')]
        );
        $aluno3 = Aluno::firstOrCreate(
            ['email'=>'pedro@eadpro.com'],
            ['nome'=>'Pedro Oliveira','password'=>Hash::make('senha123')]
        );

        /*
        |--------------------------------------------------------------------------
        | Cursos (6 itens, como na UI)
        |--------------------------------------------------------------------------
        */
        $cursosSeed = [
            [
                'titulo'        => 'Segurança do Trabalho - NR10',
                'categoria'     => 'Segurança do Trabalho',
                'resumo'        => 'Entenda os riscos elétricos e procedimentos de segurança.',
                'descricao'     => 'Conteúdo completo sobre riscos, EPI, EPC, procedimentos e responsabilidades.',
                'preco'         => 199.90,
                'nivel'         => 'Básico',
                'carga_horaria' => 20,
                'max_alunos'    => 200,
                'publicado'     => true,
            ],
            [
                'titulo'        => 'NR35 - Trabalho em Altura',
                'categoria'     => 'Segurança do Trabalho',
                'resumo'        => 'Técnicas, regulamentação e procedimentos para trabalho em altura.',
                'descricao'     => 'Normas, verificação de equipamentos, ancoragem, planos de resgate.',
                'preco'         => 179.90,
                'nivel'         => 'Básico',
                'carga_horaria' => 16,
                'max_alunos'    => 150,
                'publicado'     => true,
            ],
            [
                'titulo'        => 'Primeiros Socorros no Trabalho',
                'categoria'     => 'Saúde',
                'resumo'        => 'Atendimentos iniciais em acidentes até a chegada do suporte profissional.',
                'descricao'     => 'Avaliação primária, RCP, controle de hemorragias e imobilizações.',
                'preco'         => 149.90,
                'nivel'         => 'Intermediário',
                'carga_horaria' => 12,
                'max_alunos'    => 120,
                'publicado'     => true,
            ],
            [
                'titulo'        => 'Gestão de Riscos Ocupacionais',
                'categoria'     => 'Gestão',
                'resumo'        => 'Identificação, análise e mitigação de riscos no ambiente de trabalho.',
                'descricao'     => 'Metodologias de avaliação, planos de ação, auditorias internas.',
                'preco'         => 249.90,
                'nivel'         => 'Intermediário',
                'carga_horaria' => 24,
                'max_alunos'    => 100,
                'publicado'     => true,
            ],
            [
                'titulo'        => 'Introdução à Segurança da Informação',
                'categoria'     => 'Tecnologia',
                'resumo'        => 'Fundamentos de segurança, boas práticas e normas.',
                'descricao'     => 'Criptografia, gestão de acessos, políticas e resposta a incidentes.',
                'preco'         => 199.90,
                'nivel'         => 'Básico',
                'carga_horaria' => 18,
                'max_alunos'    => 180,
                'publicado'     => true,
            ],
            [
                'titulo'        => 'Ferramentas da Qualidade',
                'categoria'     => 'Qualidade',
                'resumo'        => 'As principais ferramentas para melhoria contínua.',
                'descricao'     => 'PDCA, Ishikawa, Pareto, 5W2H, Histograma e folhas de verificação.',
                'preco'         => 159.90,
                'nivel'         => 'Básico',
                'carga_horaria' => 10,
                'max_alunos'    => 140,
                'publicado'     => true,
            ],
        ];

        $cursosCriados = [];
        foreach ($cursosSeed as $c) {
            $cat = Categoria::where('nome', $c['categoria'])->first();
            if (!$cat) continue;

            $slugBase = Str::slug($c['titulo']);
            $slug = $slugBase;
            $i = 0;
            while (Curso::where('slug', $slug)->exists()) {
                $i++;
                $slug = $slugBase.'-'.Str::lower(Str::random(5));
                if ($i > 10) break;
            }

            $curso = Curso::firstOrCreate(
                ['slug' => $slug],
                [
                    'professor_id'  => $prof->id,
                    'categoria_id'  => $cat->id,
                    'titulo'        => $c['titulo'],
                    'resumo'        => $c['resumo'],
                    'descricao'     => $c['descricao'],
                    'preco'         => $c['preco'],
                    'nivel'         => $c['nivel'],
                    'carga_horaria' => $c['carga_horaria'],
                    'max_alunos'    => $c['max_alunos'],
                    'publicado'     => $c['publicado'],
                    'tags'          => ['segurança','ead'],
                ]
            );

            $cursosCriados[] = $curso;

            // módulos + aulas demo (2 x 2)
            if ($curso->modulos()->count() === 0) {
                $m1 = Modulo::create([
                    'curso_id'  => $curso->id,
                    'titulo'    => 'Introdução',
                    'descricao' => 'Conceitos iniciais e objetivos do curso.',
                    'ordem'     => 1,
                ]);
                $m2 = Modulo::create([
                    'curso_id'  => $curso->id,
                    'titulo'    => 'Conteúdo Essencial',
                    'descricao' => 'Tópicos principais e estudo de caso.',
                    'ordem'     => 2,
                ]);

                Aula::create([
                    'modulo_id'   => $m1->id,
                    'titulo'      => 'Boas-vindas',
                    'tipo'        => 'video',
                    'duracao_min' => 8,
                    'ordem'       => 1,
                ]);
                Aula::create([
                    'modulo_id'   => $m1->id,
                    'titulo'      => 'Visão Geral',
                    'tipo'        => 'texto',
                    'conteudo'    => '<p>Objetivos e como aproveitar o curso.</p>',
                    'duracao_min' => 5,
                    'ordem'       => 2,
                ]);
                Aula::create([
                    'modulo_id'   => $m2->id,
                    'titulo'      => 'Procedimentos',
                    'tipo'        => 'video',
                    'duracao_min' => 12,
                    'ordem'       => 1,
                ]);
                Aula::create([
                    'modulo_id'   => $m2->id,
                    'titulo'      => 'Checklist e Boas Práticas',
                    'tipo'        => 'pdf',
                    'duracao_min' => 10,
                    'ordem'       => 2,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Dúvidas demo (para aparecer no painel)
        |--------------------------------------------------------------------------
        */
        if (!empty($cursosCriados)) {
            $cursoNR10 = collect($cursosCriados)->firstWhere('titulo', 'Segurança do Trabalho - NR10') ?? $cursosCriados[0];

            Duvida::firstOrCreate(
                [
                    'curso_id'     => $cursoNR10->id,
                    'aluno_id'     => $aluno1->id,
                    'professor_id' => $prof->id,
                    'texto'        => 'Qual a diferença entre aterramento funcional e de proteção?'
                ],
                ['assunto' => 'NR10', 'lida' => false]
            );

            Duvida::firstOrCreate(
                [
                    'curso_id'     => $cursoNR10->id,
                    'aluno_id'     => $aluno2->id,
                    'professor_id' => $prof->id,
                    'texto'        => 'Como calcular a força de retenção de um EPI?'
                ],
                ['assunto' => 'NR35', 'lida' => false]
            );
        }
    }
}
