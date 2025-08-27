@extends('layouts.app')

@section('title', 'Área do Aluno - Login')

@section('content')
    <div class="min-h-[80vh] flex flex-col items-center">
        {{-- Cabeçalho / Breadcrumb simples --}}
        <div class="mt-10 text-center">
            <div class="inline-flex items-center gap-2 text-2xl font-semibold">
                <span class="i">📘</span>
                <span>EAD Pro</span>
            </div>
            <h1 class="mt-6 text-4xl font-extrabold tracking-tight">Área do Aluno</h1>
            <p class="mt-2 text-gray-500">Entre com suas credenciais para acessar seus cursos</p>
        </div>

        {{-- Card de Login --}}
        <div class="w-full max-w-lg mt-10">
            <div class="rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4">Fazer Login</h2>

                {{-- Status / Sucesso (ex: reset de senha) --}}
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Erros de validação --}}
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('aluno.login') }}" novalidate>
                    @csrf

                    {{-- E-mail --}}
                    <label class="block text-sm font-medium mb-1" for="email">E-mail</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="seu@email.com"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    />

                    {{-- Senha --}}
                    <label class="block text-sm font-medium mt-4 mb-1" for="password">Senha</label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Sua senha"
                            class="w-full rounded-lg border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500"
                        />
                        <button type="button" onclick="togglePwd()"
                                class="absolute inset-y-0 right-0 px-3 text-gray-500 focus:outline-none"
                                aria-label="Mostrar/ocultar senha">👁️</button>
                    </div>

                    {{-- Lembrar-me + Esqueci senha --}}
                    <div class="mt-3 flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="remember" class="rounded border-gray-300" {{ old('remember') ? 'checked' : '' }}>
                            Lembrar-me
                        </label>

                        {{-- ajuste a rota se já tiver sua própria tela de reset --}}
                        <a class="text-sm text-blue-600 hover:underline"
                           href="{{ route('aluno.password.request') }}">Esqueci minha senha</a>
                    </div>

                    {{-- Entrar --}}
                    <button type="submit"
                            class="w-full mt-5 inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-white font-medium hover:bg-blue-700 focus:outline-none">
                        Entrar
                    </button>

                    {{-- Cadastro + Professores --}}
                    <div class="mt-4 text-center text-sm text-gray-600">
                        Não tem uma conta?
                        <a class="text-blue-600 hover:underline" href="{{ route('aluno.register') }}">Cadastre-se</a>
                    </div>

                    <div class="mt-2 text-center">
                        <a class="text-sm text-gray-600 hover:underline" href="{{ route('portal.professor') }}">
                            Acesso para Professores
                        </a>
                    </div>
                </form>
            </div>

            {{-- Card de Acesso Demo --}}
            <div class="rounded-xl border border-gray-200 shadow-sm p-6 mt-6 text-center">
                <h3 class="font-semibold mb-1">Acesso Demo</h3>
                <p class="text-sm text-gray-500">Explore a plataforma sem criar uma conta</p>

                <form method="POST" action="{{ route('aluno.demo') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                            class="mx-auto inline-flex justify-center rounded-lg border px-4 py-2.5 font-medium hover:bg-gray-50">
                        Entrar como Demo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePwd() {
            const i = document.getElementById('password');
            i.type = i.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
