@extends('layouts.app')
@section('title', 'Quiz - '.$quiz->titulo)
@section('content')
    <section class="container-page mx-auto max-w-3xl py-8">
        <h1 class="text-xl font-semibold mb-4">{{ $quiz->titulo }}</h1>
        <form method="post" action="{{ route('aluno.quiz.submit', [$curso, $quiz]) }}" class="space-y-6">
            @csrf

            @foreach($quiz->questoes as $i => $q)
                <div class="rounded-lg border p-4">
                    <div class="font-medium mb-2">Q{{ $i+1 }}. {!! nl2br(e($q->enunciado)) !!} <span class="text-xs text-slate-500">({{ (float)$q->pontuacao }} pts)</span></div>
                    <input type="hidden" name="respostas[{{ $i }}][questao_id]" value="{{ $q->id }}">
                    @if($q->tipo === 'multipla')
                        <div class="space-y-2">
                            @foreach($q->opcoes as $op)
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="respostas[{{ $i }}][opcao_id]" value="{{ $op->id }}" class="h-4 w-4">
                                    <span>{{ $op->texto }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <textarea name="respostas[{{ $i }}][resposta_texto]" rows="3" class="w-full rounded-md border px-3 py-2"></textarea>
                    @endif
                </div>
            @endforeach

            <div class="text-right">
                <button class="btn btn-primary">Enviar respostas</button>
            </div>
        </form>
    </section>
@endsection
