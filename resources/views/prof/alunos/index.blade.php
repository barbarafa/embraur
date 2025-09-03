@extends('layouts.app')
@section('title','Alunos')

@section('content')
    <div class="container-page py-6">

        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Gerenciar Alunos</h1>
                <p class="text-slate-600 text-sm">Visualize o progresso e gerencie seus alunos</p>
            </div>
        </div>

        {{-- Abas iguais às demais telas --}}
        <div class="rounded-xl border bg-white p-1 shadow-sm flex items-center gap-2 text-sm overflow-x-auto mb-4">
            <a href="{{ route('prof.dashboard') }}"
               class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">Visão Geral</a>

            <a href="{{ route('prof.cursos.index') }}"
               class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">Meus Cursos</a>

            <button class="px-4 py-2 rounded-lg bg-gray-100 font-semibold">Alunos</button>

            <a href="{{ route('prof.duvidas.index') }}"
               class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">Dúvidas</a>

            <a href="#"
               class="px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">Relatórios</a>
        </div>

        {{-- Busca / filtros --}}
        <form method="GET" class="bg-white border rounded-xl p-3 shadow-sm mb-4 flex items-center gap-3">
            <div class="relative w-full md:w-96">
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar alunos..."
                       class="w-full h-10 rounded-md border-slate-300 pl-10 pr-3">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
            </div>
            <button class="h-10 px-4 rounded-md border text-slate-700 hover:bg-gray-50">Filtrar</button>
        </form>

        @if($alunos->isEmpty())
            <div class="rounded-2xl border bg-white shadow-sm p-10 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-2xl">👥</div>
                <h3 class="text-lg font-semibold mt-4">Nenhum aluno encontrado</h3>
                <p class="text-slate-500 mt-1">Aparecerão aqui os alunos matriculados nos seus cursos.</p>
            </div>
        @else
            <div class="rounded-xl border bg-white p-0 shadow-sm overflow-hidden">
                @foreach($alunos as $a)
                    <div class="flex items-center gap-3 px-4 py-3 border-b last:border-0">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-sm">👤</div>

                        <div class="flex-1 min-w-0">
                            <div class="font-medium truncate">{{ $a['nome'] }}</div>
                            <div class="text-xs text-slate-500">{{ $a['curso'] }}</div>

                            <div class="mt-2 w-full bg-gray-100 h-2 rounded">
                                <div class="bg-blue-600 h-2 rounded" style="width: {{ (int) $a['percent'] }}%"></div>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">
                                {{ (int)$a['percent'] }}% concluído • {{ $a['quando'] }}
                            </div>
                        </div>

                        <a href="#"
                           class="ml-2 h-8 px-3 rounded-md border text-sm hover:bg-gray-50 whitespace-nowrap">
                            Ver Detalhes
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $matriculas->links() }}
            </div>
        @endif
    </div>
@endsection
