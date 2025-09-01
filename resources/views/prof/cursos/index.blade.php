@extends('layouts.app')
@section('title','Professor - Cursos')

@section('content')
    <section class="mx-auto container-page px-4 py-10">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-2xl font-bold">Meus Cursos</h1>
            <div class="flex items-center gap-2">
                <form action="{{ route('prof.logout') }}" method="post">@csrf
                    <button class="btn btn-outline">Sair</button>
                </form>
                <a href="{{ route('prof.cursos.create') }}" class="btn btn-primary">Novo Curso</a>
            </div>
        </div>

        @if(session('ok'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('ok') }}</div>
        @endif

        <div class="card">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                <tr>
                    <th class="text-left p-3">Título</th>
                    <th class="text-left p-3">Categoria</th>
                    <th class="text-left p-3">Nível</th>
                    <th class="text-right p-3">Preço</th>
                    <th class="p-3"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($cursos as $c)
                    <tr class="border-t">
                        <td class="p-3">{{ $c->titulo }}</td>
                        <td class="p-3">{{ $c->categoria->nome ?? '-' }}</td>
                        <td class="p-3">{{ $c->nivel }}</td>
                        <td class="p-3 text-right">R$ {{ number_format($c->preco_final,2,',','.') }}</td>
                        <td class="p-3 text-right">
                            <a class="text-blue-600 hover:underline mr-3" href="{{ route('prof.cursos.edit',$c) }}">Editar</a>
                            <form action="{{ route('prof.cursos.destroy',$c) }}" method="post" class="inline" onsubmit="return confirm('Excluir?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-4" colspan="5">Nenhum curso.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $cursos->links() }}</div>
    </section>
@endsection
