@extends('layouts.app')

@section('title','Embraur')

@section('content')
    {{-- Hero --}}
    <section class="bg-[url('https://images.unsplash.com/photo-1554200876-56c2f25224fa?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center">
        <div class="bg-[#3b4333]/80"> {{-- brand-900/80 --}}
            <div class="mx-auto container-page px-4 py-20 text-white">
                <span class="inline-block text-xs bg-white/20 px-2 py-1 rounded">Mais de 1000 alunos certificados</span>
                <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight">
                    Transforme sua carreira com<br><span class="text-[#c1cab0]">cursos de qualidade</span> {{-- brand-300 --}}
                </h1>
                <p class="mt-4 max-w-2xl text-[#e9eee3]">Certificações reconhecidas pelo mercado, metodologia comprovada e suporte completo para seu desenvolvimento profissional.</p> {{-- brand-100 --}}
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('site.cursos') }}" class="btn btn-primary">Explorar Cursos</a>

                </div>
            </div>
        </div>
    </section>

    {{-- Métricas --}}
{{--    <section class="bg-white">--}}
{{--        <div class="mx-auto container-page px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-6">--}}
{{--            <div class="card p-6 text-center">--}}
{{--                <div class="text-2xl font-bold">50.000+</div>--}}
{{--                <div class="text-slate-500 text-sm">Alunos certificados</div>--}}
{{--            </div>--}}
{{--            <div class="card p-6 text-center">--}}
{{--                <div class="text-2xl font-bold">200+</div>--}}
{{--                <div class="text-slate-500 text-sm">Cursos disponíveis</div>--}}
{{--            </div>--}}
{{--            <div class="card p-6 text-center">--}}
{{--                <div class="text-2xl font-bold">98%</div>--}}
{{--                <div class="text-slate-500 text-sm">Taxa de aprovação</div>--}}
{{--            </div>--}}
{{--            <div class="card p-6 text-center">--}}
{{--                <div class="text-2xl font-bold">24/7</div>--}}
{{--                <div class="text-slate-500 text-sm">Suporte disponível</div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    {{-- Cursos Populares --}}
    <section class="py-12">
        <div class="mx-auto container-page px-4">
            <h2 class="text-2xl font-bold text-center">CURSOS POPULARES</h2>
            <p class="text-center text-slate-600 mt-1">Descubra os cursos mais procurados.</p>

            <div class="grid md:grid-cols-4 gap-4 mt-6">
                @foreach ($populares as $curso)
                    <div class="card h-full flex flex-col overflow-hidden">
                        <div class="h-32 bg-slate-100 overflow-hidden">
                            <img src="{{ $curso->imagem_capa_url }}"
                                 alt="Capa do curso {{ $curso->titulo }}"
                                 class="w-full h-full object-cover">
                        </div>

                        <div class="p-4 flex-1 flex flex-col gap-2">
                            <div class="flex items-center justify-between text-xs">
                                {{-- badge com paleta --}}
                                <span class="badge border-[#d5dcc9] text-[#606d50] bg-[#f5f7f2]">{{ $curso->categoria->nome }}</span> {{-- 200 / 700 / 50 --}}
                                <span class="badge border-slate-200 text-slate-600 bg-slate-50">{{ $curso->nivel }}</span>
                            </div>

                            {{-- TÍTULO com clamp de 2 linhas + tooltip --}}
                            <h3 class="font-semibold leading-snug line-clamp-2" title="{{ $curso->titulo }}">
                                {{ $curso->titulo }}
                            </h3>

                            <div class="text-xs text-slate-500 flex items-center gap-3">
                                <span><i class="ri-time-line mr-1"></i> {{ $curso->carga_horaria_total }}h</span>
                            </div>

                            <div class="text-sm">
                                @if($curso->preco_original)
                                    <span class="line-through text-slate-400 mr-1">
                                        R$ {{ number_format($curso->preco_original,2,',','.') }}
                                    </span>
                                @endif
                                <span class="font-semibold text-[#606d50]">
                                    R$ {{ number_format($curso->preco,2,',','.') }}
                                </span> {{-- brand-700 --}}
                            </div>

                            <div class="mt-auto"></div>
                            <a href="{{ route('site.curso.detalhe',$curso->id) }}" class="btn btn-primary w-full">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('site.cursos') }}" class="btn btn-outline">VER TODOS OS CURSOS</a>
            </div>
        </div>
    </section>

    {{-- Parceiros (dinâmico) --}}
    <section class="bg-white">
        <div class="mx-auto container-page px-4 py-12">
            <h2 class="text-2xl font-bold text-center">EMPRESAS QUE CONFIAM NO NOSSO TRABALHO</h2>

            @if($parceiros->isEmpty())
                <p class="text-center text-slate-500 mt-4">Em breve novos parceiros por aqui.</p>
            @else
                <div class="mt-6 relative overflow-hidden">
                    <div class="parceiros-track flex items-center gap-12 will-change-transform"
                         style="animation-duration: {{ max(18, 6 + $parceiros->count()*3) }}s">
                        {{-- Linha A --}}
                        <div class="parceiros-row flex items-center gap-12 shrink-0">
                            @foreach ($parceiros as $p)
                                @php $src = asset('storage/images/parceiros/'.$p['logo']); @endphp
                                @if(!empty($p['url']))
                                    <a href="{{ $p['url'] }}" target="_blank" rel="noopener" class="block opacity-90 hover:opacity-100">
                                        <img src="{{ $src }}" alt="{{ $p['alt'] ?? 'Parceiro' }}" class="h-12 object-contain" loading="lazy">
                                    </a>
                                @else
                                    <img src="{{ $src }}" alt="{{ $p['alt'] ?? 'Parceiro' }}" class="h-12 object-contain" loading="lazy">
                                @endif
                            @endforeach
                        </div>

                        {{-- Linha B (duplicada) --}}
                        <div class="parceiros-row flex items-center gap-12 shrink-0">
                            @foreach ($parceiros as $p)
                                @php $src = asset('storage/images/parceiros/'.$p['logo']); @endphp
                                @if(!empty($p['url']))
                                    <a href="{{ $p['url'] }}" target="_blank" rel="noopener" class="block opacity-90 hover:opacity-100">
                                        <img src="{{ $src }}" alt="{{ $p['alt'] ?? 'Parceiro' }}" class="h-12 object-contain" loading="lazy">
                                    </a>
                                @else
                                    <img src="{{ $src }}" alt="{{ $p['alt'] ?? 'Parceiro' }}" class="h-12 object-contain" loading="lazy">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Pontinhos decorativos (opcional) --}}
                <div class="flex justify-center mt-6 gap-2">
                    @for($i=0; $i<5; $i++)
                        <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span>
                    @endfor
                </div>
            @endif
        </div>
    </section>

    {{-- SOBRE --}}
    <section id="sobre" class="bg-[#f5f7f2]">
        <div class="mx-auto container-page px-4 py-12 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl font-bold">Sobre</h2>
                <p class="mt-3 text-slate-700">
                    Cursos em segurança do trabalho.
                    Somos uma plataforma especializada em cursos de capacitação voltados para a área de Segurança do Trabalho,
                    com foco nas principais Normas Regulamentadoras exigidas pelo Ministério do Trabalho.
                    Conteúdo atualizado, didático e certificado para garantir a qualificação e segurança dos profissionais.
                </p>

                <h3 class="mt-6 font-semibold">Nossos principais cursos</h3>
                <ul class="mt-2 space-y-2 text-slate-700">
                    <li><span class="font-medium">NR 10 – Segurança em Instalações e Serviços com Eletricidade:</span>
                        capacita profissionais para atuarem com segurança em instalações elétricas.</li>
                    <li><span class="font-medium">NR 10 SEP – Sistema Elétrico de Potência:</span>
                        complementar ao NR 10 para quem trabalha diretamente em SEP e suas proximidades.</li>
                    <li><span class="font-medium">NR 35 – Trabalho em Altura:</span>
                        técnicas e procedimentos seguros para atividades acima de 2 metros.</li>
                </ul>
            </div>

            <div class="rounded-xl overflow-hidden shadow border bg-white p-6">
                <img src="https://blog.mrhgestao.com.br/wp-content/uploads/2018/03/178698-tecnico-em-seguranca-do-trabalho-conheca-o-mercado-no-brasil.jpg"
                     alt="Treinamento de segurança do trabalho" class="w-full h-56 object-cover rounded-lg">
                <div class="mt-4 text-sm text-slate-600">
                    Certificados reconhecidos • Conteúdo atualizado • Acesso 24/7
                </div>
            </div>
        </div>
    </section>

    {{-- CONTATO --}}
    <section id="contato" class="bg-white">
        <div class="mx-auto container-page px-4 py-12">
            <h2 class="text-2xl font-bold text-center">Fale Conosco</h2>
            <p class="text-center text-slate-600 mt-1">Tire suas dúvidas e fale com nosso time.</p>

            <div class="mt-8 grid md:grid-cols-3 gap-6">
                <div class="card p-6">
                    <div class="text-sm text-slate-500">WhatsApp</div>
                    <a href="https://wa.me/554831983198?text=Olá!%20Preciso%20de%20suporte%20no%20site%20Embraur."
                       target="_blank" rel="noopener"
                       class="mt-1 block font-semibold text-[#606d50] hover:underline">
                        (48) 3198-3198
                    </a>
                </div>

                <div class="card p-6">
                    <div class="text-sm text-slate-500">E-mail</div>
                    <a href="mailto:embraur@embraur.com.br " class="mt-1 block font-semibold text-[#606d50] hover:underline">
                        embraur@embraur.com.br
                    </a>
                </div>

                <div class="card p-6">
                    <div class="text-sm text-slate-500">Redes Sociais</div>
                    <a href="https://www.instagram.com/embraur" target="_blank" rel="noopener"
                       class="mt-1 inline-flex items-center gap-2 font-semibold text-[#606d50] hover:underline">
                        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm11 2a1 1 0 1 1 0 2 1 1 0 0 1 0-2ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 .002 6.002A3 3 0 0 0 12 9Z"/></svg>
                        Instagram
                    </a>
                </div>
            </div>
        </div>
    </section>



    @php
        $miniCart = collect(session('cart', []));
        $miniTotal = $miniCart->sum('preco');
    @endphp

    {{-- MINI-CARRINHO --}}
    <button id="miniCartToggle"
            class="fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 px-3 py-2 rounded-full shadow border bg-white hover:bg-slate-50">
        <span>🛒</span>
        <span>Carrinho</span>
        <span data-cart-badge
              class="min-w-[20px] h-5 px-1 rounded-full bg-[#778663] text-white text-[11px] grid place-items-center {{ $miniCart->isEmpty() ? 'hidden' : '' }}">
            {{ $miniCart->count() }}
        </span> {{-- brand-600 --}}
    </button>

    <div id="miniCartPanel"
         class="fixed bottom-20 right-5 z-40 w-[320px] max-h-[70vh] overflow-auto rounded-xl border bg-white shadow-lg hidden">
        <div class="p-4 border-b flex items-center justify-between">
            <div class="font-semibold">Seu carrinho</div>
            <button class="text-slate-500 hover:text-slate-700" onclick="document.getElementById('miniCartPanel').classList.add('hidden')">✕</button>
        </div>

        <div class="p-3">
            @if($miniCart->isEmpty())
                <div class="text-sm text-slate-500 p-3">Seu carrinho está vazio.</div>
            @else
                <ul class="space-y-2">
                    @foreach($miniCart as $it)
                        <li class="flex items-center justify-between rounded border p-2">
                            <div class="pr-2">
                                <div class="text-sm font-medium truncate max-w-[180px]">{{ $it['titulo'] }}</div>
                                <div class="text-xs text-slate-500">R$ {{ number_format($it['preco'] ?? 0,2,',','.') }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="post" action="{{ route('checkout.cart.remove', $it['id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 hover:underline">remover</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-3 border-t pt-3 flex items-center justify-between">
                    <span class="text-sm text-slate-600">Total</span>
                    <span class="font-semibold">R$ {{ number_format($miniTotal,2,',','.') }}</span>
                </div>

                <div class="mt-3 grid grid-cols-2 gap-2">
                    <a href="{{ route('checkout.cart') }}" class="btn btn-outline text-center">Ver carrinho</a>
                    <a href="{{ route('checkout.cart') }}" class="btn btn-primary text-center">Finalizar</a>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function(){
            const btn = document.getElementById('miniCartToggle');
            const panel = document.getElementById('miniCartPanel');
            btn?.addEventListener('click', ()=> panel.classList.toggle('hidden'));

            async function refreshCartBadge() {
                try {
                    const res = await fetch("{{ route('checkout.cart.count') }}", { headers: {'Accept':'application/json'}, cache: 'no-store' });
                    const data = await res.json();
                    const n = Number(data?.count || 0);
                    document.querySelectorAll('[data-cart-badge]').forEach(b=>{
                        b.textContent = String(n);
                        b.classList.toggle('hidden', n === 0);
                    });
                } catch(e){}
            }
            refreshCartBadge();
        })();
    </script>
@endsection

<style>
    @keyframes parceiros-scroll {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .parceiros-track{
        width: max-content;
        animation-name: parceiros-scroll;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    .parceiros-track:hover{ animation-play-state: paused; }
</style>
