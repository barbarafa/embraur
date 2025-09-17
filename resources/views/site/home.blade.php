@extends('layouts.app')

@section('title','Embraur')

@section('content')
    {{-- Hero --}}
    <section class="bg-[url('https://images.unsplash.com/photo-1554200876-56c2f25224fa?q=80&w=1920&auto=format&fit=crop')] bg-cover bg-center">
        <div class="bg-[#3b4333]/80"> {{-- brand-900/80 --}}
            <div class="mx-auto container-page px-4 py-20 text-white">
                <span class="inline-block text-xs bg-white/20 px-2 py-1 rounded">Mais de 50.000 alunos certificados</span>
                <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight">
                    Transforme sua carreira com<br><span class="text-[#c1cab0]">cursos de qualidade</span> {{-- brand-300 --}}
                </h1>
                <p class="mt-4 max-w-2xl text-[#e9eee3]">Certificações reconhecidas pelo mercado, metodologia comprovada e suporte completo para seu desenvolvimento profissional.</p> {{-- brand-100 --}}
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
                <a href="{{ route('site.cursos') }}" class="btn btn-outline">Ver Todos os Cursos</a>
            </div>
        </div>
    </section>

    {{-- Newsletter (faixa) --}}
    <section class="bg-[#606d50]"> {{-- brand-700 --}}
        <div class="mx-auto container-page px-4 py-10 text-white">
            <h3 class="text-2xl font-bold">Fique por dentro das novidades</h3>
            <p class="text-[#e9eee3]">Receba em primeira mão informações sobre novos cursos e promoções.</p> {{-- brand-100 --}}
            <div class="mt-3 flex gap-2">
                <input type="email" class="w-full md:w-80 px-3 py-2 rounded bg-white text-slate-800" placeholder="Digite seu e-mail">
                <button class="btn btn-primary bg-white text-[#606d50] hover:bg-slate-100">Inscrever-se</button> {{-- texto brand-700 --}}
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
