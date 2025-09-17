@extends('layouts.app')

@section('content')
    <div class="container-page py-6">
        @include('aluno._tabs', ['aluno' => $aluno, 'stats' => ['cursos'=>0,'concluidos'=>count($certificados),'horas'=>0,'progressoGeral'=>0]])

        <div class="mt-4 rounded-xl border bg-white p-4 shadow-sm">
            <h3 class="text-lg font-semibold mb-3">Certificados</h3>

            <div class="grid md:grid-cols-2 gap-3">
                @forelse($certificados as $cert)
                    <div class="rounded-lg border p-4 bg-white">
                        <div class="text-sm font-medium">{{ $cert['curso'] }}</div>
                        <div class="text-xs text-gray-500 mb-3">Emitido em {{ \Illuminate\Support\Carbon::parse($cert['emitido_em'])->format('d/m/Y') }}</div>
                        <div class="flex gap-2">
                            <a href="{{ $cert['visualizar'] }}" class="px-3 py-2 rounded-md border text-sm hover:bg-gray-50">Visualizar</a>
                            <a href="{{ $cert['download'] }}" class="px-3 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700">Download</a>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Você ainda não possui certificados.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
