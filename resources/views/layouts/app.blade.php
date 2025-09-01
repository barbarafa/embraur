<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','EAD Pro')</title>

    {{-- Tailwind via CDN + Remix Icons --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    {{-- Utilitários próprios (sem @apply, pois estamos no CDN) --}}
    <style>
        .container-page{max-width:1152px}
        .btn{
            display:inline-flex; align-items:center; justify-content:center;
            border-radius:0.375rem; padding:0.5rem 1rem;
            font-size:0.875rem; font-weight:500; transition:all .2s;
        }
        .btn-primary{ background:#2563eb; color:#fff; }
        .btn-primary:hover{ background:#1d4ed8; }
        .btn-outline{ border:1px solid rgb(203 213 225); color:rgb(71 85 105); }
        .btn-outline:hover{ background:rgb(248 250 252); }
        .badge{
            font-size:11px; padding:2px 8px; border-radius:9999px; border:1px solid rgb(226 232 240);
        }
        .card{
            border-radius:0.75rem; border:1px solid rgb(226 232 240); background:#fff;
            box-shadow:0 1px 2px rgba(16,24,40,.04);
        }
    </style>
</head>

@php use Illuminate\Support\Str; @endphp
<body class="bg-gray-50 min-h-screen flex flex-col">

{{-- HEADER --}}
<header class="bg-white border-b">
    <div class="mx-auto container-page px-4 py-3 flex items-center justify-between">
        {{-- LOGO --}}
        <a href="{{ route('site.home') }}" class="flex items-center gap-2">
            <i class="ri-graduation-cap-line text-xl text-blue-600"></i>
            <span class="font-semibold">EAD Pro</span>
        </a>

        {{-- MENU PRINCIPAL --}}
        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a class="{{ request()->routeIs('site.home') ? 'text-blue-600 font-semibold' : 'text-slate-700 hover:text-blue-600' }}"
               href="{{ route('site.home') }}">Início</a>
            <a class="{{ request()->routeIs('site.cursos') ? 'text-blue-600 font-semibold' : 'text-slate-700 hover:text-blue-600' }}"
               href="{{ route('site.cursos') }}">Cursos</a>
            <a class="text-slate-700 hover:text-blue-600" href="#">Sobre</a>
            <a class="text-slate-700 hover:text-blue-600" href="#">Contato</a>
        </nav>

        {{-- AÇÕES (DIREITA) --}}
        <div class="flex items-center gap-2">
            @if(session()->has('aluno_id'))
                <a href="{{ route('aluno.dashboard') }}" class="btn btn-outline text-xs">
                    Olá, {{ Str::limit(session('aluno_nome'), 16) }}
                </a>
                <form method="post" action="{{ route('aluno.logout') }}">
                    @csrf
                    <button class="btn btn-primary text-xs">Sair</button>
                </form>
            @else
                {{-- Botão do topo: leva ao login real --}}
                <a href="{{ route('aluno.login') }}" class="btn btn-outline text-xs">Portal do Aluno</a>
                <a href="{{ route('prof.login') }}" class="btn btn-primary text-xs">Portal do Professor</a>
            @endif
        </div>
    </div>
</header>

{{-- CONTEÚDO --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="mt-10 bg-white border-t">
    <div class="mx-auto container-page px-4 py-10 grid md:grid-cols-4 gap-8 text-sm">
        <div>
            <p class="font-semibold mb-2">EAD Pro</p>
            <p class="text-slate-600">Plataforma completa de ensino a distância com cursos de qualidade e certificação reconhecida.</p>
            <div class="flex items-center gap-3 mt-3 text-slate-500">
                <i class="ri-facebook-line"></i><i class="ri-instagram-line"></i><i class="ri-youtube-line"></i>
            </div>
        </div>
        <div>
            <p class="font-semibold mb-2">Links Rápidos</p>
            <ul class="space-y-1 text-slate-600">
                <li><a href="{{ route('site.cursos') }}" class="hover:text-blue-600">Catálogo de Cursos</a></li>
                <li><a href="#" class="hover:text-blue-600">Sobre Nós</a></li>
                <li><a href="#" class="hover:text-blue-600">Contato</a></li>
                <li><a href="#" class="hover:text-blue-600">Central de Ajuda</a></li>
            </ul>
        </div>
        <div>
            <p class="font-semibold mb-2">Área do Aluno</p>
            <ul class="space-y-1 text-slate-600">
                <li><a href="{{ route('aluno.login') }}" class="hover:text-blue-600">Login</a></li>
                <li><a href="{{ route('aluno.register') }}" class="hover:text-blue-600">Cadastro</a></li>
                <li><a href="{{ route('aluno.dashboard') }}" class="hover:text-blue-600">Meus Cursos</a></li>
                <li><a href="#" class="hover:text-blue-600">Certificados</a></li>
            </ul>
        </div>
        <div>
            <p class="font-semibold mb-2">Contato</p>
            <ul class="space-y-1 text-slate-600">
                <li><i class="ri-mail-line mr-1"></i> contato@eadpro.com.br</li>
                <li><i class="ri-phone-line mr-1"></i> (11) 99999-9999</li>
                <li><i class="ri-map-pin-line mr-1"></i> São Paulo, SP</li>
            </ul>
        </div>
    </div>
    <div class="border-t">
        <div class="mx-auto container-page px-4 py-4 text-xs text-slate-500">
            © 2024 EAD Pro. Todos os direitos reservados.
        </div>
    </div>
</footer>

</body>
</html>
