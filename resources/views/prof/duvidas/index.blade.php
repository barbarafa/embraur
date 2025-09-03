@extends('layouts.app')
@section('title','Dúvidas dos Alunos')

@section('content')
    <div class="container-page py-6">
        {{-- Título --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Dúvidas dos Alunos</h1>
                <p class="text-slate-600 text-sm">Responda às perguntas e gerencie as dúvidas pendentes.</p>
            </div>
        </div>

        {{-- Abas iguais às outras telas --}}
        <div class="rounded-xl border bg-white p-1 shadow-sm flex items-center gap-2 text-sm overflow-x-auto mb-4">
            <a href="{{ route('prof.dashboard') }}"
               class="px-4 py-2 rounded-lg {{ request()->routeIs('prof.dashboard') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                Visão Geral
            </a>
            <a href="{{ route('prof.cursos.index') }}"
               class="px-4 py-2 rounded-lg {{ request()->routeIs('prof.cursos.*') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                Meus Cursos
            </a>
            <a href="#"
               class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                Alunos
            </a>
            <a href="{{ route('prof.duvidas.index') }}"
               class="px-4 py-2 rounded-lg {{ request()->routeIs('prof.duvidas.*') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                Dúvidas
            </a>
            <a href="#"
               class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                Relatórios
            </a>
        </div>

        {{-- Barra de filtro simples (opcional) --}}
        <div class="bg-white border rounded-xl p-3 shadow-sm mb-4 flex items-center gap-3">
            <div class="relative w-full md:w-96">
                <input type="text" value="{{ request('q') }}" placeholder="Buscar por aluno, curso ou texto…"
                       class="w-full h-10 rounded-md border-slate-300 pl-10 pr-3"
                       onkeydown="if(event.key==='Enter'){ window.location='{{ route('prof.duvidas.index') }}?q='+encodeURIComponent(this.value) }">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
            </div>

            <form method="GET" action="{{ route('prof.duvidas.index') }}" class="hidden">
                {{-- apenas para permitir filtro via botão, se quiser ativar depois --}}
                <input type="hidden" name="q" value="{{ request('q') }}">
            </form>
        </div>

        @if(($duvidas->total() ?? $duvidas->count()) === 0)
            {{-- EMPTY STATE --}}
            <div class="rounded-2xl border bg-white shadow-sm p-10 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-2xl">💬</div>
                <h3 class="text-lg font-semibold mt-4">Nenhuma dúvida pendente.</h3>
                <p class="text-slate-500 mt-1">Quando seus alunos enviarem perguntas, elas aparecerão aqui.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($duvidas as $d)
                    <div class="rounded-xl border bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="font-medium">{{ $d->aluno_nome ?? 'Aluno' }}</div>
                                <div class="text-xs text-slate-500">{{ $d->curso_titulo ?? 'Curso' }}</div>
                            </div>
                            <div class="text-xs text-slate-500">{{ $d->quando ?? ($d->created_at? $d->created_at->diffForHumans() : '') }}</div>
                        </div>

                        <p class="text-sm mt-3">{{ $d->pergunta ?? $d->texto }}</p>

                        <div class="mt-3 flex items-center gap-2">
                            {{-- Link para responder (ajuste a rota da tela de resposta, se existir) --}}
                            <a href="#" class="btn-primary text-xs px-3 py-1 rounded-md">Responder</a>

                            @if(empty($d->lida) || !$d->lida)
                                <form method="POST" action="{{ route('prof.duvidas.markRead', $d->id) }}">
                                    @csrf
                                    <button class="btn btn-soft text-xs px-3 py-1 rounded-md">Marcar como lida</button>
                                </form>
                            @else
                                <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-700">Lida</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- paginação --}}
            @if(method_exists($duvidas,'links'))
                <div class="mt-6">{{ $duvidas->links() }}</div>
            @endif
        @endif
    </div>
@endsection
