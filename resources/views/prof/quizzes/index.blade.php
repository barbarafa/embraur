@extends('layouts.app')
@section('title','Quizzes')

@section('content')
    <div class="container-page mx-auto py-6 max-w-5xl">



        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold">Quizzes</h1>

            <div class="mb-4 flex items-center justify-between">
                <a href="{{ route('prof.cursos.edit', $cursoId) }}" class="btn btn-outline">← Voltar para o curso</a>
            </div>

            @php $canCreate = !empty($cursoId) && !empty($moduloId); @endphp
            <a
                href="{{ $canCreate ? route('prof.quizzes.create', ['curso' => $cursoId, 'modulo' => $moduloId]) : '#' }}"
                class="btn btn-primary {{ $canCreate ? '' : 'opacity-50 pointer-events-none' }}"
                title="{{ $canCreate ? 'Criar prova para este módulo' : 'Selecione curso e módulo' }}"
            >
                ＋ Novo Quiz
            </a>
        </div>

        {{-- Filtros --}}
        <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="text-sm font-medium">Curso</label>
                <select name="curso" class="mt-1 w-full h-10 rounded-md border px-3 bg-white"
                        onchange="this.form.submit()">
                    <option value="">— Todos —</option>
                    @foreach($cursos as $c)
                        <option value="{{ $c->id }}" @selected((int)($cursoId ?? 0) === (int)$c->id)>
                            {{ $c->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Módulo</label>
                <select name="modulo" class="mt-1 w-full h-10 rounded-md border px-3 bg-white"
                        @disabled(empty($cursoId))
                        onchange="this.form.submit()">
                    <option value="">— Todos —</option>
                    @foreach($modulos as $m)
                        <option value="{{ $m->id }}" @selected((int)($moduloId ?? 0) === (int)$m->id)>
                            {{ $m->ordem }} — {{ $m->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button class="btn btn-outline w-full h-10">Aplicar</button>
            </div>
        </form>

        {{-- Lista --}}
        <div class="rounded-lg border divide-y bg-white">
            @forelse ($quizzes as $q)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ $q->titulo }}</div>
                        <div class="text-xs text-slate-600">
                            Curso: {{ $q->curso->titulo ?? '—' }}
                            @if($q->modulo)
                                • Módulo {{ $q->modulo->ordem }} — {{ $q->modulo->titulo }}
                            @endif
                            • {{ $q->questoes_count }} questão(ões)
                        </div>
                    </div>
                    <div class="text-sm">
                        <a href="{{ route('prof.quizzes.edit', $q->id) }}" class="text-blue-600 hover:underline">Editar</a>
                    </div>
                </div>
            @empty
                <div class="p-6 text-slate-600">Nenhum quiz encontrado.</div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $quizzes->links() }}
        </div>
    </div>
@endsection
