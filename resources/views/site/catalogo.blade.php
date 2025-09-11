@extends('layouts.app')

@section('title','Catálogo de Cursos')

@section('content')
    <section class="mx-auto container-page px-4 py-10">
        <h1 class="text-3xl font-extrabold text-center">Catálogo de Cursos</h1>
        <p class="text-center text-slate-600 mt-2">Explore nossa biblioteca completa de cursos profissionais e certifique-se com qualidade.</p>

        {{-- Busca + Filtros --}}
        <form method="get" class="mt-6">
            <div class="bg-white border rounded-lg p-4">
                <div class="flex flex-col md:flex-row gap-3 items-center">
                    <div class="relative w-full">
                        <i class="ri-search-line absolute left-3 top-2.5 text-slate-400"></i>
                        <input name="busca" value="{{ request('busca') }}" class="w-full pl-9 pr-3 py-2 border rounded-md" placeholder="Buscar cursos...">
                    </div>
                    <div>
                        <select name="categoria" class="px-3 py-2 border rounded-md">
                            <option value="">Todas as categorias</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->slug }}" @selected(request('categoria')===$cat->slug)>{{ $cat->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        {{-- Cards --}}
        <div class="grid md:grid-cols-3 gap-4 mt-6">
            @forelse ($cursos as $curso)
                <div class="card overflow-hidden">
                    <div class="h-32 bg-slate-100 overflow-hidden">
                        <img src="{{ $curso->imagem_capa_url }}"
                             alt="Capa do curso {{ $curso->titulo }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 space-y-2">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="badge border-blue-200 text-blue-700 bg-blue-50">{{ $curso->categoria->nome }}</span>
                            <span class="badge border-slate-200 text-slate-600 bg-slate-50">{{ $curso->nivel }}</span>
                        </div>
                        <h3 class="font-semibold leading-snug">{{ $curso->titulo }}</h3>
                        <p class="text-sm text-slate-600 line-clamp-2">{{ $curso->descricao }}</p>
                        <div class="text-xs text-slate-500 flex items-center gap-3">
                            <span><i class="ri-time-line mr-1"></i> {{ $curso->carga_horaria }}h</span>
                            <span><i class="ri-user-3-line mr-1"></i> {{ number_format($curso->alunos,0,'.','.') }} alunos</span>
                        </div>
                        <div class="text-sm">
                            @if($curso->preco_promocional)
                                <span class="line-through text-slate-400 mr-1">R$ {{ number_format($curso->preco,2,',','.') }}</span>
                            @endif
                            <span class="font-semibold text-blue-700">R$ {{ number_format($curso->preco_final,2,',','.') }}</span>
                        </div>
                        <a href="{{ route('site.curso.detalhe',$curso->id) }}" class="btn btn-primary w-full">Ver Detalhes</a>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center text-slate-500">Nenhum curso encontrado.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $cursos->links() }}</div>
    </section>
@endsection
