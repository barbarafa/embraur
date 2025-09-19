@extends('layouts.app')
@section('title','Painel do Professor')

@section('content')
    <div class="container-page py-6">
        {{-- Cabeçalho --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">
                    Olá, Prof. {{ $profNome }}! <span class="ml-1">🧑‍🏫</span>
                </h1>
                <p class="text-slate-600 text-sm">Gerencie seus cursos e acompanhe o progresso dos alunos.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('prof.cursos.create') }}" class="btn-primary rounded-md h-10 px-4 flex items-center gap-2">
                    <span class="text-lg">＋</span>
                    Criar Curso
                </a>
            </div>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-600">Cursos</div>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-2xl font-semibold">{{ $cursos }}</div>
                    <div class="text-xl">📘</div>
                </div>
            </div>
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-600">Alunos</div>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-2xl font-semibold">{{ $alunos }}</div>
                    <div class="text-xl">👥</div>
                </div>
            </div>
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-600">Receita Total</div>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-2xl font-semibold">R$ {{ number_format($receitaTotal, 2, ',', '.') }}</div>
                    <div class="text-xl">💲</div>
                </div>
            </div>
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="text-sm text-slate-600">Este Mês</div>
                <div class="flex items-center justify-between mt-2">
                    <div class="text-2xl font-semibold">R$ {{ number_format($receitaMes, 2, ',', '.') }}</div>
                    <div class="text-xl">📈</div>
                </div>
            </div>
        </div>

        {{-- Abas --}}
        @include('prof._tabs', ['active' => 'dashboard'])

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            {{-- Atividade dos Alunos --}}
            <div class="lg:col-span-2 rounded-xl border bg-white p-4 shadow-sm">
                <h3 class="text-lg font-semibold">Atividade dos Alunos</h3>
                <p class="text-xs text-slate-500 mb-3">Progresso recente dos seus alunos</p>

                {{-- SCROLL SÓ NESTE CARD --}}
                <div id="atividade-scroll" class="max-h-[520px] overflow-auto pr-2">
                    @foreach($atividade as $item)
                        <div class="rounded-lg border p-3 mb-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium">{{ $item['nome'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $item['curso'] }}</div>
                                </div>
                                <div class="text-xs">
                                    <span class="px-2 py-1 rounded bg-blue-600 text-white">{{ $item['situacao'] }}</span>
                                    <span class="text-slate-500 ml-2">{{ $item['quando'] }}</span>
                                </div>
                            </div>

                            <div class="mt-2 w-full bg-gray-100 h-2 rounded">
                                <div class="bg-blue-600 h-2 rounded" style="width: {{ $item['percent'] }}%"></div>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">{{ $item['percent'] }}%</div>
                        </div>
                    @endforeach
                </div>

                {{-- paginação SÓ do card de atividade --}}
                <div class="mt-3">
                    {{ $atividade->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
