@extends('layouts.app')
@section('title','Relatório de Alunos')
@section('content')
    <div class="mx-auto container-page px-4 py-6">
    @include('prof._tabs', ['active' => 'relatorios'])

    <form class="mt-4 grid md:grid-cols-5 gap-3">
        <input name="q" value="{{ $filtro['q'] ?? '' }}" placeholder="Nome do aluno" class="border rounded px-3 py-2 md:col-span-2">
        <select name="curso_id" class="border rounded px-3 py-2">
            <option value="0">Todos os cursos</option>
            @foreach($cursos as $c)
                <option value="{{ $c->id }}" @selected(($filtro['cursoId'] ?? 0)==$c->id)>{{ $c->titulo }}</option>
            @endforeach
        </select>
        <select name="status" class="border rounded px-3 py-2">
            <option value="">Status (todos)</option>
            <option value="ativo"      @selected(($filtro['status'] ?? '')==='ativo')>Ativo</option>
            <option value="concluido"  @selected(($filtro['status'] ?? '')==='concluido')>Concluído</option>
            <option value="suspenso"   @selected(($filtro['status'] ?? '')==='suspenso')>Suspenso</option>
        </select>
        <div class="grid grid-cols-2 gap-2">
            <input type="date" name="from" value="{{ $filtro['from'] ?? '' }}" class="border rounded px-3 py-2">
            <input type="date" name="to"   value="{{ $filtro['to']   ?? '' }}" class="border rounded px-3 py-2">
        </div>
        <button class="px-4 py-2 rounded bg-[#889875] text-white">Filtrar</button>
    </form>

    <div class="mt-4 rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
            <tr>
                <th class="p-3 text-left">Aluno</th>
                <th class="p-3 text-left">Curso</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Matrícula</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rows as $m)
                <tr class="border-t">
                    <td class="p-3">{{ $m->aluno->nome_completo ?? '—' }}</td>
                    <td class="p-3">{{ $m->curso->titulo ?? '—' }}</td>
                    <td class="p-3">{{ $m->status ?? '—' }}</td>
                    <td class="p-3">{{ optional($m->data_matricula)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $rows->withQueryString()->links() }}</div>
    </div>
    </div>
@endsection
