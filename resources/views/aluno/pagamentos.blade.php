@extends('layouts.app')
@section('title','Meus Pagamentos')

@section('content')
    <div class="container-page py-6">
        @include('aluno._tabs', ['aluno' => $aluno])

        @php
            $totalPedidos  = $pedidos->count();
            $totalPago     = $pedidos->filter(fn($p) => in_array(strtolower($p->status ?? ''), ['pago','aprovado','approved']))->sum('valor_total');
            $badgeClass = function ($status) {
                $s = strtolower($status ?? '');
                return match($s) {
                    'pago', 'aprovado', 'approved' => 'bg-green-100 text-green-700',
                    'pendente'                     => 'bg-amber-100 text-amber-700',
                    'cancelado', 'rejected'        => 'bg-red-100 text-red-700',
                    'estornado'                    => 'bg-slate-100 text-slate-700',
                    default                        => 'bg-slate-100 text-slate-700',
                };
            };
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
            {{-- Lista de pedidos (mantém o mesmo padrão do "Continue Aprendendo") --}}
            <div class="lg:col-span-2 rounded-xl border bg-white p-4 shadow-sm">
                <h3 class="text-lg font-semibold mb-3">Pedidos e Pagamentos</h3>

                <div class="space-y-3">
                    @forelse($pedidos as $pd)
                        @php
                            $dtPedido    = optional($pd->data_pedido)->format('d/m/Y H:i');
                            $dtPagamento = optional($pd->data_pagamento)->format('d/m/Y H:i');
                        @endphp

                        <div class="rounded-lg border p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <div class="text-sm font-medium truncate">Pedido #{{ $pd->id }}</div>
                                        <span class="text-[11px] px-2 py-0.5 rounded-full {{ $badgeClass($pd->status) }}">
                                        {{ ucfirst($pd->status ?? '—') }}
                                    </span>
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        Feito em <span class="font-medium">{{ $dtPedido ?? '—' }}</span>
                                        @if($dtPagamento)
                                            <span class="mx-2 text-slate-300">•</span>
                                            Pago em <span class="font-medium">{{ $dtPagamento }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="text-right shrink-0">
                                    <div class="text-[11px] uppercase tracking-wide text-slate-500">Valor</div>
                                    <div class="text-xl font-extrabold">R$ {{ number_format($pd->valor_total ?? 0,2,',','.') }}</div>
                                </div>
                                @php $statusLower = strtolower($pd->status ?? ''); @endphp
                                @if(!in_array($statusLower, ['pago','aprovado','approved']))
                                    <div class="mt-3">
                                        <a href="{{ route('checkout.cart.from_pedido', $pd->id) }}"
                                           class="btn btn-primary">
                                            Reabrir no carrinho
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Itens do pedido (colapso nativo, visual igual ao resto) --}}
                            @if($pd->itens?->count())
                                <details class="mt-3">
                                    <summary class="cursor-pointer text-sm text-slate-700 hover:underline">Ver itens do pedido ({{ $pd->itens->count() }})</summary>
                                    <div class="mt-2 rounded-lg border bg-slate-50">
                                        <ul class="divide-y">
                                            @foreach($pd->itens as $item)
                                                <li class="px-3 py-2 flex items-start justify-between gap-3">
                                                    <div class="min-w-0">
                                                        <div class="font-medium truncate">
                                                            {{ $item->curso->titulo ?? $item->curso->nome ?? 'Item' }}
                                                        </div>
                                                        <div class="text-xs text-slate-500">
                                                            Qtd: {{ $item->quantidade ?? 1 }}
                                                            <span class="mx-2 text-slate-300">•</span>
                                                            Preço: R$ {{ number_format($item->preco_unitario ?? 0,2,',','.') }}
                                                        </div>
                                                    </div>
                                                    <div class="font-semibold whitespace-nowrap">
                                                        R$ {{ number_format($item->subtotal ?? 0,2,',','.') }}
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="px-3 py-2 flex items-center justify-between">
                                            <span class="text-sm text-slate-600">Total do pedido</span>
                                            <span class="font-semibold">
                                            R$ {{ number_format($pd->valor_total ?? $pd->itens->sum('subtotal') ?? 0,2,',','.') }}
                                        </span>
                                        </div>
                                    </div>
                                </details>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum pedido encontrado.</p>
                    @endforelse
                </div>
            </div>

            {{-- Resumo (coluna da direita, como "Atividades Recentes") --}}
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <h3 class="text-lg font-semibold mb-3">Resumo</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center justify-between">
                        <span class="text-slate-600">Total de pedidos</span>
                        <span class="font-semibold">{{ $totalPedidos }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-slate-600">Total pago</span>
                        <span class="font-semibold">R$ {{ number_format($totalPago,2,',','.') }}</span>
                    </li>
                    @php
                        $ultimo = $pedidos->sortByDesc('data_pagamento')->firstWhere('data_pagamento', '!=', null);
                    @endphp
                    <li class="flex items-center justify-between">
                        <span class="text-slate-600">Último pagamento</span>
                        <span class="font-medium">
                        {{ optional($ultimo?->data_pagamento)->format('d/m/Y H:i') ?? '—' }}
                    </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
