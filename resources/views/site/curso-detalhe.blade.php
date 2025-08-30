@extends('layouts.app')
@section('title', $curso->titulo)

@section('content')
    <section class="mx-auto container-page px-4 py-10">
        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 card p-6">
                <h1 class="text-2xl font-bold mb-2">{{ $curso->titulo }}</h1>
                <div class="text-sm text-slate-500 mb-4">
                    <span class="badge border-blue-200 text-blue-700 bg-blue-50">{{ $curso->categoria->nome }}</span>
                    <span class="badge border-slate-200 text-slate-600 bg-slate-50 ml-2">{{ $curso->nivel }}</span>
                </div>
                <p class="text-slate-700">{{ $curso->descricao }}</p>
            </div>
            <aside class="card p-6">
                @if(session()->has('aluno_id'))
                    <form class="mt-4" method="post" action="{{ route('aluno.matricular',$curso) }}">
                        @csrf
                        <button class="btn btn-primary w-full">Matricular-se</button>
                    </form>
                @else
                    <a href="{{ route('aluno.login') }}?intended={{ urlencode(request()->fullUrl()) }}" class="btn btn-primary w-full mt-4">
                        Faça login para matricular-se
                    </a>
                    @endif
                    <button class="btn btn-primary w-full mt-4">Matricular-se</button>
            </aside>
        </div>
    </section>
@endsection
