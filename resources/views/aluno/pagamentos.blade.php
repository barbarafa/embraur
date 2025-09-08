@extends('layouts.app')
@section('title','Pagamentos')
@section('content')
    <section class="container-page mx-auto max-w-4xl py-8">
        <h1 class="text-xl font-semibold mb-4">Recibos de Pagamento</h1>
        <div class="space-y-3">
            @forelse($pagamentos as $p)
                <div class="rounded-lg border p-4 flex items-center justify-between">
                    <div>
                        <div class="font-medium">
                            {{ $p->matricula?->curso?->titulo ?? 'Compra de curso(s)' }}
                        </div>
                        <div class="text-sm text-slate-600">
                            {{ strtoupper($p->moeda) }} {{ number_format($p->valor,2,',','.') }} — {{ ucfirst($p->status) }}
                        </div>
                        @if($p->mp_payment_id)
                            <div class="text-xs text-slate-500">MP Payment ID: {{ $p->mp_payment_id }}</div>
                        @endif
                    </div>
                    {{-- botão para detalhar JSON se quiser --}}
                </div>
            @empty
                <div class="text-slate-500">Nenhum pagamento encontrado.</div>
            @endforelse
        </div>
    </section>
@endsection
