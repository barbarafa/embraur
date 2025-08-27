
@extends('layouts.app')
@section('title','Esqueci minha senha')
@section('content')
    <div class="max-w-lg mx-auto mt-12 p-6 border rounded-xl">
        <h1 class="text-2xl font-bold mb-4">Esqueci minha senha</h1>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('aluno.password.email') }}">
            @csrf
            <label class="block text-sm font-medium mb-1">E-mail</label>
            <input type="email" name="email" required class="w-full rounded-lg border-gray-300">
            <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg">Enviar link de recuperação</button>
        </form>
    </div>
@endsection
