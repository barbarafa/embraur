@extends('layouts.app')
@section('title','Catálogo de Cursos')

@section('content')
    {{-- Título --}}
    <section class="container-page py-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-center">Catálogo de Cursos</h1>
        <p class="muted text-center mt-2">Explore nossa biblioteca completa de cursos profissionais e certifique-se com qualidade.</p>
    </section>

    {{-- Busca / Filtros --}}
    <section class="container-page">
        <div class="card p-4 md:p-5">
            <div class="flex flex-col md:flex-row gap-3 md:items-center">
                <div class="relative flex-1">
                    <input id="busca" class="input" placeholder="Buscar curso...">
                    <button id="btnBuscar" class="btn-primary absolute right-1 top-1 bottom-1">Buscar</button>
                </div>
                <div class="flex gap-2">
                    <button class="chip" data-tag="">Todos</button>
                    <button class="chip" data-tag="segurança">Segurança</button>
                    <button class="chip" data-tag="gestão">Gestão</button>
                    <button class="chip" data-tag="meio-ambiente">Meio Ambiente</button>
                </div>
            </div>
        </div>
    </section>

    {{-- Grid --}}
    <section class="container-page py-8">
        <div id="totalEncontrados" class="muted mb-3">Carregando cursos...</div>

        <div id="gridCursos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @for($i=0;$i<8;$i++)
                <div class="card p-4">
                    <div class="skeleton h-36"></div>
                    <div class="skeleton h-4 mt-4"></div>
                    <div class="skeleton h-4 mt-2 w-2/3"></div>
                    <div class="skeleton h-8 mt-6"></div>
                </div>
            @endfor
        </div>

        <div class="mt-8 text-center">
            <button id="btnCarregarMais" class="btn-outline hidden">Carregar Mais Cursos</button>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const grid=document.getElementById('gridCursos');
        const totalEncontrados=document.getElementById('totalEncontrados');
        const btnCarregarMais=document.getElementById('btnCarregarMais');
        const busca=document.getElementById('busca');
        const btnBuscar=document.getElementById('btnBuscar');
        const tagButtons=document.querySelectorAll('[data-tag]');

        let state={page:1,nextPageUrl:null,search:'',tag:''};

        async function listarCursos(reset=false){
            const base='/api/cursos';
            const p=new URLSearchParams({ativos:1,page:state.page});
            if(state.search) p.set('q',state.search);
            if(state.tag) p.set('tag',state.tag);

            try{
                const r=await fetch(`${base}?${p.toString()}`);
                const j=await r.json();
                const data=j?.data ?? j ?? [];
                const total=j?.total ?? data.length;
                state.nextPageUrl=j?.next_page_url ?? null;

                if(reset) grid.innerHTML='';
                totalEncontrados.textContent=`${total} cursos encontrados`;

                data.forEach(c=>{
                    const preco=(c.modalidades?.[0]?.preco ?? 0).toFixed(2);
                    const el=document.createElement('div');
                    el.className='card card-hover p-4 flex flex-col';
                    el.innerHTML=`
        <div class="h-36 bg-slate-100 rounded-md overflow-hidden">
          ${c.imagem_capa ? `<img src="${c.imagem_capa}" class="w-full h-full object-cover">` : ''}
        </div>
        <div class="mt-3 font-semibold line-clamp-2 min-h-[44px]">${c.titulo ?? ''}</div>
        <div class="muted text-sm mt-1 line-clamp-3 min-h-[54px]">${c.resumo ?? ''}</div>
        <div class="mt-4 flex items-center justify-between">
          <span class="text-blue-700 font-bold">R$ ${preco}</span>
          <a href="{{ route('site.cursos') }}" class="btn-primary text-xs">Ver Detalhes</a>
        </div>`;
                    grid.appendChild(el);
                });

                btnCarregarMais.classList.toggle('hidden', !state.nextPageUrl);
            }catch(e){
                totalEncontrados.textContent='Não foi possível carregar os cursos.';
                console.error(e);
            }
        }

        btnBuscar.addEventListener('click', ()=>{ state.page=1; state.search=busca.value.trim(); listarCursos(true); });
        busca.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ state.page=1; state.search=busca.value.trim(); listarCursos(true); }});
        btnCarregarMais.addEventListener('click', ()=>{ if(state.nextPageUrl){ state.page+=1; listarCursos(false); }});
        tagButtons.forEach(btn=>{
            btn.addEventListener('click', ()=>{
                tagButtons.forEach(b=>b.classList.remove('chip-blue'));
                btn.classList.add('chip-blue');
                state.tag=btn.dataset.tag; state.page=1; listarCursos(true);
            });
        });
        document.addEventListener('DOMContentLoaded', ()=> listarCursos(true));
    </script>
@endpush
