@extends('layouts.app')
@section('title','Meu Carrinho')

@section('content')
    <section class="container-page mx-auto py-8 max-w-4xl">
        <h1 class="text-xl font-bold mb-4">Meu Carrinho</h1>

        @if(empty($itens))
            <div class="rounded border bg-white p-4 text-slate-600">
                Seu carrinho está vazio.
            </div>
        @else
            @php
                $total = collect($itens)->sum('preco');
                $cupomAtual = request('cupom', session('cupom')); // mostra o que já estiver aplicado
            @endphp

            <div class="space-y-3">
                @foreach($itens as $i)
                    <div class="flex items-center justify-between rounded border bg-white p-3">
                        <div class="truncate">
                            <div class="font-medium">{{ $i['titulo'] }}</div>
                            <div class="text-sm text-slate-500">R$ {{ number_format($i['preco'],2,',','.') }}</div>
                        </div>
                        <form method="post" action="{{ route('checkout.cart.remove',$i['id']) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm hover:underline">Remover</button>
                        </form>
                    </div>
                @endforeach
            </div>

            {{-- Cupom (opcional) + feedback --}}
            <div class="mt-4 rounded border bg-white p-4">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="flex-1">
                        <label class="text-sm font-medium">Cupom (opcional)</label>
                        <input
                            id="cupomInput"
                            type="text"
                            placeholder="EXEMPLO10"
                            value="{{ strtoupper((string)session('cupom')) }}"
                            class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                    </div>
                    <div class="flex items-end">
                        <button id="btnAplicarCupom" class="btn h-10">Aplicar</button>
                    </div>
                </div>

                <div id="cupomMsg" class="mt-2 text-sm hidden"></div>
            </div>

            {{-- Resumo com subtotal/desconto/total (atualiza em tempo real) --}}
            <div class="mt-4 rounded border bg-white p-4">
                <div class="flex items-center justify-between">
                    <div class="space-y-1 text-sm">
                        <div>Subtotal: <strong id="subtotalSpan">R$ {{ number_format($total,2,',','.') }}</strong></div>
                        <div class="{{ session('cupom') ? '' : 'opacity-70' }}">Desconto: <strong id="descontoSpan">R$ 0,00</strong></div>
                    </div>
                    <div class="text-lg">Total: <strong id="totalSpan">R$ {{ number_format($total,2,',','.') }}</strong></div>
                </div>

                @php $alunoLogado = auth('aluno')->check() || session()->has('aluno_id'); @endphp
                <div class="mt-3 flex justify-end">
                    @if($alunoLogado)
                        <form id="finalizarForm" method="post" action="{{ route('checkout.start.cart') }}">
                            @csrf
                            <input type="hidden" name="cupom" id="cupomHidden" value="{{ strtoupper((string)session('cupom')) }}">
                            <button class="btn btn-primary">Finalizar compra</button>
                        </form>
                    @else
                        <a class="btn btn-primary" href="{{ route('aluno.register') }}?intended={{ urlencode(route('checkout.cart')) }}">
                            Entrar / Cadastrar para pagar
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <script>
            (function(){
                const btn   = document.getElementById('btnAplicarCupom');
                const input = document.getElementById('cupomInput');
                const msg   = document.getElementById('cupomMsg');
                const subtotalSpan = document.getElementById('subtotalSpan');
                const descontoSpan = document.getElementById('descontoSpan');
                const totalSpan    = document.getElementById('totalSpan');
                const hiddenCupom  = document.getElementById('cupomHidden');

                function realBRL(n){
                    return (n || 0).toLocaleString('pt-BR', { style:'currency', currency:'BRL' });
                }

                async function aplicar() {
                    const codigo = (input.value || '').trim().toUpperCase();
                    console.log('aplicar')

                    msg.classList.add('hidden');
                    msg.textContent = '';

                    if (!codigo) {
                        msg.className = 'mt-2 text-sm text-red-600';
                        msg.textContent = 'Informe um código de cupom.';
                        msg.classList.remove('hidden');
                        return;
                    }

                    btn.disabled = true;

                    try {
                        const url = new URL('{{ route('checkout.cupom.validar') }}', window.location.origin);
                        url.searchParams.set('codigo', codigo);

                        const res = await fetch(url, { headers:{'Accept':'application/json'}, cache:'no-store' });
                        const data = await res.json();

                        if (!res.ok || !data.ok) {
                            msg.className = 'mt-2 text-sm text-red-600';
                            msg.textContent = data?.mensagem || 'Cupom inválido.';
                            msg.classList.remove('hidden');
                            return;
                        }

                        // sucesso: atualiza totais na tela e guarda hidden (para o POST do finalizar)
                        subtotalSpan.textContent = realBRL(data.subtotal);
                        descontoSpan.textContent = realBRL(data.desconto);
                        totalSpan.textContent    = realBRL(data.total);
                        if (hiddenCupom) hiddenCupom.value = data.codigo;

                        msg.className = 'mt-2 text-sm text-green-700';
                        msg.textContent = data.mensagem || 'Cupom aplicado.';
                        msg.classList.remove('hidden');

                    } catch (e) {
                        msg.className = 'mt-2 text-sm text-red-600';
                        msg.textContent = 'Não foi possível validar o cupom. Tente novamente.';
                        msg.classList.remove('hidden');
                    } finally {
                        btn.disabled = false;
                    }
                }

                btn?.addEventListener('click', (e)=>{ e.preventDefault(); aplicar(); });
            })();
        </script>
@endsection

            {{-- JS para validar e refletir na tela --}}

