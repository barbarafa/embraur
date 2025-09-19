@extends('layouts.app')
@section('title','Relatório de Pedidos')
@section('content')
    <div class="mx-auto container-page px-4 py-6">
    @include('prof._tabs', ['active' => 'relatorios'])

    <form class="mt-4 grid md:grid-cols-5 gap-3">
        <select name="status" class="border rounded px-3 py-2">
            <option value="">Status (todos)</option>
            @foreach(['pendente','pago','cancelado','estornado'] as $st)
                <option value="{{ $st }}" @selected(($filtro['status'] ?? '')===$st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <select name="metodo_pagamento" class="border rounded px-3 py-2">
            <option value="">Método (todos)</option>
            @foreach(['pix','boleto','cartao'] as $mp)
                <option value="{{ $mp }}" @selected(($filtro['metodo'] ?? '')===$mp)>{{ strtoupper($mp) }}</option>
            @endforeach
        </select>
        <select name="curso_id" class="border rounded px-3 py-2">
            <option value="0">Todos os cursos</option>
            @foreach($cursos as $c)
                <option value="{{ $c->id }}" @selected(($filtro['cursoId'] ?? 0)==$c->id)>{{ $c->titulo }}</option>
            @endforeach
        </select>
        <div class="grid grid-cols-2 gap-2">
            <input type="date" name="from" value="{{ $filtro['from'] ?? '' }}" class="border rounded px-3 py-2">
            <input type="date" name="to"   value="{{ $filtro['to']   ?? '' }}" class="border rounded px-3 py-2">
        </div>
        <button class="px-4 py-2 rounded bg-[#889875] text-white">Filtrar</button>
    </form>

    <div class="mt-4 grid md:grid-cols-3 gap-3">
        <div class="rounded-lg border bg-white p-4">
            <div class="text-sm text-slate-500">Receita (itens seus)</div>
            <div class="text-2xl font-bold">R$ {{ number_format($totais->soma ?? 0, 2, ',', '.') }}</div>
        </div>
        <div class="rounded-lg border bg-white p-4">
            <div class="text-sm text-slate-500">Pedidos</div>
            <div class="text-2xl font-bold">{{ $totais->pedidos ?? 0 }}</div>
        </div>
    </div>

    <div class="mt-3 rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
            <tr>
                <th class="p-3 text-left">#</th>
                <th class="p-3 text-left">Aluno</th>
                <th class="p-3 text-left">Data Pagto</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Método</th>
                <th class="p-3 text-right">Valor</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $r)
                <tr class="border-t">
                    <td class="p-3">{{ $r->id }}</td>
                    <td class="p-3">{{ $r->aluno }}</td>
                    <td class="p-3">{{ $r->data_pagamento ? \Carbon\Carbon::parse($r->data_pagamento)->format('d/m/Y') : '—' }}</td>
                    <td class="p-3">{{ ucfirst($r->status) }}</td>
                    <td class="p-3">{{ strtoupper($r->metodo_pagamento ?? '—') }}</td>
                    <td class="p-3 text-right">R$ {{ number_format($r->valor_prof, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $rows->withQueryString()->links() }}</div>
    </div>
    </div>
@endsection
