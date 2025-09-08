<?php



namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\{Cursos, Modulos, Quiz, QuizQuestao, QuizOpcao};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index(Cursos $curso = null, Modulos $modulo = null)
    {
        $quizzes = Quiz::query()
            ->when($curso->id ?? null, fn($q)=>$q->where('curso_id',$curso->id))
            ->when($modulo->id ?? null, fn($q)=>$q->where('modulo_id',$modulo->id))
            ->latest()->get();

        return view('prof.quizzes.index', compact('curso','modulo','quizzes'));
    }

    public function create(Cursos $curso = null, Modulos $modulo = null)
    {
        return view('prof.quizzes.create', compact('curso','modulo'));
    }

    public function store(Request $rq, Cursos $curso = null, Modulos $modulo = null)
    {
        $data = $rq->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'escopo' => 'required|in:curso,modulo',
            'correcao_manual' => 'boolean',
            'questoes' => 'required|array|min:1',
            'questoes.*.enunciado' => 'required|string',
            'questoes.*.tipo' => 'required|in:texto,multipla',
            'questoes.*.pontuacao' => 'required|numeric|min:0.1',
            'questoes.*.opcoes' => 'array',
            'questoes.*.opcoes.*.texto' => 'required_with:questoes.*.opcoes|string',
            'questoes.*.opcoes.*.correta' => 'boolean',
        ]);

        DB::transaction(function() use ($data, $curso, $modulo){
            $quiz = Quiz::create([
                'curso_id' => $curso->id ?? null,
                'modulo_id'=> $modulo->id ?? null,
                'titulo' => $data['titulo'],
                'descricao' => $data['descricao'] ?? null,
                'escopo' => $data['escopo'],
                'correcao_manual' => (bool)($data['correcao_manual'] ?? false),
            ]);

            foreach ($data['questoes'] as $q) {
                $questao = QuizQuestao::create([
                    'quiz_id' => $quiz->id,
                    'enunciado' => $q['enunciado'],
                    'tipo' => $q['tipo'],
                    'pontuacao' => $q['pontuacao'],
                ]);
                if ($q['tipo'] === 'multipla' && !empty($q['opcoes'])) {
                    foreach ($q['opcoes'] as $op) {
                        QuizOpcao::create([
                            'questao_id' => $questao->id,
                            'texto' => $op['texto'],
                            'correta' => (bool)($op['correta'] ?? false),
                        ]);
                    }
                }
            }
        });

        return back()->with('sucesso','Quiz criado com sucesso!');
    }

    public function edit(Quiz $quiz){ $quiz->load('questoes.opcoes'); return view('prof.quizzes.edit', compact('quiz')); }

    public function update(Request $rq, Quiz $quiz)
    {
        $rq->validate(['titulo'=>'required|string|max:255']);
        DB::transaction(function() use ($rq, $quiz){
            $quiz->update($rq->only('titulo','descricao','correcao_manual'));
            // Para simplificar, substitui todas as questões
            $quiz->questoes()->delete();
            // Reaproveita a mesma estrutura do store
            request()->merge(['escopo' => $quiz->escopo]); // mantém escopo
            app(self::class)->store($rq, $quiz->curso ?? new Cursos, $quiz->modulo ?? new Modulos);
        });

        return redirect()->route('prof.cursos.quizzes.index', $quiz->curso_id)->with('sucesso','Quiz atualizado!');
    }

    public function destroy(Quiz $quiz){ $quiz->delete(); return back()->with('sucesso','Quiz removido.'); }
}

