{{-- resources/views/aluno/curso-conteudo.blade.php --}}
@extends('layouts.app')
@section('title', $curso->titulo)

@section('content')
    <section class="container-page mx-auto max-w-5xl py-8">
        <h1 class="text-2xl font-bold mb-4">{{ $curso->titulo }}</h1>

        @foreach($curso->modulos()->orderBy('ordem')->get() as $i => $modulo)
            @php
                // 👇 É AQUI QUE ENTRA O GATE:
                $liberado = \App\Support\CursoGate::podeAcessarModulo($curso, $matricula, $i);
            @endphp

            <div class="rounded-lg border p-4 mb-4 {{ $liberado ? '' : 'opacity-60' }}">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">Módulo {{ $i+1 }} — {{ $modulo->titulo }}</h2>

                    @unless($liberado)
                        <span class="text-xs text-amber-700">
            Bloqueado: atinja nota mínima {{ (float)$curso->nota_minima_aprovacao }} no quiz do módulo anterior.
          </span>
                    @endunless
                </div>

                <p class="text-sm text-slate-600 mt-1">{{ $modulo->descricao }}</p>

                <div class="mt-3 space-y-2">
                    @foreach($modulo->aulas as $aIdx => $a)
                        @php
                            // opcional: regra intra-módulo (liberar só após aula anterior)
                            $aulaLiberada = $liberado; // comece pelo gate do módulo
                            if ($aulaLiberada && $a->liberada_apos_anterior && $aIdx > 0) {
                              // se você tiver uma tabela de conclusões, cheque aqui;
                              // por enquanto, mantemos a mesma flag do módulo
                              $aulaLiberada = false; // ajuste conforme sua regra real
                            }
                        @endphp

                        <div class="flex items-center justify-between rounded border p-3 {{ $aulaLiberada ? '' : 'opacity-50 pointer-events-none' }}">
                            <div>
                                <div class="font-medium">{{ $a->titulo }}</div>
                                <div class="text-xs text-slate-500">Tipo: {{ $a->tipo }} • {{ $a->duracao_minutos }} min</div>
                            </div>
                            @if($a->tipo === 'video' && $a->conteudo_url)
                                <a href="{{ route('aluno.aula.video', $a) }}" class="btn btn-outline btn-sm">Assistir</a>
                            @else
                                <a href="{{ route('aluno.aula.show', $a) }}" class="btn btn-outline btn-sm">Abrir</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
@endsection
