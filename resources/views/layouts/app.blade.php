<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'EAD Pro')</title>

    {{-- mantém o seu vite (se já estiver ok, segue usando) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- fallback SEM instalar nada: deixa tudo bonito mesmo se o vite não carregar --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .container-page{max-width:1100px;margin:0 auto;padding-left:1rem;padding-right:1rem}
        .btn{display:inline-flex;align-items:center;justify-content:center;border-radius:.5rem;padding:.5rem .9rem;font-weight:600;border:1px solid transparent;line-height:1}
        .btn-primary{background:#2563eb;color:#fff}.btn-primary:hover{background:#1d4ed8}
        .btn-outline{background:#fff;color:#0f172a;border-color:#cbd5e1}.btn-outline:hover{background:#f8fafc}
        .btn-soft{background:#f1f5f9;color:#0f172a}.btn-soft:hover{background:#e2e8f0}
    </style>
</head>
<body class="bg-slate-50 text-slate-800">
{{-- Header ÚNICO --}}
<header class="bg-white border-b">
    <div class="container-page flex items-center justify-between h-14">
        <a href="{{ route('site.home') }}" class="flex items-center gap-2 font-semibold">
            <span class="inline-block w-5 h-5 rounded border border-slate-300"></span> EAD Pro
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('site.home') }}" class="hover:text-blue-600">Início</a>
            <a href="{{ route('site.cursos') }}" class="hover:text-blue-600">Cursos</a>
            <a href="#" class="hover:text-blue-600">Sobre</a>
            <a href="#" class="hover:text-blue-600">Contato</a>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('portal.aluno') }}" class="hidden sm:inline-flex btn btn-soft">Portal do Aluno</a>
            <a href="{{ route('portal.professor') }}" class="hidden sm:inline-flex btn btn-outline">Portal do Professor</a>

            @if(session('aluno_id'))
                <form action="{{ route('aluno.logout') }}" method="post">@csrf
                    <button class="btn-primary h-9 px-4 rounded-md">Sair</button>
                </form>
            @endif
        </div>
    </div>
</header>

<main>@yield('content')</main>

{{-- Footer ÚNICO --}}
<footer class="mt-10 border-t bg-white">
    <div class="container-page py-8 grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
        <div>
            <div class="font-semibold mb-2">EAD Pro</div>
            <p class="text-slate-600">Plataforma completa de ensino a distância com cursos de qualidade e certificação reconhecida.</p>
        </div>
        <div>
            <div class="font-semibold mb-2">Links Rápidos</div>
            <ul class="space-y-1 text-slate-600">
                <li><a class="hover:text-blue-600" href="{{ route('site.cursos') }}">Catálogo de Cursos</a></li>
                <li><a class="hover:text-blue-600" href="#">Sobre Nós</a></li>
                <li><a class="hover:text-blue-600" href="#">Contato</a></li>
                <li><a class="hover:text-blue-600" href="#">Central de Ajuda</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold mb-2">Área do Aluno</div>
            <ul class="space-y-1 text-slate-600">
                <li><a class="hover:text-blue-600" href="{{ route('aluno.login') }}">Login</a></li>
                <li><a class="hover:text-blue-600" href="{{ route('aluno.register') }}">Cadastro</a></li>
                <li><a class="hover:text-blue-600" href="{{ route('aluno.cursos') }}">Meus Cursos</a></li>
                <li><a class="hover:text-blue-600" href="{{ route('aluno.certificados') }}">Certificados</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold mb-2">Contato</div>
            <ul class="space-y-1 text-slate-600">
                <li>contato@eadpro.com.br</li><li>(11) 99999-9999</li><li>São Paulo, SP</li>
            </ul>
        </div>
    </div>
    <div class="text-center text-xs text-slate-500 py-4 border-t">© 2024 EAD Pro. Todos os direitos reservados.</div>
</footer>
</body>
</html>
