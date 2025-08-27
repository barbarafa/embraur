<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EAD Pro — Início</title>

    {{-- Tailwind via CDN (pode trocar por o que preferir) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .container{max-width:1200px}
        .shadow-card{box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}
    </style>
</head>
<body class="bg-gray-50 text-slate-800">

{{-- NAVBAR --}}
<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('site.home') }}" class="flex items-center gap-2">
            <span class="font-bold text-lg">📘 EAD Pro</span>
        </a>

        <nav class="hidden md:flex items-center gap-6">
            <a href="{{ route('site.home') }}" class="hover:text-blue-600">Início</a>
            <a href="{{ route('site.cursos') }}" class="hover:text-blue-600">Cursos</a>
            <a href="#sobre" class="hover:text-blue-600">Sobre</a>
            <a href="#contato" class="hover:text-blue-600">Contato</a>
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('portal.aluno') }}"
               class="px-3 py-2 text-sm rounded-md border border-slate-300 hover:bg-slate-100">Portal do Aluno</a>
            <a href="{{ route('portal.professor') }}"
               class="px-3 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">Portal do Professor</a>
        </div>
    </div>
</header>

{{-- HERO --}}
<section class="relative bg-gradient-to-r from-blue-700 to-indigo-700 text-white">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                Transforme sua carreira com <span class="text-yellow-300">cursos de qualidade</span>
            </h1>
            <p class="mt-4 text-blue-100">
                Certificações reconhecidas pelo mercado, metodologia comprovada e suporte completo.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('site.cursos') }}"
                   class="px-5 py-3 rounded-md bg-white text-blue-700 font-medium hover:bg-blue-50">
                    Explorar Cursos
                </a>
                <a href="#demo" class="px-5 py-3 rounded-md border border-white/40 hover:bg-white/10">
                    Assistir Demonstração
                </a>
            </div>
        </div>
    </div>
</section>

{{-- MÉTRICAS --}}
<section class="bg-white">
    <div class="container mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div>
            <div class="text-2xl font-extrabold">50.000+</div>
            <div class="text-sm text-slate-500">Alunos certificados</div>
        </div>
        <div>
            <div class="text-2xl font-extrabold">200+</div>
            <div class="text-sm text-slate-500">Cursos disponíveis</div>
        </div>
        <div>
            <div class="text-2xl font-extrabold">98%</div>
            <div class="text-sm text-slate-500">Taxa de aprovação</div>
        </div>
        <div>
            <div class="text-2xl font-extrabold">24/7</div>
            <div class="text-sm text-slate-500">Suporte disponível</div>
        </div>
    </div>
</section>

{{-- CURSOS POPULARES --}}
<section class="container mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-center">Cursos Populares</h2>
    <p class="text-center text-slate-500 mt-2">Comece seu aprendizado hoje mesmo.</p>

    <div id="cursos-grid" class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Cards preenchidos via JS (fetch /api/cursos) --}}
        <!-- Skeleton placeholders -->
        @for($i=0; $i<4; $i++)
            <div class="bg-white rounded-lg shadow-card p-4 animate-pulse">
                <div class="h-32 bg-slate-200 rounded-md"></div>
                <div class="h-4 bg-slate-200 rounded mt-4"></div>
                <div class="h-4 bg-slate-200 rounded mt-2 w-2/3"></div>
                <div class="h-8 bg-slate-200 rounded mt-6"></div>
            </div>
        @endfor
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('site.cursos') }}"
           class="px-4 py-2 rounded-md border border-slate-300 hover:bg-slate-100">
            Ver Todos os Cursos
        </a>
    </div>
</section>

{{-- POR QUE ESCOLHER --}}
<section id="sobre" class="bg-white border-y">
    <div class="container mx-auto px-4 py-14 grid md:grid-cols-2 gap-8 items-center">
        <div>
            <h3 class="text-xl font-bold">Por que escolher a EAD Pro?</h3>
            <ul class="mt-6 space-y-3 text-slate-700">
                <li>✅ Certificações reconhecidas nacionalmente</li>
                <li>✅ Metodologia comprovada de ensino</li>
                <li>✅ Suporte especializado 24/7</li>
                <li>✅ Plataforma intuitiva e responsiva</li>
                <li>✅ Conteúdo sempre atualizado</li>
                <li>✅ Acesso vitalício aos cursos</li>
            </ul>
            <a href="{{ route('site.cursos') }}" class="inline-block mt-6 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Saiba Mais
            </a>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-8 text-center">
            <div class="text-blue-700 font-semibold">Certificação Garantida</div>
            <p class="text-slate-600 mt-2">Certificado digital com QR Code e validação pública.</p>
        </div>
    </div>
</section>

