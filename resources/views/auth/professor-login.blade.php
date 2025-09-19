@extends('layouts.app')
@section('title','Área do Professor - Login')

@section('content')
    <section class="mx-auto container-page px-4 py-12">
        <div class="text-center mb-6">
            <div class="inline-flex items-center gap-2 text-blue-700">
                <i class="ri-graduation-cap-line text-xl"></i><span class="font-semibold">Embraur</span>
            </div>
            <h1 class="text-2xl font-extrabold mt-2">Portal do professor</h1>
            <p class="text-slate-600">Entre com suas credenciais para o painel administrativo</p>

        </div>

        <div class="max-w-md mx-auto card p-6">
            @if (session('warn'))
                <div class="mb-3 p-2 rounded bg-yellow-50 text-yellow-700 text-sm">{{ session('warn') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-3 text-sm text-red-600">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ route('prof.login.do') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-sm">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-md" placeholder="seu@email.com">
                </div>
                <div>
                    <label class="text-sm">Senha</label>
                    <div class="relative">
                        <input type="password" name="password" class="w-full px-3 py-2 border rounded-md pr-10" placeholder="Sua senha">
                        <i class="ri-eye-line absolute right-3 top-2.5 text-slate-400"></i>
                    </div>
                </div>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" class="rounded"> Lembrar-me
                </label>
                <button class="btn btn-primary w-full">Entrar</button>
            </form>

            <div class="text-center text-sm mt-3">
                Não tem uma conta? <a href="{{ route('prof.login') }}" class="text-blue-700 hover:underline">Cadastre-se</a>
            </div>


        </div>
    </section>
@endsection
