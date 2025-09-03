@extends('layouts.app')
@section('title','Módulos do Curso')

@section('content')
    <div class="container-page py-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Módulos — {{ $curso->titulo }}</h1>
                <p class="text-slate-600 text-sm">Organize a estrutura e as aulas do curso.</p>
            </div>
            <a href="{{ route('prof.cursos.edit',$curso) }}" class="btn btn-outline">Voltar ao curso</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-xl border bg-white p-5 shadow-sm">
                @forelse($modulos as $modulo)
                    <div class="rounded-lg border mb-4 p-4">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold">#{{ $modulo->ordem }} — {{ $modulo->titulo }}</div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('prof.cursos.modulos.update',[$curso,$modulo]) }}">
                                    @csrf @method('PUT')
                                    <input name="titulo" value="{{ $modulo->titulo }}" class="h-8 rounded border-slate-300 px-2">
                                    <button class="btn btn-soft h-8">Renomear</button>
                                </form>
                                <a href="{{ route('prof.cursos.modulos.aulas.index',[$curso,$modulo]) }}" class="btn btn-outline h-8">Aulas</a>
                                <form method="POST" action="{{ route('prof.cursos.modulos.destroy',[$curso,$modulo]) }}" onsubmit="return confirm('Remover módulo e suas aulas?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline h-8">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500">Nenhum módulo ainda.</p>
                @endforelse
            </div>

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <h3 class="font-semibold mb-3">Novo módulo</h3>
                <form method="POST" action="{{ route('prof.cursos.modulos.store',$curso) }}" class="space-y-3">
                    @csrf
                    <input name="titulo" class="w-full h-10 rounded-md border-slate-300" placeholder="Ex.: Introdução" required>
                    <button class="btn-primary h-10 w-full rounded-md">Adicionar módulo</button>
                </form>
            </div>
        </div>
    </div>
@endsection
