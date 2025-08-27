<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EAD Pro — Catálogo de Cursos</title>

    {{-- Tailwind (ou troque por seu stack) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .container{max-width:1200px}
        .shadow-card{box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}
        .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
        .line-clamp-3{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
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
            <a href="{{ route('site.cursos') }}" class="text-blue-600 font-semibold">Cursos</a>
            <a href="{{ route('site.home') }}#sobre" class="hover:text-blue-600">Sobre</a>
            <a href="{{ route('site.home') }}#contato" class="hover:text-blue-600">Contato</a>
        </nav>
        <div class="flex items-center gap-2">
            <a href="{{ route('portal.aluno') }}"
               class="px-3 py-2 text-sm rounded-md border border-slate-300 hover:bg-slate-100">Portal do Aluno</a>
            <a href="{{ route('portal.professor') }}"
               class="px-3 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">Portal do Professor</a>
        </div>
    </div>
</header>

{{-- HEADER --}}
<section class="bg-white border-b">
    <div class="container mx-auto px-4 py-10 text-center">
        <h1 class="text-2xl md:text-3xl font-extrabold">Catálogo de Cursos</h1>
        <p class="text-slate-500 mt-2">
            Explore nossa biblioteca de cursos profissionais e certifique-se com qualidade.
        </p>
    </div>
</section>

{{-- BUSCA / FILTROS --}}
<section class="bg-white">
    <div class="container mx-auto px-4 pb-2">
        <div class="flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex-1">
                <div class="relative">
                    <input id="busca" type="text" placeholder="Buscar curso..."
                           class="w-full px-4 py-3 rounded-md border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button id="btnBuscar"
                            class="absolute right-1 top-1 bottom-1 px-4 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Buscar
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- filtros exemplo (tags fake só pra UI) --}}
                <button class="px-3 py-2 text-sm rounded-md border tag-btn" data-tag="">Todos</button>
                <button class="px-3 py-2 text-sm rounded-md border tag-btn" data-tag="segurança">Segurança</button>
                <button class="px-3 py-2 text-sm rounded-md border tag-btn" data-tag="gestão">Gestão</button>
                <button class="px-3 py-2 text-sm rounded-md border tag-btn" data-tag="meio-ambiente">Meio Ambiente</button>
            </div>
        </div>
    </div>
</section>

{{-- GRID DE CURSOS --}}
<main class="container mx-auto px-4 py-8">
    <div id="totalEncontrados" class="text-sm text-slate-500 mb-3">Carregando cursos...</div>

    <div id="gridCursos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        {{-- skeletons iniciais --}}
        @for($i=0; $i<8; $i++)
            <div class="bg-white rounded-lg shadow-card p-4 animate-pulse">
                <div class="h-36 bg-slate-200 rounded-md"></div>
                <div class="h-4 bg-slate-200 rounded mt-4"></div>
                <div class="h-4 bg-slate-200 rounded mt-2 w-2/3"></div>
                <div class="h-8 bg-slate-200 rounded mt-6"></div>
            </div>
        @endfor
    </div>

    <div class="mt-8 text-center">
        <button id="btnCarregarMais"
                class="hidden px-4 py-2 rounded-md border border-slate-300 hover:bg-slate-100">
            Carregar Mais Cursos
        </button>
    </div>
</main>

{{-- FOOTER --}}
<footer class="bg-white">
    <div class="container mx-auto px-4 py-10 grid md:grid-cols-4 gap-6 text-sm">
        <div>
            <div class="font-semibold mb-2">EAD Pro</div>
            <p>Plataforma completa de EAD com certificação reconhecida.</p>
        </div>
        <div>
            <div class="font-semibold mb-2">Links Rápidos</div>
            <ul class="space-y-1">
                <li><a href="{{ route('site.cursos') }}" class="hover:text-blue-600">Catálogo de Cursos</a></li>
                <li><a href="{{ route('site.home') }}#sobre" class="hover:text-blue-600">Sobre nós</a></li>
                <li><a href="{{ route('site.home') }}#contato" class="hover:text-blue-600">Contato</a></li>
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

{{-- JS: consumo da API /api/cursos --}}
<script>
    const grid = document.getElementById('gridCursos');
    const totalEncontrados = document.getElementById('totalEncontrados');
    const btnCarregarMais = document.getElementById('btnCarregarMais');
    const busca = document.getElementById('busca');
    const btnBuscar = document.getElementById('btnBuscar');
    const tagButtons = document.querySelectorAll('.tag-btn');

    let state = {
        page: 1,
        nextPageUrl: null,
        search: '',
        tag: ''
    };

    async function listarCursos(reset = false) {
        const base = '/api/cursos';
        const params = new URLSearchParams();
        params.set('ativos', 1);
        if (state.search) params.set('q', state.search);
        if (state.tag) params.set('tag', state.tag); // se futuramente você implementar por tag
        params.set('page', state.page);

        const url = `${base}?${params.toString()}`;

        try {
            const resp = await fetch(url);
            const json = await resp.json();

            // Caso seja pagination do Laravel (data/links) ou array simples
            const data = json?.data ?? json ?? [];
            const total = json?.total ?? data.length;
            state.nextPageUrl = json?.next_page_url ?? null;

            if (reset) grid.innerHTML = '';

            // Atualiza contador
            totalEncontrados.textContent = `${total} cursos encontrados`;

            // Renderiza cards
            data.forEach(curso => {
                grid.appendChild(criarCardCurso(curso));
            });

            // Botão de "carregar mais"
            if (state.nextPageUrl) {
                btnCarregarMais.classList.remove('hidden');
            } else {
                btnCarregarMais.classList.add('hidden');
            }

        } catch (e) {
            totalEncontrados.textContent = 'Não foi possível carregar os cursos.';
            console.error(e);
        }
    }

    function criarCardCurso(curso) {
        const preco = (curso.modalidades?.[0]?.preco ?? 0).toFixed(2);
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-card p-4 flex flex-col';

        card.innerHTML = `
        <div class="h-36 bg-slate-100 rounded-md overflow-hidden">
          ${curso.imagem_capa ? `<img src="${curso.imagem_capa}" class="w-full h-full object-cover">` : ''}
        </div>
        <div class="mt-3 font-semibold line-clamp-2 min-h-[44px]">${curso.titulo}</div>
        <div class="text-sm text-slate-500 mt-1 line-clamp-3 min-h-[54px]">${curso.resumo ?? ''}</div>

        <div class="mt-4 flex items-center justify-between">
          <span class="text-blue-700 font-bold">R$ ${preco}</span>
          <a href="{{ route('site.cursos') }}" class="px-3 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">
            Ver Detalhes
          </a>
        </div>
      `;
        return card;
    }

    // eventos
    btnBuscar.addEventListener('click', () => {
        state.page = 1;
        state.search = busca.value.trim();
        listarCursos(true);
    });
    busca.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            state.page = 1;
            state.search = busca.value.trim();
            listarCursos(true);
        }
    });

    btnCarregarMais.addEventListener('click', () => {
        if (state.nextPageUrl) {
            state.page += 1;
            listarCursos(false);
        }
    });

    tagButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tagButtons.forEach(b => b.classList.remove('bg-blue-600','text-white'));
            btn.classList.add('bg-blue-600','text-white');
            state.tag = btn.dataset.tag;
            state.page = 1;
            listarCursos(true);
        });
    });

    // inicia
    document.addEventListener('DOMContentLoaded', () => listarCursos(true));
</script>
</body>
</html>
