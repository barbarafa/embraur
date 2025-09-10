{{-- resources/views/aluno/quiz.blade.php --}}
@extends('layouts.app')
@section('title', $quiz->titulo)

@section('content')
    <style>
        /* Garante 1 pergunta por vez */
        .quiz-question { display: none; }
        .quiz-question.active { display: block; }
    </style>

    <div class="container mx-auto py-6">

        {{-- HEADER (estilo referência) --}}
        <div class="rounded-lg border p-4 mb-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-lg font-semibold">{{ $quiz->titulo }}</h1>

                    {{-- Subtítulo (usa a descrição do quiz se existir, senão mostra o nome do curso) --}}
                    <p class="text-sm text-slate-600">
                        {{ $quiz->descricao ?? ('Avaliação dos conceitos de ' . ($curso->titulo ?? '')) }}
                    </p>

                    <div class="mt-2 flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-700 px-3 py-1 text-xs font-medium">
                          Nota mínima: {{ number_format((float)($curso->nota_minima_aprovacao ?? 7),1,',','.') }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-700 px-3 py-1 text-xs font-medium">
                          <span id="respondidasLabel">0 de {{ $quiz->questoes->count() }} respondidas</span>
                        </span>
                    </div>
                </div>

                <a href="{{ route('aluno.curso.conteudo', $curso->id) }}"
                   class="text-sm text-slate-600 hover:underline flex items-center gap-2 shrink-0">
                    <span class="-ml-1">←</span> Voltar às aulas
                </a>
            </div>

            {{-- Linha título do progresso + percentual --}}
            <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                <span>Progresso do quiz</span>
                <span id="pctLabel">0%</span>
            </div>

            {{-- Barra de progresso --}}
            <div class="w-full h-2 rounded-full bg-slate-200 mt-2 overflow-hidden">
                <div id="barraProgresso" class="h-full bg-blue-600 transition-all" style="width:0%"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('aluno.quiz.submit', [$curso->id, $quiz->id]) }}" id="formQuiz">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- COLUNA ÚNICA (sem sidebar de curso) --}}
                <div class="lg:col-span-12">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                        {{-- Navegação (pílulas) --}}
                        <div class="md:col-span-3">
                            <div class="rounded-lg border p-4">
                                <div class="font-semibold mb-3">Navegação</div>
                                <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-4 gap-2">
                                    @foreach($quiz->questoes as $i => $q)
                                        <button type="button"
                                                class="pill w-9 h-9 rounded border text-sm flex items-center justify-center hover:bg-slate-50"
                                                data-go="{{ $i }}">
                                            {{ $i+1 }}
                                        </button>
                                    @endforeach
                                </div>

                                <div class="mt-4 space-y-1 text-xs text-slate-500">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-slate-300 inline-block"></span> Não respondida
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Respondida
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span> Atual
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- QUESTÃO (uma por vez) --}}
                        <div class="md:col-span-9">
                            @foreach($quiz->questoes as $k => $q)
                                <div class="quiz-question rounded-lg border p-4 mb-4 {{ $k === 0 ? 'active' : '' }}"
                                     data-index="{{ $k }}">
                                    <div class="flex items-center justify-between">
                                        <div class="font-semibold">Questão {{ $k+1 }} de {{ $quiz->questoes->count() }}</div>
                                        <div class="text-xs text-slate-500">
                                            {{ rtrim(rtrim(number_format((float)$q->pontuacao,1,',','.'),'0'),',') }} pontos
                                        </div>
                                    </div>

                                    <p class="mt-2">{{ $q->enunciado }}</p>
                                    <input type="hidden" name="respostas[{{ $k }}][questao_id]" value="{{ $q->id }}"/>

                                    @if($q->tipo === 'multipla')
                                        <div class="mt-3 space-y-2">
                                            @foreach($q->opcoes as $op)
                                                <label class="flex items-center gap-2 rounded border p-3 hover:bg-slate-50 cursor-pointer">
                                                    <input type="radio"
                                                           name="respostas[{{ $k }}][opcao_id]"
                                                           value="{{ $op->id }}"
                                                           class="answer-radio mt-0.5"
                                                           data-q="{{ $k }}">
                                                    <span>{{ $op->texto }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <textarea name="respostas[{{ $k }}][resposta_texto]"
                                                  class="answer-text w-full mt-3 rounded border p-3"
                                                  rows="4" data-q="{{ $k }}"
                                                  placeholder="Escreva sua resposta..."></textarea>
                                    @endif
                                </div>
                            @endforeach

                            {{-- Rodapé de navegação --}}
                            <div class="rounded-lg border p-4 mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button type="button" id="btnPrev"
                                            class="px-3 py-2 border rounded hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        &larr; Anterior
                                    </button>

                                    <button type="button" id="btnNext"
                                            class="px-3 py-2 border rounded hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Próxima &rarr;
                                    </button>
                                </div>

                                <div class="text-sm text-slate-500">
                                    Questão <span id="lblAtual">1</span> de <span id="lblTotal">{{ $quiz->questoes->count() }}</span>
                                </div>

                                <button type="submit" id="btnSubmit"
                                        class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                        style="display:none">
                                    Enviar Prova
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- JS puro --}}
    <script>
        (function(){
            const total = {{ $quiz->questoes->count() }};
            let page = 0;
            const answered = Array(total).fill(false);

            const questions = Array.from(document.querySelectorAll('.quiz-question'));
            const pills = Array.from(document.querySelectorAll('.pill'));
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const btnSubmit = document.getElementById('btnSubmit');
            const lblAtual = document.getElementById('lblAtual');
            const barra = document.getElementById('barraProgresso');
            const lblResp = document.getElementById('respondidasLabel');
            const lblPct  = document.getElementById('pctLabel'); // <— percentual

            function setPillState(){
                pills.forEach((p,i)=>{
                    p.classList.remove('bg-blue-600','text-white','border-blue-600','bg-green-500','border-green-500');
                    if(i === page){
                        p.classList.add('bg-blue-600','text-white','border-blue-600');
                    } else if(answered[i]){
                        p.classList.add('bg-green-500','text-white','border-green-500');
                    }
                });
            }

            function showPage(i){
                questions.forEach((el, idx)=>{
                    if(idx === i){ el.classList.add('active'); }
                    else { el.classList.remove('active'); }
                });
                page = i;
                lblAtual.textContent = (page+1);
                setPillState();
                updateButtons();
                questions[i].scrollIntoView({behavior:'smooth', block:'start'});
            }

            function updateButtons(){
                btnPrev.disabled = (page===0);
                if(page < total-1){
                    btnNext.style.display = '';
                    btnNext.disabled = !answered[page];
                    btnSubmit.style.display = 'none';
                } else {
                    btnNext.style.display = 'none';
                    btnSubmit.style.display = '';
                    btnSubmit.disabled = !answered.every(Boolean);
                }
            }

            function updateProgress(){
                const count = answered.filter(Boolean).length;
                const pct = Math.round((count/total)*100);
                barra.style.width = pct+'%';
                lblPct.textContent = pct+'%';        // <— atualiza “0%”
                lblResp.textContent = `${count} de ${total} respondidas`;
                setPillState();
                updateButtons();
            }

            // marcações
            document.querySelectorAll('.answer-radio').forEach(r=>{
                r.addEventListener('change', e=>{
                    const k = +e.target.dataset.q;
                    if(!answered[k]) { answered[k] = true; updateProgress(); }
                });
            });

            document.querySelectorAll('.answer-text').forEach(t=>{
                t.addEventListener('input', e=>{
                    const k = +e.target.dataset.q;
                    const has = e.target.value.trim().length>0;
                    if(has !== answered[k]) { answered[k] = has; updateProgress(); }
                });
            });

            // navegação
            pills.forEach((p,i)=> p.addEventListener('click', ()=> showPage(i)));
            btnPrev.addEventListener('click', ()=> { if(page>0) showPage(page-1); });
            btnNext.addEventListener('click', ()=> { if(answered[page] && page<total-1) showPage(page+1); });

            // init
            setPillState();
            updateButtons();
            updateProgress(); // inicia mostrando 0%
        })();
    </script>
@endsection
