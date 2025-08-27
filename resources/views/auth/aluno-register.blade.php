@extends('layouts.app')
@section('title','Cadastro de Aluno')
@section('content')
    <div class="max-w-lg mx-auto mt-12 p-6 border rounded-xl">
        <h1 class="text-2xl font-bold mb-4">Criar conta</h1>
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('aluno.register.store') }}">
            @csrf
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="nome" value="{{ old('nome') }}" required class="w-full rounded-lg border-gray-300">
            <label class="block text-sm font-medium mt-4 mb-1">E-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300">
            <label class="block text-sm font-medium mt-4 mb-1">Senha</label>
            <input type="password" name="password" required class="w-full rounded-lg border-gray-300">
            <label class="block text-sm font-medium mt-4 mb-1">Confirmar senha</label>
            <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300">
            <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg">Cadastrar</button>
        </form>
    </div>
@endsection
