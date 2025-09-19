@extends('layouts.app')

@section('title','Relatórios')

@section('content')
    <div class="mx-auto container-page px-4 py-6">
        {{-- Tabs --}}
        @include('prof._tabs',['active'=>'relatorios'])

        <h1 class="text-2xl font-bold mb-4">Relatórios</h1>

        <div class="grid md:grid-cols-3 gap-4">
            {{-- Relatório de Alunos --}}
            <a href="{{ route('prof.relatorios.alunos') }}"
               class="rounded-xl border bg-white p-4 shadow-sm hover:bg-gray-50 transition">
                <h3 class="font-semibold">Relatório de Alunos</h3>
                <p class="text-sm text-slate-500">Matrículas e status</p>
            </a>

            {{-- Relatório de Pedidos --}}
            <a href="{{ route('prof.relatorios.pedidos') }}"
               class="rounded-xl border bg-white p-4 shadow-sm hover:bg-gray-50 transition">
                <h3 class="font-semibold">Relatório de Pedidos</h3>
                <p class="text-sm text-slate-500">Financeiro básico</p>
            </a>

            {{-- Relatório de Cursos --}}
            <a href="{{ route('prof.relatorios.cursos') }}"
               class="rounded-xl border bg-white p-4 shadow-sm hover:bg-gray-50 transition">
                <h3 class="font-semibold">Relatório de Cursos</h3>
                <p class="text-sm text-slate-500">Receita e inscritos por curso</p>
            </a>
        </div>
    </div>
@endsection
