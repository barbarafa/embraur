<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;

use App\Models\Aulas;
use App\Models\Categorias;
use App\Models\Cursos;
use App\Models\Modulos;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CursoAdminController extends Controller
{
    public function index(Request $request)
    {
        $profId = session('prof_id');
        $cursos = Cursos::with('categoria')
            ->where('professor_id', $profId)
            ->orderByDesc('id')
            ->paginate(12);



        return view('prof.cursos.index', compact('cursos'));
    }

    public function create()
    {
        $curso = new Cursos();
        $categorias = Categorias::orderBy('nome')->get();


        return view('prof.cursos.create', compact('curso', 'categorias'));
    }

    public function store(Request $request)
    {
        $profId = session('prof_id');

        $data = $request->validate([
            'categoria_id'          => ['required','exists:categorias,id'],
            'titulo'                => ['required','string','max:255'],
            'descricao_curta'       => ['nullable','string','max:255'],
            'descricao_completa'    => ['nullable','string'],
            'nivel'                 => ['required', Rule::in(['iniciante','intermediario','avancado'])],
            'carga_horaria_horas'   => ['nullable','numeric','min:0'], // campo da tela (horas)
            'maximo_alunos'         => ['nullable','integer','min:1'],
            'preco'                 => ['nullable','numeric','min:0'],
            'preco_original'        => ['nullable','numeric','min:0'],
            'nota_minima_aprovacao' => ['nullable','numeric','min:0','max:10'],
            'validade_dias'         => ['nullable','integer','min:1'],
            'status'                => ['nullable', Rule::in(['rascunho','publicado','arquivado'])],
            'imagem_capa'           => ['nullable','image','max:4096'],
            // estrutura
            'modulos'                              => ['nullable','array'],
            'modulos.*.titulo'                     => ['required_with:modulos.*','string','max:255'],
            'modulos.*.descricao'                  => ['nullable','string'],
            'modulos.*.aulas'                      => ['nullable','array'],
            'modulos.*.aulas.*.titulo'             => ['required_with:modulos.*.aulas.*','string','max:255'],
            'modulos.*.aulas.*.duracao_minutos'    => ['nullable','integer','min:0'],
            'modulos.*.aulas.*.tipo'               => ['required_with:modulos.*.aulas.*', Rule::in(['video','texto','quiz','arquivo'])],
            'modulos.*.aulas.*.conteudo_url'       => ['nullable','string','max:255'],
            'modulos.*.aulas.*.descricao'     => ['nullable','string'],
            'modulos.*.aulas.*.liberada_apos_anterior' => ['nullable','boolean'],
        ]);

        $dataCurso = collect($data)->only([
            'categoria_id','titulo','descricao_curta','descricao_completa',
            'nivel','maximo_alunos','preco','preco_original','nota_minima_aprovacao',
            'validade_dias','status'
        ])->toArray();

        $dataCurso['professor_id'] = $profId ?? null;
        // Horas (UI) → minutos (DB)
        $horas = (float)($data['carga_horaria_horas'] ?? 0);
        $dataCurso['carga_horaria_total'] = (int) round($horas * 60);

        // status pelo botão (opcional)
        $salvar = $request->input('salvar'); // 'rascunho' | 'publicar'
        if ($salvar === 'publicar') $dataCurso['status'] = 'publicado';
        if (empty($dataCurso['status'])) $dataCurso['status'] = 'rascunho';

        // capa
        if ($request->hasFile('imagem_capa')) {
            $dataCurso['imagem_capa'] = $request->file('imagem_capa')->store('cursos/capas', 'public');
        }

        DB::transaction(function () use (&$curso, $dataCurso, $data) {
            $curso = Cursos::create($dataCurso);

            // módulos + aulas
            $ordemModulo = 1;
            foreach (($data['modulos'] ?? []) as $m) {
                if (empty($m['titulo'])) continue;

                $modulo = Modulos::create([
                    'curso_id'  => $curso->id,
                    'titulo'    => $m['titulo'],
                    'descricao' => $m['descricao'] ?? null,
                    'ordem'     => $ordemModulo++,
                ]);

                $ordemAula = 1;
                foreach (($m['aulas'] ?? []) as $a) {
                    if (empty($a['titulo'])) continue;

                    Aulas::create([
                        'modulo_id'              => $modulo->id,
                        'titulo'                 => $a['titulo'],
                        'descricao'              => $a['descricao'] ?? null,
                        'tipo'                   => $a['tipo'] ?? 'video',
                        'duracao_minutos'        => (int)($a['duracao_minutos'] ?? 0),
                        'conteudo_url'           => $a['conteudo_url'] ?? null,
                        'conteudo_texto'         => $a['conteudo_texto'] ?? null,
                        'ordem'                  => $ordemAula++,
                        'liberada_apos_anterior' => (bool)($a['liberada_apos_anterior'] ?? false),
                    ]);
                }
            }




            // se a carga horária não foi preenchida, calculamos pela soma das aulas
            if (empty($dataCurso['carga_horaria_total'])) {
                $min = $curso->modulos()->withSum('aulas', 'duracao_minutos')->get()
                    ->sum('aulas_sum_duracao_minutos');
                $curso->update(['carga_horaria_total' => (int)$min]);
            }
        });



        return redirect()->route('prof.cursos.edit', $curso)->with('success','Curso criado com sucesso!');

    }

    public function edit(Cursos $curso)
    {
        $this->authorizeCurso($curso);
        $categorias = Categorias::orderBy('ordem_exibicao')->orderBy('nome')->get();
        $curso->load([
            'categoria',
            'modulos' => fn($q) => $q->orderBy('ordem'),
            'modulos.aulas' => fn($q) => $q->orderBy('ordem'),
        ]);

        $quizzesDoCurso = Quiz::where('curso_id', $curso->id)->get(['id','titulo']);

        return view('prof.cursos.edit', compact('curso','categorias','quizzesDoCurso'));


    }

    public function update(Request $request, Cursos $curso)
    {
        $this->authorizeCurso($curso);

        $data = $request->validate([
            'categoria_id'          => ['required','exists:categorias,id'],
            'titulo'                => ['required','string','max:255'],
            'descricao_curta'       => ['nullable','string','max:255'],
            'descricao_completa'    => ['nullable','string'],
            'nivel'                 => ['required', Rule::in(['iniciante','intermediario','avancado'])],
            'preco'                 => ['nullable','numeric','min:0'],
            'preco_original'        => ['nullable','numeric','min:0'],
            'nota_minima_aprovacao' => ['nullable','numeric','min:0','max:10'],
            'validade_dias'         => ['nullable','integer','min:1'],
            'status'                => ['nullable', Rule::in(['rascunho','publicado','arquivado'])],
            'imagem_capa'           => ['nullable','image','max:4096'],

            // estrutura (update)
            'modulos'                              => ['nullable','array'],
            'modulos.*.id'                         => ['nullable','exists:modulos,id'],
            'modulos.*.titulo'                     => ['required_with:modulos.*','string','max:255'],
            'modulos.*.descricao'                  => ['nullable','string'],
            'modulos.*.aulas'                      => ['nullable','array'],
            'modulos.*.aulas.*.id'                 => ['nullable','exists:aulas,id'],
            'modulos.*.aulas.*.titulo'             => ['required_with:modulos.*.aulas.*','string','max:255'],
            'modulos.*.aulas.*.duracao_minutos'    => ['nullable','integer','min:0'],
            'modulos.*.aulas.*.tipo'               => ['required_with:modulos.*.aulas.*', Rule::in(['video','texto','quiz','arquivo'])],
            'modulos.*.aulas.*.conteudo_url'       => ['nullable','string','max:255'],
            'modulos.*.aulas.*.descricao'     => ['nullable','string'],
            'modulos.*.aulas.*.liberada_apos_anterior' => ['nullable','boolean'],
            'modulos.*.aulas.*.quiz_id'            => ['nullable','exists:quizzes,id'],
        ]);

        // payload curso
        $payload = collect($data)->except(['imagem_capa','modulos'])->toArray();

        // horas (se vierem como input futuro)
        if (isset($payload['carga_horaria_horas'])) {
            $payload['carga_horaria_total'] = (int) round(((float)$payload['carga_horaria_horas']) * 60);
            unset($payload['carga_horaria_horas']);
        }

        $salvar = $request->input('salvar'); // 'rascunho' | 'publicar'
        $payload['status'] =  $salvar === 'publicar' ? 'publicado' : 'rascunho';

        // troca capa (se enviada)
        if ($request->hasFile('imagem_capa')) {
            if ($curso->imagem_capa && Storage::disk('public')->exists($curso->imagem_capa)) {
                Storage::disk('public')->delete($curso->imagem_capa);
            }
            $payload['imagem_capa'] = $request->file('imagem_capa')->store('cursos/capas', 'public');
        }

        DB::transaction(function () use ($curso, $payload, $data) {

            // 1) Atualiza o curso
            $curso->update($payload);

            // 2) Sincroniza módulos e aulas (se veio 'modulos'; senão, não altera estrutura)
            if (array_key_exists('modulos', $data)) {

                // carrega estrutura atual para diffs
                $curso->load(['modulos.aulas']);
                $modulosAtuais   = $curso->modulos; // Collection
                $idsModulosAtuais = $modulosAtuais->pluck('id')->all();

                $idsModulosRecebidos = [];
                $ordemModulo = 1;

                foreach (($data['modulos'] ?? []) as $m) {
                    $modId = $m['id'] ?? null;

                    if ($modId) {
                        // UPDATE de módulo existente (garante que pertence ao curso)
                        $modulo = $modulosAtuais->firstWhere('id', $modId);
                        if (!$modulo) {
                            // segurança extra: evita mexer em módulo de outro curso
                            continue;
                        }
                        $modulo->update([
                            'titulo'    => $m['titulo'],
                            'descricao' => $m['descricao'] ?? null,
                            'ordem'     => $ordemModulo++,
                        ]);
                    } else {
                        // CREATE novo módulo
                        $modulo = Modulos::create([
                            'curso_id'  => $curso->id,
                            'titulo'    => $m['titulo'],
                            'descricao' => $m['descricao'] ?? null,
                            'ordem'     => $ordemModulo++,
                        ]);
                    }

                    $idsModulosRecebidos[] = $modulo->id;

                    // === AULAS ===
                    $aulasAtuais = $modulo->aulas; // Collection
                    $idsAulasAtuais = $aulasAtuais->pluck('id')->all();

                    $idsAulasRecebidas = [];
                    $ordemAula = 1;

                    foreach (($m['aulas'] ?? []) as $a) {
                        $aulaId = $a['id'] ?? null;

                        $payloadAula = [
                            'titulo'                 => $a['titulo'],
                            'descricao'              => $a['descricao'] ?? null,
                            'tipo'                   => $a['tipo'] ?? 'video',
                            'duracao_minutos'        => (int)($a['duracao_minutos'] ?? 0),
                            'conteudo_url'           => $a['conteudo_url'] ?? null,
                            'conteudo_texto'         => $a['conteudo_texto'] ?? null,
                            'quiz_id'                => $a['quiz_id'] ?? null,
                            'ordem'                  => $ordemAula++,
                            'liberada_apos_anterior' => (bool)($a['liberada_apos_anterior'] ?? false),
                        ];

                        if ($aulaId) {
                            // UPDATE aula (garante que pertence ao módulo)
                            $aula = $aulasAtuais->firstWhere('id', $aulaId);
                            if (!$aula) {
                                continue;
                            }
                            $aula->update($payloadAula);
                        } else {
                            // CREATE aula
                            $aula = Aulas::create(array_merge($payloadAula, [
                                'modulo_id' => $modulo->id,
                            ]));
                        }

                        $idsAulasRecebidas[] = $aula->id;
                    }

                    // DELETE aulas que não vieram mais no payload
                    $idsAulasParaExcluir = array_diff($idsAulasAtuais, $idsAulasRecebidas);
                    if (!empty($idsAulasParaExcluir)) {
                        Aulas::whereIn('id', $idsAulasParaExcluir)->delete();
                    }
                }

                // DELETE módulos que não vieram mais
                $idsModulosParaExcluir = array_diff($idsModulosAtuais, $idsModulosRecebidos);
                if (!empty($idsModulosParaExcluir)) {
                    // cascade deve remover aulas, se FK estiver com onDelete(cascade)
                    Modulos::whereIn('id', $idsModulosParaExcluir)->delete();
                }

                // (opcional) recalcular carga horária se necessário
                if (empty($payload['carga_horaria_total'])) {
                    $min = $curso->modulos()->withSum('aulas', 'duracao_minutos')->get()
                        ->sum('aulas_sum_duracao_minutos');
                    $curso->update(['carga_horaria_total' => (int)$min]);
                }
            }
        });

        return back()->with('success','Curso atualizado com sucesso!');
    }


    public function destroy(Cursos $curso)
    {

        $this->authorizeCurso($curso);

        if ($curso->imagem_capa && Storage::disk('public')->exists($curso->imagem_capa)) {
            Storage::disk('public')->delete($curso->imagem_capa);
        }
        $curso->delete();
        return redirect()->route('prof.cursos.index')->with('success', 'Curso removido.');
    }

    private function authorizeCurso(Cursos $curso)
    {
        if ($curso->professor_id != session('prof_id')) {
            abort(403, 'Sem permissão para esse curso.');
        }
    }
}
