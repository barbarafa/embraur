@extends('layouts.app')
@section('title','Portal do Professor - Login')

@section('content')
    <section class="mx-auto container-page px-4 py-12 max-w-md">
        <div class="card p-6">
            <h1 class="text-xl font-bold mb-3">Login do Professor</h1>
            @if(session('warn')) <div class="mb-2 text-yellow-700 bg-yellow-50 p-2 rounded">{{ session('warn') }}</div> @endif
            @if($errors->any()) <div class="mb-2 text-red-600">{{ $errors->first() }}</div> @endif

            <form method="post" action="{{ route('prof.login.do') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="text-sm">Senha</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded">
                </div>
                <button class="btn btn-primary w-full">Entrar</button>
            </form>
        </div>
    </section>
@endsection
