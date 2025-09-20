<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Categorias;
use App\Models\Cursos;
use App\Models\Modulos;
use App\Models\Aulas;

class SstSeed extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // 1) Categorias
            $categorias = [
                ['nome' => 'Normas Regulamentadoras (NRs)', 'descricao' => 'Cursos.php focados nas NRs aplicáveis à SST', 'icone' => 'ph:book', 'ordem_exibicao' => 1],
                ['nome' => 'Prevenção de Acidentes',         'descricao' => 'Técnicas e práticas para reduzir incidentes', 'icone' => 'ph:shield-check', 'ordem_exibicao' => 2],
                ['nome' => 'Brigada de Incêndio',            'descricao' => 'Formação e reciclagem de brigadistas', 'icone' => 'ph:fire', 'ordem_exibicao' => 3],
                ['nome' => 'Saúde Ocupacional',              'descricao' => 'Higiene, agentes nocivos e programas de saúde', 'icone' => 'ph:heartbeat', 'ordem_exibicao' => 4],
                ['nome' => 'Ergonomia',                      'descricao' => 'Prevenção de LER/DORT e qualidade de vida', 'icone' => 'ph:armchair', 'ordem_exibicao' => 5],
                ['nome' => 'EPI e EPC',                      'descricao' => 'Uso, seleção e manutenção de EPIs/EPCs', 'icone' => 'ph:hard-hat', 'ordem_exibicao' => 6],
                ['nome' => 'Gestão de Riscos',               'descricao' => 'Mapeamento, análise e controle de riscos', 'icone' => 'ph:warning', 'ordem_exibicao' => 7],
            ];

            $categoriasIds = [];
            foreach ($categorias as $cat) {
                $c = Categorias::firstOrCreate(
                    ['nome' => $cat['nome']],
                    $cat
                );
                $categoriasIds[$cat['nome']] = $c->id;
            }

            // 2) Cursos.php (com módulos e aulas)
            // Observação: ajuste professor_id conforme sua base.
            $professorIdDefault = 1;

            $cursos = [
                [
                    'categoria' => 'Normas Regulamentadoras (NRs)',
                    'titulo'    => 'NR-10 – Segurança em Instalações e Serviços em Eletricidade',
                    'descricao_curta'    => 'Proteção e prevenção em atividades com eletricidade',
                    'descricao_completa' => 'Aborda requisitos da NR-10, medidas de controle, documentação, análise de risco, EPC/EPI e primeiros socorros em acidentes elétricos.',
                    'nivel'              => 'intermediario',
                    'preco'              => 129.90,
                    'preco_original'     => 199.90,
                    'nota_minima_aprovacao' => 7.0,
                    'status'  => 'publicado',
                    'video_introducao'   => null,
                    'imagem_capa'        => null,
                    'modulos' => [
                        [
                            'titulo' => 'Fundamentos de Segurança Elétrica',
                            'descricao' => 'Conceitos, choques elétricos e legislação aplicável.',
                            'aulas' => [
                                ['titulo'=>'Introdução à NR-10','descricao'=>'Objetivos e campo de aplicação','tipo'=>'video','duracao_minutos'=>12,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>false],
                                ['titulo'=>'Riscos Elétricos','descricao'=>'Perigos, choques e arco elétrico','tipo'=>'video','duracao_minutos'=>18,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>true],
                                ['titulo'=>'Documentação e Responsabilidades','descricao'=>'Prontuários, procedimentos e responsabilidades','tipo'=>'texto','duracao_minutos'=>10,'conteudo_url'=>null,'conteudo_texto'=>'Conteúdo textual NR-10','liberada_apos_anterior'=>true],
                            ],
                        ],
                        [
                            'titulo' => 'Medidas de Controle e Primeiros Socorros',
                            'descricao' => 'Bloqueio, sinalização, EPIs e atendimento inicial.',
                            'aulas' => [
                                ['titulo'=>'Bloqueio e Etiquetagem (LOTO)','descricao'=>'Procedimentos LOTO','tipo'=>'video','duracao_minutos'=>16,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>false],
                                ['titulo'=>'EPIs/EPCs aplicados à NR-10','descricao'=>'Seleção e uso correto','tipo'=>'texto','duracao_minutos'=>8,'conteudo_texto'=>'Lista de EPIs e critérios de seleção','conteudo_url'=>null,'liberada_apos_anterior'=>true],
                                ['titulo'=>'Primeiros Socorros em Acidentes Elétricos','descricao'=>'Abordagem inicial e acionamento de emergência','tipo'=>'video','duracao_minutos'=>14,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>true],
                            ],
                        ],
                    ],
                ],
                [
                    'categoria' => 'Normas Regulamentadoras (NRs)',
                    'titulo'    => 'NR-35 – Trabalho em Altura',
                    'descricao_curta'    => 'Técnicas e cuidados para trabalhos acima de 2 metros',
                    'descricao_completa' => 'Planejamento, análise de risco, sistemas de ancoragem, linhas de vida, resgate e EPIs específicos.',
                    'nivel'              => 'intermediario',
                    'preco'              => 119.90,
                    'preco_original'     => 179.90,
                    'nota_minima_aprovacao' => 7.0,
                    'status'  => 'publicado',
                    'modulos' => [
                        [
                            'titulo'=>'Planejamento e Análise de Risco',
                            'descricao'=>'Requisitos e responsabilidades',
                            'aulas'=>[
                                ['titulo'=>'Critérios da NR-35','descricao'=>null,'tipo'=>'video','duracao_minutos'=>15,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>false],
                                ['titulo'=>'Análise Preliminar de Risco (APR)','descricao'=>null,'tipo'=>'texto','duracao_minutos'=>9,'conteudo_texto'=>'Modelo de APR e exemplos','conteudo_url'=>null,'liberada_apos_anterior'=>true],
                            ],
                        ],
                        [
                            'titulo'=>'Sistemas de Proteção e Resgate',
                            'descricao'=>'Ancoragem e procedimentos',
                            'aulas'=>[
                                ['titulo'=>'Ancoragem e Linhas de Vida','descricao'=>null,'tipo'=>'video','duracao_minutos'=>17,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>false],
                                ['titulo'=>'Procedimentos de Resgate','descricao'=>null,'tipo'=>'video','duracao_minutos'=>13,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>true],
                            ],
                        ],
                    ],
                ],
                [
                    'categoria' => 'Brigada de Incêndio',
                    'titulo'    => 'Formação de Brigadistas de Incêndio',
                    'descricao_curta'    => 'Combate a princípios de incêndio e evacuação segura',
                    'descricao_completa' => 'Teoria do fogo, classes de incêndio, uso de extintores, hidrantes e simulações de evacuação.',
                    'nivel'              => 'iniciante',
                    'preco'              => 99.90,
                    'preco_original'     => 149.90,
                    'nota_minima_aprovacao' => 7.0,
                    'status'  => 'publicado',
                    'modulos'=>[
                        [
                            'titulo'=>'Fundamentos do Fogo',
                            'descricao'=>'Química do fogo e classes',
                            'aulas'=>[
                                ['titulo'=>'Teoria do Fogo','descricao'=>null,'tipo'=>'video','duracao_minutos'=>14,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>false],
                                ['titulo'=>'Classes de Incêndio e Agentes','descricao'=>null,'tipo'=>'texto','duracao_minutos'=>10,'conteudo_texto'=>'Tabela de classes e agentes recomendados','conteudo_url'=>null,'liberada_apos_anterior'=>true],
                            ],
                        ],
                        [
                            'titulo'=>'Combate e Evacuação',
                            'descricao'=>'Extintores, hidrantes e rotas',
                            'aulas'=>[
                                ['titulo'=>'Uso Correto de Extintores','descricao'=>null,'tipo'=>'video','duracao_minutos'=>12,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>false],
                                ['titulo'=>'Evacuação e Ponto de Encontro','descricao'=>null,'tipo'=>'video','duracao_minutos'=>11,'conteudo_url'=>null,'conteudo_texto'=>null,'liberada_apos_anterior'=>true],
                            ],
                        ],
                    ],
                ],
            ];

            // 3) Inserção dos cursos com módulos e aulas
            foreach ($cursos as $ordemCurso => $cData) {
                $catId = $categoriasIds[$cData['categoria']] ?? null;
                if (!$catId) continue;


                $curso = Cursos::create([
                    'professor_id'          => $professorIdDefault,
                    'categoria_id'          => $catId,
                    'titulo'                => $cData['titulo'],
                    'descricao_curta'       => $cData['descricao_curta'],
                    'descricao_completa'    => $cData['descricao_completa'],
                    'imagem_capa'           => $cData['imagem_capa'] ?? null,
                    'video_introducao'      => $cData['video_introducao'] ?? null,
                    'nivel'                 => $cData['nivel'],
                    'carga_horaria_total'   => 0, // atualiza depois
                    'preco'                 => $cData['preco'],
                    'preco_original'        => $cData['preco_original'],
                    'nota_minima_aprovacao' => $cData['nota_minima_aprovacao'],
                    'status'     => $cData['status']
                ]);

                $totalMin = 0;

                foreach ($cData['modulos'] as $ordemModulo => $mData) {
                    $modulo = Modulos::create([
                        'curso_id'  => $curso->id,
                        'titulo'    => $mData['titulo'],
                        'descricao' => $mData['descricao'] ?? null,
                        'ordem'     => $ordemModulo + 1,
                    ]);

                    foreach ($mData['aulas'] as $ordemAula => $aData) {
                        $dur = (int)($aData['duracao_minutos'] ?? 0);
                        $totalMin += $dur;

                        Aulas::create([
                            'modulo_id'              => $modulo->id,
                            'titulo'                 => $aData['titulo'],
                            'descricao'              => $aData['descricao'] ?? null,
                            'tipo'                   => $aData['tipo'] ?? 'video',
                            'duracao_minutos'        => $dur,
                            'conteudo_url'           => $aData['conteudo_url'] ?? null,
                            'conteudo_texto'         => $aData['conteudo_texto'] ?? null,
                            'ordem'                  => $ordemAula + 1,
                            'liberada_apos_anterior' => (bool)($aData['liberada_apos_anterior'] ?? false),
                        ]);
                    }
                }

                // Atualiza a carga horária total do curso (em minutos)
                $curso->update(['carga_horaria_total' => $totalMin]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
