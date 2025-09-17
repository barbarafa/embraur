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
            @php $total = collect($itens)->sum('preco'); @endphp

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

            <div class="mt-4 rounded border bg-white p-4 flex items-center justify-between">
                <div class="text-lg">Total: <strong>R$ {{ number_format($total,2,',','.') }}</strong></div>

                @php $alunoLogado = auth('aluno')->check() || session()->has('aluno_id'); @endphp

                @if($alunoLogado)
                    <form method="post" action="{{ route('checkout.start.cart') }}">
                        @csrf
                        <button class="btn btn-primary">Finalizar compra</button>
                    </form>
                @else
                    <a
                        class="btn btn-primary"
                        href="{{ route('aluno.register') }}?intended={{ urlencode(route('checkout.cart')) }}"
                    >
                        Entrar / Cadastrar para pagar
                    </a>
                @endif
            </div>
        @endif
    </section>
@endsection
