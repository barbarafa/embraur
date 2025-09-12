@extends('layouts.app')
@section('title','Alunos')

@section('content')
    <div class="container-page py-6">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Gerenciar Alunos</h1>
                <p class="text-slate-600 text-sm">Visualize o progresso e gerencie seus alunos</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('prof.cursos.create') }}"
                   class="btn-primary rounded-md h-10 px-4 flex items-center gap-2">
                    <span class="text-lg">+</span> Criar Curso
                </a>
                <button class="h-10 w-10 grid place-items-center rounded-md border hover:bg-gray-50">⚙️</button>
            </div>
        </div>

        {{-- TABS --}}
        @include('prof._tabs', ['active' => 'alunos'])

        {{-- CARD: Gerenciar Alunos --}}
        <div class="rounded-xl border bg-white p-4 shadow-sm mt-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-lg font-semibold">Gerenciar Alunos</h3>
                    <p class="text-xs text-slate-500">Visualize o progresso e gerencie seus alunos</p>
                </div>

                <button type="button"
                        class="inline-flex items-center gap-2 h-9 px-3 rounded-md border text-sm hover:bg-gray-50">
                    Filtrar
                </button>
            </div>

            {{-- Busca --}}
            <form method="get" action="{{ route('prof.alunos.index') }}" class="mb-4">
                <div class="relative">
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar alunos..."
                           class="w-full h-10 pl-10 pr-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30"/>
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
                </div>
            </form>

            {{-- LISTA --}}
            @if(($alunos ?? collect())->isEmpty())
                <div class="text-center py-12 text-slate-500">Nenhum aluno encontrado.</div>
            @else
                <div class="space-y-3">
                    @foreach($alunos as $aluno)
                        @php
                            $percent = max(0, min(100, (int)($aluno['percent'] ?? 0)));
                            $quando  = $aluno['quando'] ?? '—';
                        @endphp
                        <div class="rounded-lg border p-3 flex items-center gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="h-10 w-10 rounded-full grid place-items-center bg-gray-100 text-slate-400">👤</div>
                                <div class="min-w-0">
                                    <div class="font-medium truncate">{{ $aluno['nome'] }}</div>
                                    <div class="text-xs text-slate-500 truncate">{{ $aluno['curso'] }}</div>
                                </div>
                            </div>
                            <div class="ml-auto w-full sm:w-2/5 md:w-1/3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-600">{{ $percent }}% concluído</span>
                                    <span class="text-slate-400">{{ $quando }}</span>
                                </div>
                                <div class="mt-1 h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-2 rounded-full bg-blue-600" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            {{--
                            <div class="shrink-0">
                                <a href="#" class="ml-2 h-9 px-3 rounded-md border text-sm hover:bg-gray-50 whitespace-nowrap">Ver Detalhes</a>
                            </div>
                            --}}
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">{{ $matriculas->appends(['q' => $q ?? null])->links() }}</div>
            @endif
        </div>
    </div>
@endsection
