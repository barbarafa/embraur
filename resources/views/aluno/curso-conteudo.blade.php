{{-- resources/views/aluno/curso-conteudo.blade.php --}}
@extends('layouts.app')
@section('title', $curso->titulo)

@section('content')
    <div class="container mx-auto py-6">

        <div class="mb-3">
            <a href="{{ route('aluno.dashboard') }}" class="text-slate-500 hover:underline">
                &larr; Voltar ao Dashboard
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-4">{{ $curso->titulo }}</h1>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- COLUNA PRINCIPAL (PLAYER + CONTEÚDO) --}}
            <div class="lg:col-span-8 space-y-4">
                {{-- Player --}}
                <div class="rounded-lg border bg-black aspect-video flex items-center justify-center">
                    @if($aula->tipo === 'video' && $aula->conteudo_url)
                        <iframe class="w-full h-full rounded-lg"
                                src="{{ $aula->conteudo_url }}" allowfullscreen></iframe>
                    @else
                        <div class="text-white">Conteúdo da aula</div>
                    @endif
                </div>

                {{-- Título + Navegação --}}
                <div class="rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-semibold">{{ $aula->titulo }}</h2>
                            <div class="text-xs text-slate-500">{{ $aula->duracao_minutos }}min</div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($prevAula)
                                <a href="{{ route('aluno.curso.modulo.aula', [$curso->id, $modulo->id, $prevAula->id]) }}"
                                   class="px-3 py-2 border rounded hover:bg-slate-50">&larr; Anterior</a>
                            @else
                                <button class="px-3 py-2 border rounded opacity-50" disabled>&larr; Anterior</button>
                            @endif

                            @if($nextAula)
                                <a href="{{ route('aluno.curso.modulo.aula', [$curso->id, $modulo->id, $nextAula->id]) }}"
                                   class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">Próxima &rarr;</a>
                            @else
                                @if($modulo->quiz)
                                    <a href="{{ route('aluno.quiz.show', [$curso->id, $modulo->quiz->id]) }}"
                                       class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        Ir para a Prova do Módulo &rarr;
                                    </a>
                                @else
                                    <button class="px-3 py-2 bg-green-600 text-white rounded opacity-50" disabled>Fim do Módulo</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Material de Apoio --}}
                <div class="rounded-lg border p-4">
                    <h3 class="font-semibold mb-3">Material de Apoio</h3>
                    @php
                        $materiais = method_exists($aula,'materiais') ? $aula->materiais : collect();
                    @endphp

                    @if($materiais->count())
                        <ul class="space-y-2">
                            @foreach($materiais as $m)
                                <li class="flex items-center justify-between rounded border p-3">
                                    <span class="truncate">{{ $m->titulo ?? 'Arquivo' }}</span>
                                    <a href="{{ $m->arquivo_url ?? '#' }}" target="_blank"
                                       class="px-2 py-1 border rounded hover:bg-slate-50 text-sm">Baixar</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-slate-500 text-sm">Nenhum material cadastrado.</div>
                    @endif
                </div>
            </div>

            {{-- SIDEBAR (CONTEÚDO DO CURSO) --}}
            <div class="lg:col-span-4">
                <div class="rounded-lg border p-4">
                    <h3 class="font-semibold mb-3">Conteúdo do Curso</h3>

                    @foreach($curso->modulos->sortBy('ordem') as $idx => $m)
                        @php
                            $isAtual = (int)$m->id === (int)$modulo->id;
                            $quiz    = $m->quiz ?? null;
                            $tent    = $quiz ? ($ultimaTentativaPorQuiz[$quiz->id] ?? null) : null;

                            $statusQuiz = 'pend';
                            if ($tent) {
                                $statusQuiz = $tent->aprovado ? 'ok' : 'reprov';
                            }

                            // Trava visual do próximo módulo quando anterior não aprovado
                            $modLiberado = true;
                            if (class_exists(\App\Support\CursoGate::class)) {
                                $modLiberado = \App\Support\CursoGate::podeAcessarModulo($curso, $matricula, $idx);
                            }
                        @endphp

                        <div class="mb-4">
                            <div class="flex items-center justify-between">
                                <div class="font-medium">
                                    Módulo {{ $idx+1 }} — {{ $m->titulo }}
                                </div>
                                @if(!$modLiberado)
                                    <span class="text-[11px] text-amber-700">Bloqueado</span>
                                @elseif($isAtual)
                                    <span class="text-[11px] text-green-600">Atual</span>
                                @endif
                            </div>
                            @if($m->descricao)
                                <div class="text-xs text-slate-500">{{ $m->descricao }}</div>
                            @endif

                            <div class="mt-2">
                                @foreach($m->aulas->sortBy('ordem') as $a)
                                    <a href="{{ route('aluno.curso.modulo.aula', [$curso->id, $m->id, $a->id]) }}"
                                       class="flex items-center justify-between rounded border p-2 mb-1 {{ !$modLiberado ? 'opacity-60 pointer-events-none' : '' }} {{ (int)$a->id === (int)$aula->id ? 'bg-green-50 border-green-200' : 'hover:bg-slate-50' }}">
                                        <span class="truncate text-sm">{{ $a->titulo }}</span>
                                        <span class="text-xs text-slate-500">{{ $a->duracao_minutos }}min</span>
                                    </a>
                                @endforeach

                                {{-- Prova do módulo --}}
                                <div class="flex items-center justify-between mt-2">
                                    @if($quiz)
                                        <a href="{{ route('aluno.quiz.show', [$curso->id, $quiz->id]) }}"
                                           class="px-2 py-1 text-sm border rounded hover:bg-slate-50 {{ !$modLiberado ? 'opacity-60 pointer-events-none' : '' }}">
                                            Prova do Módulo
                                        </a>
                                        @switch($statusQuiz)
                                            @case('ok')
                                                <span class="text-[11px] px-2 py-1 rounded bg-green-100 text-green-700">OK</span>
                                                @break
                                            @case('reprov')
                                                <span class="text-[11px] px-2 py-1 rounded bg-red-100 text-red-700">Reprovado</span>
                                                @break
                                            @default
                                                <span class="text-[11px] px-2 py-1 rounded bg-slate-100 text-slate-600">Pend.</span>
                                        @endswitch
                                    @else
                                        <span class="text-xs text-slate-500">Prova do módulo não cadastrada</span>
                                    @endif
                                </div>

                                {{-- Cadeado / aviso para próximo módulo --}}
                                @if(!$modLiberado)
                                    <div class="mt-2 text-[11px] text-amber-700">
                                        Para acessar este módulo, conclua e seja aprovado na prova do módulo anterior.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
