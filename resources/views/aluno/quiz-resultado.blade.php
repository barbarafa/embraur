@extends('layouts.app')

@section('title', 'Resultado da Prova')

@section('content')
    <div class="container mx-auto py-6">

        <div class="rounded-lg border p-6 bg-white">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-lg font-semibold">
                    Prova {{ $quiz->titulo }} - Finalizada
                </h1>
                <a href="{{ route('aluno.curso.conteudo', $curso->id) }}"
                   class="text-sm text-slate-600 hover:underline flex items-center gap-1">
                    ← Voltar ao curso
                </a>
            </div>

            {{-- Nota --}}
            <div class="text-center mb-6">
                <div class="text-3xl font-bold
                {{ $tentativa->aprovado ? 'text-green-600' : 'text-red-600' }}">
                    {{ $nota10 }} / 10
                </div>
                <div class="text-slate-600 mt-1">
                    Nota mínima para aprovação: <strong>{{ number_format($notaMinima,1,',','.') }}</strong>
                </div>

                @if(!$tentativa->aprovado)
                    <p class="mt-3 text-red-600 font-medium">
                        Você não atingiu a nota mínima. Revise o conteúdo e tente novamente.
                    </p>
                @else
                    <p class="mt-3 text-green-600 font-medium">
                        Parabéns! Você atingiu a nota mínima e foi aprovado.
                    </p>
                @endif
            </div>

            {{-- Ações --}}
            <div class="flex justify-center gap-4 mb-6">
                <a href="{{ route('aluno.curso.conteudo', $curso->id) }}"
                   class="px-4 py-2 rounded border text-slate-700 hover:bg-slate-100">
                    Revisar conteúdo
                </a>

                <a href="{{ route('aluno.quiz.refazer', [$curso->id, $quiz->id]) }}"
                   class="px-4 py-2 rounded text-white bg-blue-600 hover:bg-blue-700">
                    Refazer prova
                </a>
            </div>

            {{-- Resumo das respostas --}}
            <h2 class="text-lg font-semibold mb-4">Resumo das respostas:</h2>
            <div class="space-y-3">
                @foreach($resumo as $i => $r)
                    <div class="border rounded p-4
                    {{ $r['ok'] ? 'bg-green-50 border-green-300' : 'bg-red-50 border-red-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">Questão {{ $i+1 }}: {{ $r['questao']->enunciado }}</span>
                            @if($r['ok'])
                                <span class="text-green-600 font-bold">+{{ $r['questao']->pontuacao }}</span>
                            @else
                                <span class="text-red-600 font-bold">0</span>
                            @endif
                        </div>
                        <div class="mt-2 text-sm">
                            <p><strong>Sua resposta:</strong>
                                {{ $r['sua']->texto ?? $respostas->get($r['questao']->id)->resposta_texto ?? 'Não respondida' }}
                            </p>
                            <p><strong>Resposta correta:</strong> {{ $r['correta']->texto ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
