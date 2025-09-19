@extends('layouts.app')
@section('title','Relatório de Cursos')
@section('content')

    <div class="mx-auto container-page px-4 py-6">
        @include('prof._tabs', ['active' => 'relatorios'])
    <form class="mt-4 grid md:grid-cols-4 gap-3">
        <select name="curso_id" class="border rounded px-3 py-2">
            <option value="0">Todos os cursos</option>
            @foreach($cursos as $c)
                <option value="{{ $c->id }}" @selected(($filtro['cursoId'] ?? 0)==$c->id)>{{ $c->titulo }}</option>
            @endforeach
        </select>
        <select name="status" class="border rounded px-3 py-2">
            <option value="">Status (todos)</option>
            @foreach(['publicado','rascunho','oculto'] as $st)
                <option value="{{ $st }}" @selected(($filtro['status'] ?? '')===$st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button class="px-4 py-2 rounded bg-[#889875] text-white">Filtrar</button>
    </form>

    <div class="mt-4 rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
            <tr>
                <th class="p-3 text-left">Curso</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-right">Alunos</th>
                <th class="p-3 text-right">Pedidos pagos</th>
                <th class="p-3 text-right">Receita</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $r)
                <tr class="border-t">
                    <td class="p-3">{{ $r->titulo }}</td>
                    <td class="p-3">{{ ucfirst($r->status) }}</td>
                    <td class="p-3 text-right">{{ $r->alunos }}</td>
                    <td class="p-3 text-right">{{ $r->pedidos_pagos }}</td>
                    <td class="p-3 text-right">R$ {{ number_format($r->receita_total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $rows->withQueryString()->links() }}</div>
    </div>
    </div>
@endsection
