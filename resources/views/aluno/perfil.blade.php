@extends('layouts.app')

@section('content')
    <div class="container-page py-6">
        @include('aluno._tabs', ['aluno' => $aluno, 'stats' => ['cursos'=>0,'concluidos'=>0,'horas'=>0,'progressoGeral'=>0]])

        <div class="mt-4 rounded-xl border bg-white p-4 shadow-sm">
            <h3 class="text-lg font-semibold mb-4">Dados Pessoais</h3>

            <form method="post" action="#">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nome</label>
                        <input type="text" class="w-full rounded-md border-gray-300" value="{{ $aluno->nome_completo ?? '' }}" disabled>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">E-mail</label>
                        <input type="email" class="w-full rounded-md border-gray-300" value="{{ $aluno->email ?? '' }}" disabled>
                    </div>
                </div>
{{--                <div class="mt-4">--}}
{{--                    <a href="#" class="px-4 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700">Editar Perfil</a>--}}
{{--                </div>--}}
            </form>
        </div>
    </div>
@endsection
