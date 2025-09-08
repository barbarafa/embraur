<?php

namespace App\Http\Controllers\Aluno;

use App\Http\Controllers\Controller;
use App\Models\{Quiz, QuizQuestao, QuizTentativa, QuizResposta, Matricula, Cursos};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlunoQuizController extends Controller
{
    public function show(Request $rq, Cursos $curso, Quiz $quiz)
    {
        $alunoId = auth('aluno')->id() ?? $rq->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $matricula = Matricula::where('aluno_id',$alunoId)->where('curso_id',$curso->id)->firstOrFail();
        $quiz->load('questoes.opcoes');
        return view('aluno.quiz', compact('curso','quiz','matricula'));
    }

    public function submit(Request $rq, Cursos $curso, Quiz $quiz)
    {
        $alunoId = auth('aluno')->id() ?? $rq->session()->get('aluno_id');
        abort_if(!$alunoId, 403);

        $matricula = Matricula::where('aluno_id',$alunoId)->where('curso_id',$curso->id)->firstOrFail();

        $payload = $rq->validate([
            'respostas' => 'required|array',
            'respostas.*.questao_id' => 'required|integer|exists:quiz_questoes,id',
            'respostas.*.opcao_id' => 'nullable|integer',
            'respostas.*.resposta_texto' => 'nullable|string',
        ]);

        $questoes = $quiz->questoes()->with('opcoes')->get()->keyBy('id');

        $nota = 0; $notaMax = 0;

        DB::transaction(function() use (&$nota, &$notaMax, $quiz, $matricula, $payload, $questoes, $alunoId){
            $tentativa = QuizTentativa::create([
                'quiz_id' => $quiz->id,
                'aluno_id' => $alunoId,
                'matricula_id' => $matricula->id,
            ]);

            foreach ($questoes as $qid => $q) {
                $notaMax += (float)$q->pontuacao;
                $resp = collect($payload['respostas'])->firstWhere('questao_id', $qid);
                $pont = 0;

                if ($q->tipo === 'multipla') {
                    $correta = $q->opcoes->firstWhere('correta', true);
                    $pont = ($correta && $resp && (int)($resp['opcao_id'] ?? 0) === $correta->id) ? (float)$q->pontuacao : 0;
                    QuizResposta::create([
                        'tentativa_id'=>$tentativa->id,'questao_id'=>$qid,
                        'opcao_id'=>$resp['opcao_id'] ?? null,'pontuacao_obtida'=>$pont
                    ]);
                } else {
                    // texto: deixa para correção manual (pontuação 0 por ora)
                    QuizResposta::create([
                        'tentativa_id'=>$tentativa->id,'questao_id'=>$qid,
                        'resposta_texto'=>$resp['resposta_texto'] ?? null,'pontuacao_obtida'=>0
                    ]);
                }
                $nota += $pont;
            }

            $aprovado = $notaMax > 0 && $nota >= (float)($quiz->curso->nota_minima_aprovacao ?? 0);
            $tentativa->update([
                'nota_obtida' => $nota,
                'nota_maxima' => $notaMax,
                'aprovado' => $aprovado,
                'concluido_em' => Carbon::now(),
            ]);

            // Se aprovado e escopo=modulo, podemos marcar módulo como liberado (sua regra)
            // Se escopo=curso, só o requisito global de nota mínima será atendido
        });

        return back()->with('sucesso', "Quiz enviado. Nota: {$nota} / {$notaMax}");
    }
}
