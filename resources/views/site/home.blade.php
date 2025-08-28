@extends('layouts.app')
@section('title', 'Início')

@section('content')
    {{-- HERO --}}
    <section class="hero">
        <div class="hero-inner">
            <div class="max-w-3xl">
                <span class="chip-blue">Mais de 50.000 alunos certificados</span>
                <h1 class="mt-4 text-3xl md:text-5xl font-extrabold leading-tight">
                    Transforme sua carreira com <span class="text-yellow-300">cursos de qualidade</span>
                </h1>
                <p class="mt-4 text-blue-100">
                    Certificações reconhecidas pelo mercado, metodologia comprovada e suporte completo.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('site.cursos') }}" class="btn-soft">Explorar Cursos</a>
                    <a href="#demo" class="btn border border-white/40 text-white hover:bg-white/10">Assistir Demonstração</a>
                </div>
            </div>
        </div>
    </section>

    {{-- MÉTRICAS --}}
    <section>
        <div class="container-page grid grid-cols-2 md:grid-cols-4 gap-6 py-10 text-center">
            <div><div class="text-2xl font-extrabold">50.000+</div><div class="muted text-sm">Alunos certificados</div></div>
            <div><div class="text-2xl font-extrabold">200+</div><div class="muted text-sm">Cursos disponíveis</div></div>
            <div><div class="text-2xl font-extrabold">98%</div><div class="muted text-sm">Taxa de aprovação</div></div>
            <div><div class="text-2xl font-extrabold">24/7</div><div class="muted text-sm">Suporte disponível</div></div>
        </div>
    </section>

    {{-- CURSOS POPULARES --}}
    <section class="container-page py-12">
        <h2 class="text-2xl font-bold text-center">Cursos Populares</h2>
        <p class="muted text-center mt-2">Comece seu aprendizado hoje mesmo.</p>

        <div id="cursos-grid" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- skeletons iniciais --}}
            @for($i=0;$i<4;$i++)
                <div class="card p-4">
                    <div class="skeleton h-32"></div>
                    <div class="skeleton h-4 mt-4"></div>
                    <div class="skeleton h-4 mt-2 w-2/3"></div>
                    <div class="skeleton h-8 mt-6"></div>
                </div>
            @endfor
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('site.cursos') }}" class="btn-outline">Ver Todos os Cursos</a>
        </div>
    </section>

    {{-- POR QUE ESCOLHER --}}
    <section id="sobre" class="border-y">
        <div class="container-page py-12 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h3 class="text-xl font-bold">Por que escolher a EAD Pro?</h3>
                <ul class="mt-6 space-y-3 text-slate-700">
                    <li>✅ Certificações reconhecidas nacionalmente</li>
                    <li>✅ Metodologia comprovada de ensino</li>
                    <li>✅ Suporte especializado 24/7</li>
                    <li>✅ Plataforma intuitiva e responsiva</li>
                    <li>✅ Conteúdo sempre atualizado</li>
                    <li>✅ Acesso vitalício aos cursos</li>
                </ul>
                <a href="{{ route('site.cursos') }}" class="btn-primary mt-6">Saiba Mais</a>
            </div>
            <div class="card p-8 text-center">
                <div class="text-blue-700 font-semibold">Certificação Garantida</div>
                <p class="muted mt-2">Certificado digital com QR Code e validação pública.</p>
            </div>
        </div>
    </section>

    {{-- NEWSLETTER --}}
    <section id="contato" class="mt-12 bg-blue-700 text-white">
        <div class="container-page py-10 text-center">
            <h4 class="text-xl font-bold">Fique por dentro das novidades</h4>
            <p class="mt-2 text-blue-100">Receba promoções e novos cursos.</p>
            <form class="mt-6 flex flex-col md:flex-row items-center justify-center gap-3">
                <input type="email" placeholder="Seu e-mail" class="input w-72 text-slate-800">
                <button class="btn-soft">Inscrever-se</button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        async function carregarCursosPopulares(){
            const grid = document.getElementById('cursos-grid');
            try{
                const resp = await fetch('/api/cursos?ativos=1');
                const json = await resp.json();
                const items = json?.data ?? json ?? [];
                grid.innerHTML = '';
                (items || []).slice(0,4).forEach(curso=>{
                    const preco = (curso.modalidades?.[0]?.preco ?? 0).toFixed(2);
                    const el = document.createElement('div');
                    el.className = 'card card-hover p-4 flex flex-col';
                    el.innerHTML = `
        <div class="h-32 bg-slate-100 rounded-md overflow-hidden mb-3">
          ${curso.imagem_capa ? `<img src="${curso.imagem_capa}" class="w-full h-full object-cover">` : ''}
        </div>
        <div class="font-semibold line-clamp-2 min-h-[44px]">${curso.titulo ?? ''}</div>
        <div class="muted text-sm mt-1 line-clamp-3 min-h-[54px]">${curso.resumo ?? ''}</div>
        <div class="mt-4 flex items-center justify-between">
          <span class="text-blue-700 font-bold">R$ ${preco}</span>
          <a href="{{ route('site.cursos') }}" class="btn-primary text-xs">Ver Detalhes</a>
        </div>`;
                    grid.appendChild(el);
                });
            }catch(e){ console.error('Erro ao carregar cursos:', e); }
        }
        document.addEventListener('DOMContentLoaded', carregarCursosPopulares);
    </script>
@endpush
