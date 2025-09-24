@extends('layouts.app')
@section('title','Cupons')

@section('content')
    <div class="container mx-auto py-6 space-y-4 max-w-5xl">
        @include('prof._tabs', ['active' => 'cupons'])


        @if(session('ok'))
            <div class="rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-2">
                {{ session('ok') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h1 class="text-lg font-semibold">Cupons</h1>
            <a href="{{ route('prof.cupons.create') }}" class="btn">+ Novo Cupom</a>
        </div>

        <form method="GET" class="max-w-md">
            <input type="text"
                   name="q"
                   value="{{ $q }}"
                   class="input w-full"
                   placeholder="Buscar por código...">
        </form>

        <div class="overflow-x-auto border rounded-lg bg-white">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left">Código</th>
                    <th class="px-3 py-2 text-left">Tipo</th>
                    <th class="px-3 py-2 text-left">Valor</th>
                    <th class="px-3 py-2 text-left">Início</th>
                    <th class="px-3 py-2 text-left">Fim</th>
                    <th class="px-3 py-2 text-left">Ativo</th>
                    <th class="px-3 py-2"></th>
                </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($itens as $c)
                    <tr class="odd:bg-white even:bg-slate-50/40">
                        <td class="px-3 py-2 font-mono">{{ $c->codigo }}</td>
                        <td class="px-3 py-2">
                            {{ $c->tipo === 'percentual' ? 'Percentual (%)' : 'Fixo (R$)' }}
                        </td>
                        <td class="px-3 py-2">
                            @if($c->tipo === 'percentual')
                                {{ number_format($c->valor, 0) }}%
                            @else
                                R$ {{ number_format($c->valor, 2, ',', '.') }}
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ optional($c->inicio_em)->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">{{ optional($c->fim_em)->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded text-xs {{ $c->ativo ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $c->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                        </td>
                        <td class="px-3 py-2 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('prof.cupons.edit', $c) }}" class="text-blue-600 hover:underline">Editar</a>
                                <form action="{{ route('prof.cupons.destroy', $c) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Excluir cupom {{ $c->codigo }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:underline">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-8 text-center text-slate-500">
                            Nenhum cupom encontrado.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div>
            {{ $itens->onEachSide(1)->links() }}
        </div>
    </div>
@endsection
