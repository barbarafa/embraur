@extends('layouts.app')

@section('content')
    <div class="container-page py-6">
        @include('aluno._tabs', ['aluno' => $aluno, 'stats' => ['cursos'=>0,'concluidos'=>0,'horas'=>0,'progressoGeral'=>0]])

        <div class="mt-4 rounded-xl border bg-white p-4 shadow-sm">
            <h3 class="text-lg font-semibold mb-3">Pagamentos</h3>
            <div class="text-sm text-gray-500">Em breve: histórico de faturas, assinaturas e recibos.</div>
        </div>
    </div>
@endsection
