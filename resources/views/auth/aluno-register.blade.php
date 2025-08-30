@extends('layouts.app')
@section('title','Cadastro de Aluno')

@section('content')
    <section class="mx-auto container-page px-4 py-12">
        <div class="max-w-md mx-auto card p-6">
            <h1 class="text-xl font-bold mb-3">Criar conta</h1>
            <form method="post" action="{{ route('aluno.register.do') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm">Nome</label>
                    <input name="nome" value="{{ old('nome') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('nome')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm">E-mail</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm">Senha</label>
                    <input name="password" type="password" class="w-full px-3 py-2 border rounded-md">
                    @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm">Confirmar Senha</label>
                    <input name="password_confirmation" type="password" class="w-full px-3 py-2 border rounded-md">
                </div>
                <button class="btn btn-primary w-full">Cadastrar</button>
            </form>
        </div>
    </section>
@endsection
