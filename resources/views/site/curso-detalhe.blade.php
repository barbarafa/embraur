@extends('layouts.app')
@section('title', $curso->titulo)

@section('head')
    <style>
        /* remove o marcador padrão e gira a setinha quando aberto */
        [data-acc] summary { list-style: none; }
        [data-acc] summary::-webkit-details-marker { display: none; }
        details[open] .acc-arrow { transform: rotate(180deg); }
    </style>
@endsection


@section('content')
    <section class="mx-auto container-page px-4 py-8">


        {{-- Cabeçalho com breadcrumb simplificado --}}
        <div class="flex items-center justify-between mb-3">
            <nav class="text-sm text-slate-500">
                <a href="{{ route('site.cursos') }}" class="hover:underline">Cursos</a>
                <span class="mx-1">/</span>
                <span>{{ $curso->titulo }}</span>
            </nav>

            {{-- Carrinho (link + badge) --}}
            <a href="{{ route('checkout.cart') }}" class="relative inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-md border hover:bg-slate-50">
                <span>🛒</span>
                <span>Carrinho</span>
                <span
                    data-cart-badge
                    class="absolute -top-2 -right-2 hidden min-w-[20px] h-5 px-1 rounded-full bg-blue-600 text-white text-[11px] grid place-items-center"
                >0</span>
            </a>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Coluna esquerda (mídia + tabs + módulos) --}}
            <div class="lg:col-span-8 space-y-4">
                {{-- Capa/Player --}}
                <div class="rounded-lg border bg-black aspect-video overflow-hidden">
                    @if($curso->video_introducao)
                        <iframe class="w-full h-full" src="{{ $curso->video_introducao }}" allowfullscreen></iframe>
                    @else
                        <img src="{{ $curso->imagem_capa_url }}" class="w-full h-full object-cover opacity-70" alt="Capa do curso">
                    @endif
                </div>

                {{-- Tabs simples (Conteúdo / Sobre / Instrutor / Avaliações) --}}
                <div class="rounded-lg border bg-white">
                    <div class="flex text-sm">
                        <button class="px-4 py-2 border-b-2 border-blue-600 text-blue-700 font-medium">Conteúdo</button>
{{--                        <button class="px-4 py-2 text-slate-500">Sobre</button>--}}
{{--                        <button class="px-4 py-2 text-slate-500">Instrutor</button>--}}
{{--                        <button class="px-4 py-2 text-slate-500">Avaliações</button>--}}
                    </div>

                    {{-- Módulos/Aulas --}}
                    {{-- Módulos/Aulas (versão simples, sem JS) --}}
                    <div class="p-4">
                        <h3 class="font-semibold mb-2">Módulos do Curso</h3>

                        <div class="space-y-3">
                            @forelse($curso->modulos->sortBy('ordem') as $m)
                                <details class="rounded-lg border bg-white" {{ $loop->first ? 'open' : '' }} data-acc>
                                    <summary class="flex items-center justify-between px-4 py-3 cursor-pointer select-none hover:bg-slate-50">
                                        <div class="text-left">
                                            <div class="font-medium">Módulo {{ $loop->iteration }}: {{ $m->titulo }}</div>
                                            @if($m->descricao)
                                                <div class="text-xs text-slate-500">{{ $m->descricao }}</div>
                                            @endif
                                        </div>
                                        <span class="text-slate-500 text-sm inline-flex items-center gap-1">
            <span>Ver aulas</span>
            <svg class="w-4 h-4 acc-arrow transition-transform" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </span>
                                    </summary>

                                    @if($m->aulas->count())
                                        <div class="px-4 pb-3">
                                            @foreach($m->aulas->sortBy('ordem') as $a)
                                                <div class="flex items-center justify-between rounded border p-3 mb-2 hover:bg-slate-50">
                                                    <div class="truncate">
                                                        <div class="text-sm">{{ $a->titulo }}</div>
                                                        @if($a->descricao)
                                                            <div class="text-xs text-slate-500 truncate">{{ $a->descricao }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-slate-500">{{ $a->duracao_minutos }} min</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </details>
                            @empty
                                <div class="text-sm text-slate-500">Este curso ainda não possui módulos.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

            {{-- Coluna direita (card de compra) --}}
            <aside class="lg:col-span-4">
                <div class="rounded-lg border p-4 bg-white">
                    @php
                        $temPromo = filled($curso->preco_original) && (float)$curso->preco_original > (float)$curso->preco;
                    @endphp

                    <div class="space-y-2">
                        @if($temPromo)
                            <div class="text-slate-400 text-sm line-through">
                                R$ {{ number_format($curso->preco_original,2,',','.') }}
                            </div>
                        @endif
                        <div class="text-2xl font-bold">
                            R$ {{ number_format($curso->preco ?? 0, 2, ',', '.') }}
                        </div>
                        <div class="text-xs text-slate-500">Acesso vitalício</div>
                    </div>

                    <div class="mt-4 space-y-2">
                        @php
                            $alunoLogado = auth('aluno')->check() || session()->has('aluno_id');
                        @endphp

                        @if($alunoLogado)
                            {{-- Já logado → manda direto pro checkout --}}
                            <a href="{{ route('checkout.start', $curso->id) }}" class="btn btn-primary w-full">Comprar agora</a>

                        @else
                            {{-- Não logado → vai para cadastro com intended + curso --}}
                            <a
                                href="{{ route('aluno.register') }}?intended={{ urlencode(route('checkout.start', $curso->id)) }}&curso={{ $curso->id }}"
                                class="btn btn-primary w-full"
                            >
                                Comprar agora
                            </a>
                        @endif

                        <form id="addToCartForm" method="post" action="{{ route('checkout.cart.add', $curso->id) }}" class="mt-2">
                            @csrf
                            <button class="btn btn-soft w-full">Adicionar ao Carrinho</button>
                        </form>
                    </div>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex items-center gap-2"><span>✅</span> Certificado digital reconhecido</div>
                        <div class="flex items-center gap-2"><span>✅</span> Acesso vitalício ao conteúdo</div>
                        <div class="flex items-center gap-2"><span>✅</span> Material complementar em PDF</div>
                        <div class="flex items-center gap-2"><span>✅</span> Suporte especializado</div>
                        <div class="flex items-center gap-2"><span>✅</span> Garantia de 7 dias</div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection

<script>
    (function () {
        const countUrl = "{{ route('checkout.cart.count') }}";
        const addUrl   = "{{ route('checkout.cart.add', $curso->id) }}";
        const token    = "{{ csrf_token() }}";

        function setBadges(count) {
            document.querySelectorAll('[data-cart-badge]').forEach(badge => {
                const n = Number(count) || 0;
                badge.textContent = String(n);
                // mostra badge somente se > 0
                if (n > 0) badge.classList.remove('hidden'); else badge.classList.add('hidden');
            });
        }

        async function refreshBadge() {
            try {
                const res = await fetch(countUrl, {
                    headers: { 'Accept': 'application/json' },
                    cache: 'no-store' // evita pegar do cache
                });
                const data = await res.json();
                setBadges(data?.count ?? 0);
            } catch (e) { /* silencia */ }
        }

        const form = document.getElementById('addToCartForm');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                try {
                    const res = await fetch(addUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        cache: 'no-store'
                    });

                    // Se o backend redirecionar (não retornou JSON), force um refresh do badge e dá feedback
                    const contentType = res.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        await refreshBadge();
                        toast('Curso adicionado ao carrinho.');
                        return;
                    }

                    const data = await res.json();
                    if (data?.ok) {
                        setBadges(data.count ?? 0);
                        toast(data.msg || 'Curso adicionado ao carrinho.');
                    } else {
                        throw new Error('Resposta inválida');
                    }
                } catch (err) {
                    toast('Falha ao adicionar. Tente novamente.', true);
                }
            });
        }

        function toast(text, isError = false) {
            const el = document.createElement('div');
            el.textContent = text;
            el.className =
                'fixed bottom-4 left-1/2 -translate-x-1/2 px-3 py-2 rounded text-white text-sm shadow ' +
                (isError ? 'bg-red-600' : 'bg-blue-600');
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 1800);
        }

        // Atualiza ao carregar a tela
        refreshBadge();

        // (Opcional) revalidar periodicamente
        // setInterval(refreshBadge, 15000);
    })();
</script>


