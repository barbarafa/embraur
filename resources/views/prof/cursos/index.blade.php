@extends('layouts.app')
@section('title','Meus Cursos.php')

@section('content')
    <div class="container-page py-6">
        {{-- Título + ação --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Meus Cursos</h1>
                <p class="text-slate-600 text-sm">Gerencie seus cursos e acesse módulos/aulas.</p>
            </div>

            <a href="{{ route('prof.cursos.create') }}"
               class="inline-flex items-center gap-2 h-10 px-4 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                <span class="text-lg leading-none">＋</span>
                Criar Curso
            </a>
        </div>

        @include('prof._tabs')

        {{-- Barra de ferramentas --}}
        <div class="bg-white border rounded-xl p-3 shadow-sm mb-4 flex items-center gap-3">
            <div class="relative w-full md:w-80">
                <input type="text" placeholder="Buscar cursos..."
                       class="w-full h-10 rounded-md border-slate-300 pl-10 pr-3"
                       oninput="filtraCursos(this.value)">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔎</span>
            </div>
            <select class="h-10 rounded-md border-slate-300">
                <option>Ordenar por: Recentes</option>
                <option>Ordenar por: Título (A-Z)</option>
                <option>Ordenar por: Título (Z-A)</option>
            </select>
        </div>

        @if($cursos->count() === 0)
            {{-- EMPTY STATE no padrão Lovable --}}
            <div class="rounded-2xl border bg-white shadow-sm p-10 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center text-2xl">📘</div>
                <h3 class="text-lg font-semibold mt-4">Você ainda não criou cursos.</h3>
                <p class="text-slate-500 mt-1">Crie seu primeiro curso e comece a adicionar módulos e aulas.</p>
                <a href="{{ route('prof.cursos.create') }}"
                   class="inline-flex items-center gap-2 mt-4 h-10 px-5 rounded-md bg-blue-600 text-white hover:bg-blue-700 shadow-sm">
                    ＋ Criar Curso
                </a>
            </div>
        @else
            {{-- GRID DE CARDS --}}
            <div id="gridCursos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($cursos as $curso)
                    <div class="curso-card rounded-xl border bg-white shadow-sm overflow-hidden"
                         data-title="{{ Str::lower($curso->titulo) }}">
                        <div class="h-32 bg-slate-100"
                             style="background-image:url('{{ $curso->capa_path ? asset("storage/".$curso->capa_path) : "" }}');
                      background-size:cover;background-position:center"></div>

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="font-semibold truncate" title="{{ $curso->titulo }}">
                                        {{ $curso->titulo }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $curso->categoria?->nome ? 'Categoria: '.$curso->categoria->nome.' • ' : '' }}
                                        {{ $curso->nivel ? 'Nível: '.$curso->nivel.' • ' : '' }}
                                        {{ $curso->carga_horaria ? $curso->carga_horaria.'h' : '' }}
                                    </div>
                                </div>

                                <span class="text-xs px-2 py-1 rounded
                {{ $curso->publicado ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $curso->publicado ? 'Publicado' : 'Rascunho' }}
              </span>
                            </div>

                            @if($curso->resumo)
                                <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $curso->resumo }}</p>
                            @endif

                            <div class="mt-3 flex items-center gap-2">
                                <a href="{{ route('prof.cursos.edit',$curso) }}" class="btn btn-soft">Editar</a>
                                <a href="{{ route('prof.cursos.modulos.index',$curso) }}" class="btn btn-outline">Módulos</a>
                                <form method="POST" action="{{ route('prof.cursos.destroy',$curso) }}"
                                      onsubmit="return confirm('Remover curso \"{{ $curso->titulo }}\"?')"
                                class="ml-auto">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- paginação --}}
            <div class="mt-6">{{ $cursos->links() }}</div>
        @endif
    </div>

    {{-- filtro simples no front (sem recarregar) --}}
    <script>
        function filtraCursos(q){
            q = (q || '').toLowerCase();
            document.querySelectorAll('#gridCursos .curso-card').forEach(card=>{
                const title = card.getAttribute('data-title') || '';
                card.style.display = title.includes(q) ? '' : 'none';
            });
        }
    </script>
@endsection