{{-- DEPOIMENTOS --}}
<section class="container mx-auto px-4 py-14">
    <h3 class="text-xl font-bold text-center">O que nossos alunos dizem</h3>
    <div class="mt-8 grid md:grid-cols-3 gap-6">
        <blockquote class="bg-white rounded-lg shadow-card p-6">
            <div class="text-slate-600 italic">“Os cursos da EAD Pro são excelentes! Me ajudaram no desenvolvimento.”</div>
            <div class="mt-4 text-sm text-slate-500">Maria Silva · Técnica de Segurança</div>
        </blockquote>
        <blockquote class="bg-white rounded-lg shadow-card p-6">
            <div class="text-slate-600 italic">“Plataforma muito bem estruturada, conteúdo de qualidade e certificados reconhecidos.”</div>
            <div class="mt-4 text-sm text-slate-500">João Santos · Engenheiro Civil</div>
        </blockquote>
        <blockquote class="bg-white rounded-lg shadow-card p-6">
            <div class="text-slate-600 italic">“Recomendo para todos! Metodologia clara e suporte excelente.”</div>
            <div class="mt-4 text-sm text-slate-500">Ana Costa · Gestora de Projetos</div>
        </blockquote>
    </div>
</section>

{{-- NEWSLETTER / CONTATO --}}
<section id="contato" class="bg-blue-700 text-white">
    <div class="container mx-auto px-4 py-12 text-center">
        <h4 class="text-xl font-bold">Fique por dentro das novidades</h4>
        <p class="text-blue-100 mt-2">Receba em primeira mão promoções e novos cursos.</p>
        <form class="mt-6 flex flex-col md:flex-row items-center justify-center gap-3">
            <input type="email" placeholder="Seu e-mail" class="px-4 py-2 rounded-md text-slate-800 w-72">
            <button class="px-4 py-2 rounded-md bg-white text-blue-700 font-medium hover:bg-blue-50">
                Inscrever-se
            </button>
        </form>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-white">
    <div class="container mx-auto px-4 py-10 grid md:grid-cols-4 gap-6 text-sm">
        <div>
            <div class="font-semibold mb-2">EAD Pro</div>
            <p>Plataforma completa de ensino a distância com cursos de qualidade e certificação reconhecida.</p>
        </div>
        <div>
            <div class="font-semibold mb-2">Links Rápidos</div>
            <ul class="space-y-1">
                <li><a href="{{ route('site.cursos') }}" class="hover:text-blue-600">Catálogo de Cursos</a></li>
                <li><a href="#sobre" class="hover:text-blue-600">Sobre nós</a></li>
                <li><a href="#contato" class="hover:text-blue-600">Contato</a></li>
                <li><a href="{{ route('portal.aluno') }}" class="hover:text-blue-600">Área do Aluno</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold mb-2">Área do Aluno</div>
            <ul class="space-y-1">
                <li><a href="{{ route('portal.aluno') }}" class="hover:text-blue-600">Login</a></li>
                <li><a href="{{ route('portal.aluno') }}" class="hover:text-blue-600">Meus Cursos</a></li>
                <li><a href="{{ route('portal.aluno') }}" class="hover:text-blue-600">Certificados</a></li>
            </ul>
        </div>
        <div>
            <div class="font-semibold mb-2">Contato</div>
            <p>contato@eadpro.com.br</p>
            <p>(11) 99999-9999</p>
            <p>São Paulo - SP</p>
        </div>
    </div>
    <div class="border-t py-4 text-center text-xs text-slate-500">
        © {{ date('Y') }} EAD Pro. Todos os direitos reservados.
    </div>
</footer>

{{-- JS: carregar cursos populares da API --}}
<script>
    async function carregarCursosPopulares() {
        const grid = document.getElementById('cursos-grid');
        try {
            const resp = await fetch('/api/cursos?ativos=1');
            const json = await resp.json();
            // se for pagination padrão do Laravel
            const items = json?.data ?? json;

            grid.innerHTML = '';
            (items || []).slice(0, 4).forEach(curso => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-card p-4 flex flex-col';

                card.innerHTML = `
            <div class="h-32 bg-slate-100 rounded-md overflow-hidden">
              ${curso.imagem_capa ? `<img src="${curso.imagem_capa}" class="w-full h-full object-cover">` : ''}
            </div>
            <div class="mt-3 font-semibold line-clamp-2">${curso.titulo}</div>
            <div class="text-sm text-slate-500 mt-1 line-clamp-3">${curso.resumo ?? ''}</div>
            <div class="mt-auto flex items-center justify-between pt-4">
              <span class="text-blue-700 font-bold">R$ ${(curso.modalidades?.[0]?.preco ?? 0).toFixed(2)}</span>
              <a href="{{ route('site.cursos') }}"
                 class="px-3 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">
                Ver Detalhes
              </a>
            </div>
          `;
                grid.appendChild(card);
            });
        } catch (e) {
            console.error('Erro ao carregar cursos:', e);
        }
    }

    document.addEventListener('DOMContentLoaded', carregarCursosPopulares);
</script>
</body>
</html>
