@extends('layouts.app')
@section('title','Cadastro de Aluno')

@section('content')
    <section class="mx-auto container-page px-4 py-12">
        <div class="max-w-md mx-auto card p-6">
            <h1 class="text-xl font-bold mb-3">Criar conta</h1>
            <form method="post" action="{{ route('aluno.register.do') }}" class="space-y-3">
                @csrf

                <input type="hidden" name="intended" value="{{ $intended }}">
                <input type="hidden" name="curso" value="{{ $curso }}">


                <div>
                    <label class="text-sm">Nome</label>
                    <input name="nome" value="{{ old('nome') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('nome')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm">CPF</label>
                    <input name="cpf" value="{{ old('cpf') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('cpf')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">Celular</label>
                    <input name="telefone" value="{{ old('telefone') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('telefone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">Data Nascimento</label>
                    <input name="data_nascimento" value="{{ old('data_nascimento') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('data_nascimento')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
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
