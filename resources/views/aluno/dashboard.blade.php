@extends('layouts.app')
@section('title','Área do Aluno - Meus Cursos')

@section('content')
    <section class="mx-auto container-page px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Meus Cursos</h1>
            <form action="{{ route('aluno.logout') }}" method="post">
                @csrf
                <button class="btn btn-outline">Sair</button>
            </form>
        </div>

        @if(session('ok'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('ok') }}</div>
        @endif

        @if($matriculas->isEmpty())
            <div class="card p-6 text-slate-600">Você ainda não tem cursos. Acesse o catálogo e matricule-se em um curso.</div>
        @else
            <div class="grid md:grid-cols-3 gap-4">
                @foreach($matriculas as $m)
                    <div class="card overflow-hidden">
                        <div class="h-32 bg-slate-100 flex items-center justify-center">
                            <i class="ri-image-2-line text-3xl text-slate-400"></i>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="text-[11px]">
                                <span class="badge border-blue-200 text-blue-700 bg-blue-50">{{ $m->curso->categoria->nome }}</span>
                                <span class="badge border-slate-200 text-slate-600 bg-slate-50 ml-1">{{ $m->curso->nivel }}</span>
                            </div>
                            <h3 class="font-semibold leading-snug">{{ $m->curso->titulo }}</h3>
                            <div class="text-xs text-slate-500"><i class="ri-time-line mr-1"></i>{{ $m->curso->carga_horaria }}h</div>
                            <a href="{{ route('site.curso.detalhe',$m->curso->slug) }}" class="btn btn-primary w-full">Ir para o curso</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
