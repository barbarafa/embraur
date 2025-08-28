@extends('layouts.app')
@section('title','Área do Aluno')

@section('content')
    <section class="container-page py-8">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 justify-center text-blue-700 font-semibold">
                <span class="inline-block h-6 w-6 rounded-sm bg-blue-600"></span> EAD Pro
            </div>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold">Área do Aluno</h1>
            <p class="muted mt-2">Entre com suas credenciais para acessar seus cursos</p>
        </div>

        <div class="mx-auto mt-6 max-w-xl">
            <div class="card p-6">
                <form method="POST" action="{{ route('aluno.login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium">E-mail</label>
                        <input type="email" name="email" class="input" value="{{ old('email') }}" required>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium">Senha</label>
                            <a href="#" class="text-xs text-blue-600 hover:underline">Esqueci minha senha</a>
                        </div>
                        <input type="password" name="password" class="input" required>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="h-4 w-4">
                        <span class="text-sm">Lembrar-me</span>
                    </div>
                    <button class="btn-primary w-full">Entrar</button>

                    <p class="muted text-center text-sm">
                        Não tem uma conta? <a href="#" class="text-blue-600 hover:underline">Cadastre-se</a>
                    </p>
                    <div class="text-center">
                        <a href="{{ route('portal.professor') }}" class="text-xs hover:underline">Acesso para Professores</a>
                    </div>
                </form>
            </div>

            <div class="card mt-6 p-6 text-center">
                <div class="font-semibold">Acesso Demo</div>
                <p class="muted text-sm mt-1">Explore a plataforma sem criar uma conta</p>
                <form method="POST" action="{{ route('aluno.demo') }}" class="mt-3">
                    @csrf
                    <button class="btn-outline">Entrar como Demo</button>
                </form>
            </div>
        </div>
    </section>
@endsection
