@extends('layouts.app')
@section('title', $curso->titulo)

@section('content')
    <div class="container py-3">

        {{-- Breadcrumb/Voltar --}}
        <div class="mb-2">
            <a href="{{ route('aluno.curso.conteudo', $curso->id) }}" class="text-muted">&larr; Voltar ao Dashboard do Curso</a>
        </div>

        <div class="row">
            {{-- COLUNA PRINCIPAL (PLAYER + CONTEÚDO) --}}
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        {{-- Player de vídeo ou conteúdo textual --}}
                        @if($aula->tipo === 'video' && $aula->conteudo_url)
                            <div class="ratio ratio-16x9 bg-dark rounded">
                                {{-- seu player aqui (iframe ou player JS) --}}
                                <iframe src="{{ $aula->conteudo_url }}" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="p-3 border rounded bg-light">
                                {!! nl2br(e($aula->conteudo_texto ?? 'Conteúdo da aula')) !!}
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                {{ $aula->duracao_minutos }}min
                            </div>
                            <div class="d-flex gap-2">
                                {{-- Botão Anterior --}}
                                @if($prevAula)
                                    <a class="btn btn-outline-secondary"
                                       href="{{ route('aluno.curso.modulo.aula', [$curso->id, $modulo->id, $prevAula->id]) }}">
                                        &larr; Anterior
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>&larr; Anterior</button>
                                @endif

                                {{-- Botão Próxima (aula seguinte OU prova do módulo) --}}
                                @if($nextAula)
                                    <a class="btn btn-primary"
                                       href="{{ route('aluno.curso.modulo.aula', [$curso->id, $modulo->id, $nextAula->id]) }}">
                                        Próxima &rarr;
                                    </a>
                                @else
                                    {{-- fim das aulas deste módulo: se houver quiz, manda para a prova --}}
                                    @if($modulo->quiz)
                                        <a class="btn btn-primary"
                                           href="{{ route('aluno.quiz.show', [$curso->id, $modulo->quiz->id]) }}">
                                            Ir para a Prova do Módulo &rarr;
                                        </a>
                                    @else
                                        <button class="btn btn-primary" disabled>Fim do Módulo</button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Material de apoio --}}
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Material de Apoio</h5>
                        @php
                            // se você tiver relação $aula->materiais
                            $materiais = method_exists($aula,'materiais') ? $aula->materiais : collect();
                        @endphp

                        @if($materiais->count())
                            <ul class="list-group">
                                @foreach($materiais as $mat)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $mat->titulo ?? 'Arquivo' }}</span>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ $mat->arquivo_url ?? '#' }}" target="_blank">Baixar</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">Nenhum material cadastrado.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SIDEBAR (CONTEÚDO DO CURSO) --}}
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Conteúdo do Curso</h5>

                        @foreach($curso->modulos->sortBy('ordem') as $idx => $m)
                            @php
                                $isAtual = (int)$m->id === (int)$modulo->id;
                                $quiz    = $m->quiz ?? null;
                                $tent    = $quiz ? ($ultimaTentativaPorQuiz[$quiz->id] ?? null) : null;

                                $statusQuiz = 'pend';
                                if ($tent) {
                                    $statusQuiz = $tent->aprovado ? 'ok' : 'reprov';
                                }
                            @endphp

                            <div class="mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-semibold">
                                        Módulo {{ $idx+1 }}: {{ $m->titulo }}
                                    </div>
                                    @if($isAtual)
                                        <span class="badge bg-primary">Atual</span>
                                    @endif
                                </div>
                                @if($m->descricao)
                                    <div class="text-muted small">{{ $m->descricao }}</div>
                                @endif>

                                <div class="mt-2">
                                    {{-- Aulas --}}
                                    @foreach($m->aulas->sortBy('ordem') as $a)
                                        <a class="d-flex justify-content-between align-items-center list-group-item list-group-item-action mb-1 {{ (int)$a->id === (int)$aula->id ? 'active' : '' }}"
                                           href="{{ route('aluno.curso.modulo.aula', [$curso->id, $m->id, $a->id]) }}">
                                            <span class="text-truncate">{{ $a->titulo }}</span>
                                            <small class="text-muted">{{ $a->duracao_minutos }}min</small>
                                        </a>
                                    @endforeach

                                    {{-- Prova do módulo --}}
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        @if($quiz)
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('aluno.quiz.show', [$curso->id, $quiz->id]) }}">
                                                Prova do Módulo
                                            </a>
                                            @switch($statusQuiz)
                                                @case('ok')
                                                    <span class="badge bg-success">OK</span>
                                                    @break
                                                @case('reprov')
                                                    <span class="badge bg-danger">Reprovado</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">Pend.</span>
                                            @endswitch
                                        @else
                                            <span class="text-muted small">Prova do módulo não cadastrada</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
