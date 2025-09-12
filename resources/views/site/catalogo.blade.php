@extends('layouts.app')

@section('title','Catálogo de Cursos')

@section('content')
    <section class="mx-auto container-page px-4 py-10">
        <h1 class="text-3xl font-extrabold text-center">Catálogo de Cursos</h1>
        <p class="text-center text-slate-600 mt-2">
            Explore nossa biblioteca completa de cursos profissionais e certifique-se com qualidade.
        </p>

        {{-- Busca + Filtros --}}
        <form method="get" class="mt-6">
            <div class="bg-white border rounded-xl p-4 shadow-sm">
                <div class="flex flex-col md:flex-row gap-3 items-center">
                    <div class="relative w-full">
                        <span class="absolute left-3 top-2.5 text-slate-400">🔎</span>
                        <input
                            name="busca"
                            value="{{ request('busca') }}"
                            class="w-full pl-9 pr-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500/30"
                            placeholder="Buscar cursos..."
                        >
                    </div>

                    <div class="w-full md:w-auto">
                        <select name="categoria" class="w-full md:w-56 px-3 py-2 border rounded-md">
                            <option value="">Todas as categorias</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->slug }}" @selected(request('categoria')===$cat->slug)>
                                    {{ $cat->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary px-4 py-2 rounded-md">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>

        {{-- Grid de cards --}}
        <div class="grid gap-4 mt-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($cursos as $curso)
                <article class="rounded-xl border bg-white overflow-hidden shadow-sm hover:shadow-md transition">
                    {{-- Capa --}}
                    <div class="h-36 bg-slate-100">
                        <img
                            src="{{ $curso->imagem_capa_url }}"
                            alt="Capa do curso {{ $curso->titulo }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                    </div>

                    {{-- Conteúdo --}}
                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between text-[11px]">
                        <span class="px-2 py-1 rounded border border-blue-200 text-blue-700 bg-blue-50">
                            {{ $curso->categoria->nome ?? 'Sem categoria' }}
                        </span>
                            <span class="px-2 py-1 rounded border border-slate-200 text-slate-600 bg-slate-50">
                            {{ $curso->nivel ?? '—' }}
                        </span>
                        </div>

                        <h3 class="font-semibold leading-snug line-clamp-2">
                            {{ $curso->titulo }}
                        </h3>

                        <p class="text-sm text-slate-600 line-clamp-2">
                            {{ $curso->descricao_curta ?? '' }}
                        </p>

                        <div class="text-xs text-slate-500 flex items-center gap-4">
                            <span>⏱️ {{ (int)($curso->carga_horaria_total ?? 0) }}h</span>
                            <span>👥 {{ number_format($curso->matriculas_count ?? 0, 0, ',', '.') }} alunos</span>
                        </div>

                        {{-- Preço: usa preco_original como "de", quando maior que preco --}}
                        <div class="text-sm">
                            @php
                                $temPromo = isset($curso->preco_original) && (float)$curso->preco_original > (float)$curso->preco;
                            @endphp

                            @if($temPromo)
                                <span class="line-through text-slate-400 mr-1">
                                R$ {{ number_format($curso->preco_original, 2, ',', '.') }}
                            </span>
                            @endif

                            <span class="font-semibold text-blue-700">
                            R$ {{ number_format($curso->preco, 2, ',', '.') }}
                        </span>
                        </div>

                        {{-- Botão (comentado por enquanto) --}}

                        <a href="{{ route('site.curso.detalhe', $curso->id) }}"
                           class="btn btn-primary w-full">
                            Ver Detalhes
                        </a>

                    </div>
                </article>
            @empty
                <div class="md:col-span-2 lg:col-span-3 text-center text-slate-500 py-10">
                    Nenhum curso encontrado.
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        <div class="mt-6">
            {{ $cursos->appends(request()->query())->links() }}
        </div>
    </section>
@endsection
