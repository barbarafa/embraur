@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-20 bg-white p-8 shadow rounded">
        <h2 class="text-xl font-bold mb-6 text-center">Login do Professor</h2>

        <form method="POST" action="{{ route('professor.login') }}">
            @csrf
            <input type="email" name="email" placeholder="E-mail" class="w-full border rounded px-3 py-2 mb-3" required>
            <input type="password" name="password" placeholder="Senha" class="w-full border rounded px-3 py-2 mb-3" required>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Entrar</button>
        </form>
    </div>
@endsection
