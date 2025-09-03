@extends('layouts.app')
@section('title', 'Área do Aluno - Visão Geral')

@section('content')
    <div class="container-page py-6">
        @include('aluno._tabs', ['aluno' => $aluno, 'stats' => $stats])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            {{-- Continue Aprendendo --}}
            <div class="lg:col-span-2 rounded-xl border bg-white p-4 shadow-sm">
                <h3 class="text-lg font-semibold mb-3">Continue Aprendendo</h3>
                <div class="space-y-3">
                    @forelse(($continuar ?? []) as $item)
                        <div class="rounded-lg border p-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-md"></div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium">{{ $item['titulo'] }}</div>
                                    <div class="w-full bg-gray-100 h-2 rounded mt-2">
                                        <div class="bg-blue-600 h-2 rounded" style="width: {{ $item['percent'] }}%"></div>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ $item['aulas_feitas'] }}/{{ $item['aulas_total'] }} aulas • {{ $item['percent'] }}%
                                    </div>
                                </div>
                                <a href="{{ $item['link'] }}" class="btn-primary text-xs px-3 py-1 rounded-md">Continuar</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Você ainda não iniciou nenhum curso.</p>
                    @endforelse
                </div>
            </div>

            {{-- Atividades Recentes --}}
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <h3 class="text-lg font-semibold mb-3">Atividades Recentes</h3>
                <ul class="space-y-2">
                    @forelse(($recentes ?? []) as $r)
                        <li class="text-sm">
                            <span class="font-medium">{{ $r['titulo'] }}</span>
                            <div class="text-xs text-slate-500">{{ $r['curso'] ?? '' }} {{ isset($r['quando']) ? '• '.$r['quando'] : '' }}</div>
                        </li>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma atividade recente encontrada.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
