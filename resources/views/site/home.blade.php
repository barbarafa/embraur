@extends('layouts.app')

@section('title','EAD Pro - Início')

@section('content')
    {{-- Hero --}}
    <section class="bg-[url('https://images.unsplash.com/photo-1554200876-56c2f25224fa?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center">
        <div class="bg-blue-900/80">
            <div class="mx-auto container-page px-4 py-20 text-white">
                <span class="inline-block text-xs bg-white/20 px-2 py-1 rounded">Mais de 50.000 alunos certificados</span>
                <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight">
                    Transforme sua carreira com<br><span class="text-blue-300">cursos de qualidade</span>
                </h1>
                <p class="mt-4 max-w-2xl text-blue-100">Certificações reconhecidas pelo mercado, metodologia comprovada e suporte completo para seu desenvolvimento profissional.</p>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('site.cursos') }}" class="btn btn-primary">Explorar Cursos</a>
                    <a href="#" class="btn btn-outline"><i class="ri-play-fill mr-1"></i> Assistir Demonstração</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Métricas --}}
    <section class="bg-white">
        <div class="mx-auto container-page px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="card p-6 text-center">
                <div class="text-2xl font-bold">50.000+</div>
                <div class="text-slate-500 text-sm">Alunos certificados</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-2xl font-bold">200+</div>
                <div class="text-slate-500 text-sm">Cursos disponíveis</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-2xl font-bold">98%</div>
                <div class="text-slate-500 text-sm">Taxa de aprovação</div>
            </div>
            <div class="card p-6 text-center">
                <div class="text-2xl font-bold">24/7</div>
                <div class="text-slate-500 text-sm">Suporte disponível</div>
            </div>
        </div>
    </section>

    {{-- Cursos Populares --}}
    <section class="py-12">
        <div class="mx-auto container-page px-4">
            <h2 class="text-2xl font-bold text-center">Cursos Populares</h2>
            <p class="text-center text-slate-600 mt-1">Descubra os cursos mais procurados.</p>

            <div class="grid md:grid-cols-4 gap-4 mt-6">
                @foreach ($populares as $curso)
                    <div class="card overflow-hidden">
                        <div class="h-32 bg-slate-100 flex items-center justify-center">
                            <i class="ri-image-2-line text-3xl text-slate-400"></i>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="badge border-blue-200 text-blue-700 bg-blue-50">{{ $curso->categoria->nome }}</span>
                                <span class="badge border-slate-200 text-slate-600 bg-slate-50">{{ $curso->nivel }}</span>
                            </div>
                            <h3 class="font-semibold leading-snug">{{ $curso->titulo }}</h3>
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
                            <a href="{{ route('site.curso.detalhe',$curso->slug) }}" class="btn btn-primary w-full">Ver Detalhes</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('site.cursos') }}" class="btn btn-outline">Ver Todos os Cursos</a>
            </div>
        </div>
    </section>

    {{-- Newsletter faixa azul --}}
    <section class="bg-blue-700">
        <div class="mx-auto container-page px-4 py-10 text-white">
            <h3 class="text-2xl font-bold">Fique por dentro das novidades</h3>
            <p class="text-blue-100">Receba em primeira mão informações sobre novos cursos e promoções.</p>
            <div class="mt-3 flex gap-2">
                <input type="email" class="w-full md:w-80 px-3 py-2 rounded bg-white text-slate-800" placeholder="Digite seu e-mail">
                <button class="btn btn-primary bg-white text-blue-700 hover:bg-slate-100">Inscrever-se</button>
            </div>
        </div>
    </section>
@endsection
