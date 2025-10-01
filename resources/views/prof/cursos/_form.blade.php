{{--@extends('layouts.app')--}}
{{--@section('title','Criar Novo Curso')--}}
{{--@section('content')--}}
<section class="container-page mx-auto py-6 max-w-5xl">

    {{-- Estilos leves para separar módulos/aulas sem quebrar Tailwind --}}
    <style>
        /* módulo: moldura mais sutil + sombra leve */
        #modulosWrap [data-modulo] { border-radius: 0.75rem; }
        /* aula: faixa lateral + separação por linhas finas e zebra */
        .aula-card {
            position: relative;
            border-left-width: 4px;        /* faixa lateral */
            border-left-color: rgb(59,130,246); /* blue-500 */
        }
        /* zebra: usa :nth-child(odd/even) dentro do bloco de aulas */
        [data-aulas] > .aula-card:nth-child(odd)  { background: #f8fafc; } /* slate-50 */
        [data-aulas] > .aula-card:nth-child(even) { background: #ffffff; }
        /* linhas finas no topo/rodapé de cada aula */
        .aula-card::before, .aula-card::after{
            content: "";
            position: absolute;
            left: 0; right: 0;
            height: 1px;
            background: rgba(148,163,184,.35); /* slate-400/35 */
        }
        .aula-card::before{ top: -8px; }
        .aula-card::after { bottom: -8px; }
        /* pílulas/badges */
        .pill { display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .5rem; border-radius:9999px; font-size:.72rem; font-weight:600; }
    </style>

    {{-- NAV de seção (fixo no topo) --}}
    <nav class="sticky top-0 z-20 -mx-4 mb-4 bg-white/80 backdrop-blur border-b">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm">
                <a href="#sec-basicas" class="px-3 py-1 rounded-full border hover:bg-slate-50">1. Informações</a>
                <a href="#sec-estrutura" class="px-3 py-1 rounded-full border hover:bg-slate-50">2. Estrutura</a>
            </div>


            <div class="hidden md:flex items-center gap-2">
                <button type="submit" form="cursoForm" name="salvar" value="rascunho" class="btn btn-outline h-9">
                    Salvar como Rascunho
                </button>
                <button type="submit" form="cursoForm" name="salvar" value="publicar" class="btn btn-primary h-9">
                    {{ ($mode ?? 'create') === 'edit' ? 'Salvar Alterações' : 'Criar Curso' }}
                </button>
            </div>

        </div>
    </nav>

    {{-- Cabeçalho --}}
    <div class="mb-4 flex items-center justify-between px-1">
        <a href="{{ route('prof.cursos.index') }}" class="btn btn-outline">← Voltar</a>
        <div class="flex gap-2 md:hidden">
            <button type="submit" name="salvar" value="rascunho" class="btn btn-outline">Rascunho</button>
            <button type="submit" name="salvar" value="publicar" class="btn btn-primary">
                {{ ($mode ?? 'create') === 'edit' ? 'Salvar' : 'Criar' }}
            </button>
        </div>
    </div>

    {{-- Card Informações Básicas --}}
    <div id="sec-basicas" class="rounded-xl border bg-white p-5 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">🗂️ Informações Básicas</h2>
            <span class="text-xs text-slate-500">Preencha os dados principais do curso</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium">Título do Curso *</label>
                <input name="titulo"
                       value="{{ old('titulo', $curso->titulo) }}"
                       required placeholder="Ex.: Curso completo de React"
                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                @error('titulo') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Categoria *</label>
                <select name="categoria_id" required
                        class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" @selected(old('categoria_id', $curso->categoria_id) == $cat->id)>
                            {{ $cat->nome }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            {{--
            <div class="md:col-span-2">
                <label class="text-sm font-medium">Descrição Curta</label>
                <textarea
                    name="descricao"
                    id="descricao"
                    class="js-ckeditor mt-1 w-full rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                    rows="4"
                    placeholder="Escreva um resumo do curso "
                >{{ old('descricao', $curso->descricao) }}</textarea>
            </div>
            --}}



            <div class="md:col-span-2">
                <label class="text-sm font-medium">Descrição Completa</label>
                <textarea
                    name="descricao_completa"
                    id="descricao_completa"
                    class="js-ckeditor mt-1 w-full rounded-md border border-slate-300"
                    placeholder="Descreva detalhadamente o que os alunos irão aprender..."
                    rows="8"
                >{{ old('descricao_completa', $curso->descricao_completa) }}</textarea>
            </div>

            <div>
                <label class="text-sm font-medium">Nível *</label>
                <select name="nivel" required
                        class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white">
                    @php
                        // usa o que veio do POST (old) ou o que está no modelo
                        $nivelSel = old('nivel', $curso->nivel ?? 'todos');
                    @endphp
                    <option value="todos"          @selected($nivelSel==='todos')>Todos os Níveis</option>
                    <option value="iniciante"      @selected($nivelSel==='iniciante')>Iniciante</option>
                    <option value="intermediario"  @selected($nivelSel==='intermediario')>Intermediário</option>
                    <option value="avancado"       @selected($nivelSel==='avancado')>Avançado</option>
                </select>
            </div>
            <div>
            <label class="text-sm font-medium">Preço original (R$)</label>
            <input name="preco_original"
                   value="{{ old('preco_original', $curso->preco_original) }}"
                   type="number" min="0" step="0.01" placeholder="Ex.: 99,90"
                   class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
            </div>
            <div>

                <label class="text-sm font-medium">Preço (R$)</label>
                <input name="preco"
                       value="{{ old('preco', $curso->preco) }}"
                       type="number" min="0" step="0.01" placeholder="Ex.: 99,90"
                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">



            </div>

            <div>
                <label class="text-sm font-medium">Nota Minima Para Aprovação</label>
                <input name="nota_minima_aprovacao"
                       value="{{ old('nota_minima_aprovacao', $curso->nota_minima_aprovacao) }}"
                       type="number" min="0" placeholder="Ex.: 7"
                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-medium">Imagem do Curso</label>
                <div class="mt-1 rounded-md border border-dashed p-6 text-center text-slate-500 bg-slate-50/50">
                    <div class="mb-3">Clique para fazer upload ou arraste uma imagem</div>
                    <input type="file" name="imagem_capa" id="imagemCapa" accept="image/*"
                           class="mx-auto block">
                    <div class="mt-3 aspect-video rounded bg-slate-100 overflow-hidden ring-1 ring-slate-200">
                        <img id="previewCapa"
                             src="{{ $curso->imagem_capa_url ?? '' }}"
                             class="w-full h-full object-cover {{ $curso->imagem_capa_url ? '' : 'hidden' }}">
                    </div>
                    @error('imagem_capa') <div class="text-red-600 text-xs mt-2">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Card Estrutura do Curso --}}
    <div id="sec-estrutura" class="rounded-xl border bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">📚 Estrutura do Curso</h2>
            <div class="flex items-center gap-2">
                <button type="button" id="btnExpandAll" class="text-sm px-3 py-1 rounded border hover:bg-slate-50">Expandir tudo</button>
                <button type="button" id="btnCollapseAll" class="text-sm px-3 py-1 rounded border hover:bg-slate-50">Recolher tudo</button>
            </div>
        </div>

        <div id="modulosWrap" class="space-y-4">
            @foreach($curso->modulos as $mIdx => $modulo)
                <div class="rounded-lg border p-0 overflow-hidden" data-modulo="{{ $mIdx }}">
                    {{-- Cabeçalho do módulo (colapsável) --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b">
                        <div class="flex items-center gap-3">
                            <button type="button" class="toggle-modulo h-8 w-8 rounded-md border bg-white hover:bg-slate-100 grid place-items-center"
                                    aria-expanded="true">
                                <span class="i">▾</span>
                            </button>
                            <div>
                                <h3 class="font-semibold">Módulo <span class="mod-num">{{ $mIdx + 1 }}</span></h3>
                                {{-- Badge de status da Prova --}}
                                <div class="mt-1">
                                    @if($modulo->quiz)
                                        <span class="pill bg-green-100 text-green-700 border border-green-200">
                                                ✅ Prova cadastrada
                                            </span>
                                        @if(isset($curso->id))
                                            <a href="{{ route('prof.quizzes.edit', $modulo->quiz->id ?? 0) }}"
                                               class="text-xs underline text-green-700 ml-2">Editar</a>
                                        @endif
                                    @else
                                        <span class="pill bg-slate-100 text-slate-700 border border-slate-200">
                                                ⏳ Sem prova
                                            </span>
                                        @if(isset($curso->id))
                                            <a href="{{ route('prof.quizzes.create', ['curso' => $curso->id, 'modulo' => $modulo->id]) }}"
                                               class="text-xs underline text-blue-700 ml-2">Criar agora</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="text-red-600 hover:underline"
                                onclick="window.removeModulo(this)">Remover</button>
                    </div>

                    <div class="modulo-body p-4">
                        {{-- ID do módulo (update) --}}
                        <input type="hidden" name="modulos[{{ $mIdx }}][id]" value="{{ $modulo->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Título do Módulo</label>
                                <input required name="modulos[{{ $mIdx }}][titulo]"
                                       value="{{ old("modulos.$mIdx.titulo", $modulo->titulo) }}"
                                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Descrição do Módulo</label>
                                <textarea name="modulos[{{ $mIdx }}][descricao]"
                                          class=" js-ckeditor mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                                          rows="4"
                                >{{ old("modulos.$mIdx.descricao", $modulo->descricao) }}</textarea>
                            </div>
                        </div>

                        {{-- Aulas --}}
                        <div class="space-y-6" data-aulas="{{ $mIdx }}">
                            @foreach($modulo->aulas as $aIdx => $aula)
                                <div class="aula-card grid grid-cols-1 md:grid-cols-4 gap-3 border rounded-md p-3 bg-white" data-aula="{{ $aIdx }}">
                                    <input type="hidden" name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][id]" value="{{ $aula->id }}">

                                    <div class="md:col-span-2">
                                        <label class="text-sm font-medium">Título da Aula</label>
                                        <input name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][titulo]"
                                               value="{{ old("modulos.$mIdx.aulas.$aIdx.titulo", $aula->titulo) }}"
                                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="Ex: Criando componentes">
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium">Duração (min)</label>
                                        <input type="number" min="0" step="1"
                                               name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][duracao_minutos]"
                                               value="{{ old("modulos.$mIdx.aulas.$aIdx.duracao_minutos", $aula->duracao_minutos) }}"
                                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="Ex: 15">
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium">Tipo</label>
                                        @php $tipoSel = old("modulos.$mIdx.aulas.$aIdx.tipo", $aula->tipo); @endphp
                                        <select name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][tipo]"
                                                class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                            <option value="video"   @selected($tipoSel==='video')>Vídeo</option>
                                            <option value="texto"   @selected($tipoSel==='texto')>Texto</option>

                                            <option value="arquivo" @selected($tipoSel==='arquivo')>Arquivo</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-4">
                                        <label class="text-sm font-medium">Descrição da Aula (opcional)</label>
                                        <textarea
                                            id="editor-desc-{{ $mIdx }}-{{ $aIdx }}"
                                            name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][conteudo_texto]"
                                            class="js-ckeditor mt-1 w-full rounded-md border border-slate-300"
                                            rows="5"
                                        >{{ old("modulos.$mIdx.aulas.$aIdx.descricao", $aula->conteudo_texto) }}</textarea>



                                    </div>

                                    <div class="md:col-span-3">
                                        <label class="text-sm font-medium">URL de Conteúdo (opcional)()</label>
                                        <input name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][conteudo_url]"
                                               value="{{ old("modulos.$mIdx.aulas.$aIdx.conteudo_url", $aula->conteudo_url) }}"
                                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="https://...">
                                    </div>
                                    {{-- NOVO: upload de vídeo opcional --}}
                                    <div class="md:col-span-3">
                                        <label class="text-sm font-medium">Enviar Vídeo (opcional)</label>
                                        <input type="file"
                                               name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][video_file]"
                                               accept="video/*"
                                               class="mt-1 block w-full text-sm file:mr-3 file:py-2 file:px-3 file:rounded-md file:border file:bg-slate-50 file:hover:bg-slate-100">
                                        <p class="text-xs text-slate-500 mt-1">
                                            Se você enviar um arquivo, a URL será ignorada. Formatos: MP4/WebM/OGG.
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @php
                                            $lib = old("modulos.$mIdx.aulas.$aIdx.liberada_apos_anterior", $aula->liberada_apos_anterior ? '1' : null);
                                        @endphp
                                        <input type="checkbox"
                                               name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][liberada_apos_anterior]"
                                               value="1" @checked($lib == '1')
                                               class="h-4 w-4 border border-slate-300">
                                        <label class="text-sm">Liberar só após concluir aula anterior</label>
                                    </div>

                                    <div class="md:col-span-4 text-right">
                                        <button type="button" class="text-red-600 hover:underline"
                                                onclick="this.closest('[data-aula]').remove()">Remover aula</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Ações do módulo: adicionar aula + criar prova --}}
                        <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn btn-outline" data-action="add-aula">＋ Adicionar Aula</button>

                                @if(isset($curso->id))
                                    <a
                                        href="{{ route('prof.quizzes.create', ['curso' => $curso->id, 'modulo' => $modulo->id]) }}"
                                        class="btn btn-soft"
                                        title="Criar prova para este módulo"
                                    >
                                        ✏️ Criar Prova do Módulo
                                    </a>
                                @endif
                            </div>

                            <span class="text-xs text-slate-500">Organize as aulas e cadastre a prova do módulo quando estiver pronto</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-between">
            <button type="button" class="btn btn-outline" id="addModuloBtn">＋ Adicionar Módulo</button>
            <span class="text-xs text-slate-500">Use os botões acima para organizar os módulos</span>
        </div>
    </div>

    {{-- Barra de ações fixa no rodapé --}}
    <div class="sticky bottom-0 z-20 mt-6 bg-white/80 backdrop-blur border-t">
        <div class="max-w-5xl mx-auto px-1 py-3 flex justify-end gap-2">
            <button type="submit" form="cursoForm" name="salvar" value="rascunho" class="btn btn-outline h-9">
                Salvar como Rascunho
            </button>
            <button type="submit" form="cursoForm" name="salvar" value="publicar" class="btn btn-primary h-9">
                {{ ($mode ?? 'create') === 'edit' ? 'Salvar Alterações' : 'Criar Curso' }}
            </button>
        </div>
    </div>

</section>

{{-- JS: preview, colapsar módulos, numerar e atalhos (sem mudanças de seletor) --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>

    (function(){
        // preview imagem
        const imgInput = document.getElementById('imagemCapa');
        if (imgInput) {
            imgInput.addEventListener('change', e => {
                const f = e.target.files?.[0]; if (!f) return;
                const img = document.getElementById('previewCapa');
                img.src = URL.createObjectURL(f);
                img.onload = ()=> URL.revokeObjectURL(img.src);
                img.classList.remove('hidden');
            });
        }

        const modWrap = document.getElementById('modulosWrap');
        const addModuloBtn = document.getElementById('addModuloBtn');
        if (!modWrap) return;

        function renumberModules(){
            modWrap.querySelectorAll('[data-modulo]').forEach((el, i)=>{
                const num = el.querySelector('.mod-num');
                if (num) num.textContent = (i+1);
            });
        }

        // remover módulo
        window.removeModulo = function(btn){
            const card = btn.closest('[data-modulo]');
            if (!card) return;
            card.remove();
            renumberModules();
        };

        // colapsar/expandir
        function setExpanded(card, expanded){
            const btn = card.querySelector('.toggle-modulo');
            const body = card.querySelector('.modulo-body');
            if (!btn || !body) return;
            btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            btn.querySelector('.i').textContent = expanded ? '▾' : '▸';
            body.style.display = expanded ? '' : 'none';
        }
        function bindModule(card){
            const btn = card.querySelector('.toggle-modulo');
            btn?.addEventListener('click', ()=>{
                const expanded = btn.getAttribute('aria-expanded') !== 'true';
                setExpanded(card, expanded);
            });
            setExpanded(card, true);
        }
        modWrap.querySelectorAll('[data-modulo]').forEach(bindModule);

        document.getElementById('btnExpandAll')?.addEventListener('click', ()=>{
            modWrap.querySelectorAll('[data-modulo]').forEach(card=> setExpanded(card, true));
        });
        document.getElementById('btnCollapseAll')?.addEventListener('click', ()=>{
            modWrap.querySelectorAll('[data-modulo]').forEach(card=> setExpanded(card, false));
        });

        // templates
        function moduloTemplate(idx){
            return `
      <div class="rounded-lg border p-0 overflow-hidden" data-modulo="${idx}">
        <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b">
          <div class="flex items-center gap-3">
            <button type="button" class="toggle-modulo h-8 w-8 rounded-md border bg-white hover:bg-slate-100 grid place-items-center" aria-expanded="true"><span class="i">▾</span></button>
            <div>
              <h3 class="font-semibold">Módulo <span class="mod-num">${idx+1}</span></h3>
              <div class="mt-1"><span class="pill bg-slate-100 text-slate-700 border border-slate-200">⏳ Sem prova</span></div>
            </div>
          </div>
          <button type="button" class="text-red-600 hover:underline" onclick="window.removeModulo(this)">Remover</button>
        </div>
        <div class="modulo-body p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
            <div class="md:col-span-2">
              <label class="text-sm font-medium">Título do Módulo</label>
              <input name="modulos[${idx}][titulo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
            </div>
            <div class="md:col-span-2">
              <label class="text-sm font-medium">Descrição do Módulo</label>
              <textarea name="modulos[${idx}][descricao]" rows="3" class="js-ckeditor mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"></textarea>
            </div>
          </div>
          <div class="space-y-6" data-aulas></div>
          <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
              <button type="button" class="btn btn-outline" data-action="add-aula">＋ Adicionar Aula</button>
              <span class="btn btn-soft opacity-60 cursor-not-allowed" title="Salve o curso para criar a prova">✏️ Criar Prova do Módulo</span>
            </div>
            <span class="text-xs text-slate-500">Organize as aulas e cadastre a prova do módulo quando estiver pronto</span>
          </div>
        </div>
      </div>
    `;
        }
        function aulaTemplate(mIdx, aIdx){
            return `
      <div class="aula-card grid grid-cols-1 md:grid-cols-4 gap-3 border rounded-md p-3 bg-white" data-aula="${aIdx}">
        <div class="md:col-span-2">
          <label class="text-sm font-medium">Título da Aula</label>
          <input name="modulos[${mIdx}][aulas][${aIdx}][titulo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="Ex: Criando componentes">
        </div>
        <div>
          <label class="text-sm font-medium">Duração (min)</label>
          <input type="number" min="0" step="1" name="modulos[${mIdx}][aulas][${aIdx}][duracao_minutos]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="Ex: 15">
        </div>
        <div>
          <label class="text-sm font-medium">Tipo</label>
          <select name="modulos[${mIdx}][aulas][${aIdx}][tipo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
            <option value="video">Vídeo</option>
            <option value="texto">Texto</option>
            <option value="arquivo">Arquivo</option>
          </select>
        </div>
        <div class="md:col-span-4">
          <label class="text-sm font-medium">Descrição da Aula (opcional)</label>
           <textarea
             name="modulos[${mIdx}][aulas][${aIdx}][descricao]"
             class="js-ckeditor mt-1 w-full rounded-md border border-slate-300"
             rows="5"
             placeholder="Descreva os pontos principais desta aula..."
           ></textarea>

        />
        </div>
        <div class="md:col-span-3">
          <label class="text-sm font-medium">URL do Conteúdo (opcional)</label>
          <input name="modulos[${mIdx}][aulas][${aIdx}][conteudo_url]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="https://...">
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="modulos[${mIdx}][aulas][${aIdx}][liberada_apos_anterior]" value="1" class="h-4 w-4 border border-slate-300">
          <label class="text-sm">Liberar só após concluir aula anterior</label>
        </div>
        <div class="md:col-span-4 text-right">
          <button type="button" class="text-red-600 hover:underline" data-action="remove-aula">Remover aula</button>
        </div>
      </div>
    `;
        }

        // índice do módulo pelo name=modulos[idx]
        function getModuloIndexFromNames(card){
            const any = card.querySelector('input[name^="modulos["], textarea[name^="modulos["], select[name^="modulos["]');
            const m = any?.name.match(/^modulos\[(\d+)\]/);
            return m ? parseInt(m[1],10) : null;
        }

        // Delegação: add-aula / remove-aula
        modWrap.addEventListener('click', (e)=>{
            const add = e.target.closest('[data-action="add-aula"]');
            if (add) {
                e.preventDefault();
                const card = add.closest('[data-modulo]');
                const mIdx = getModuloIndexFromNames(card);
                const cont = card.querySelector('[data-aulas]');
                if (!cont) return console.warn('Container de aulas não encontrado para módulo', mIdx);
                const next = cont.querySelectorAll('[data-aula]').length;
                cont.insertAdjacentHTML('beforeend', aulaTemplate(mIdx, next));
                // Inicializa CKEditor nos itens adicionados
                window.createCKEditorsIn?.(cont);
                return;
            }
            const rm = e.target.closest('[data-action="remove-aula"]');
            if (rm) {
                e.preventDefault();
                rm.closest('[data-aula]')?.remove();
            }
        });

        // adicionar módulo
        function addModulo(){
            const idx = modWrap.querySelectorAll('[data-modulo]').length;
            modWrap.insertAdjacentHTML('beforeend', moduloTemplate(idx));
            const card = modWrap.querySelector('[data-modulo]:last-child');
            bindModule(card);
            renumberModules();
            // Inicializa CKEditor no novo módulo
            window.createCKEditorsIn?.(card);
        }
        addModuloBtn?.addEventListener('click', addModulo);
    })();

</script>


<script>
    (function () {

        const UPLOAD_URL = "{{ route('prof.uploads.ckeditor') }}?_token={{ csrf_token() }}";

        // Permite <video> e <source> no conteúdo
        const htmlSupport = {
            allow: [
                { name: /^(video|source)$/, attributes: true, classes: true, styles: true }
            ]
        };

        // MediaEmbed com player também para .mp4/.webm/.ogg hospedados por você
        const mediaEmbed = {
            previewsInData: true,
            extraProviders: [
                {
                    name: 'localVideo',
                    // link terminando com .mp4/.webm/.ogg
                    url: /^https?:\/\/[^ ]+\.(mp4|webm|ogg)$/i,
                    html: match => {
                        const url = match[0];
                        const ext = (url.split('.').pop() || '').toLowerCase();
                        const type = ext === 'ogv' ? 'ogg' : ext;
                        return `<video controls style="max-width:100%;height:auto;">
                    <source src="${url}" type="video/${type}">
                  </video>`;
                    }
                }
            ]
        };

        const toolbar = [
            'undo','redo','|',
            'heading','|',
            'bold','italic','underline','link','|',
            'bulletedList','numberedList','blockQuote','|',
            'insertTable','imageUpload','mediaEmbed','|',
            'alignment','outdent','indent','|',
            'codeBlock','horizontalLine'
        ];

        // Cria todos os .js-ckeditor da página
        document.querySelectorAll('textarea.js-ckeditor').forEach((el) => {
            ClassicEditor.create(el, {
                language: 'pt-br',
                toolbar: { items: toolbar },
                ckfinder: { uploadUrl: UPLOAD_URL },   // ← upload direto (imagem/vídeo/qualquer arquivo)
                mediaEmbed,
                htmlSupport,
                // se a sua build vier com esses plugins e você não quiser usar, remova daqui:
                removePlugins: ['CKBox','CKFinder','EasyImage']
            })
                .then((editor) => {
                    // Dica: se arrastar soltar um .mp4, o CKEditor envia ao UPLOAD_URL.
                    // Depois é só colar a URL na linha e usar mediaEmbed: o preview vira <video> automaticamente.
                    // Para facilitar, sempre que subir vídeo a gente já insere o player:
                    const fileRepo = editor.plugins.get('FileRepository');

                    // Sobrescreve a renderização pós-upload de vídeo: insere como mediaEmbed
                    const origCreateAdapter = fileRepo.createUploadAdapter.bind(fileRepo);
                    fileRepo.createUploadAdapter = loader => {
                        const adapter = origCreateAdapter(loader);
                        const origUpload = adapter.upload?.bind(adapter);
                        // Se for o adapter padrão do ckfinder, terá upload(); senão, só retorna o adapter
                        if (!origUpload) return adapter;

                        adapter.upload = async () => {
                            const res = await origUpload();
                            try {
                                const url = res?.default ?? res?.url ?? res?.urls?.default ?? res?.url;
                                if (url && /\.(mp4|webm|ogg)$/i.test(url)) {
                                    // insere um media embed com o vídeo local
                                    editor.execute('mediaEmbed', url);
                                    // retorna algo "inofensivo" pro fluxo de upload de imagem
                                    return { default: url };
                                }
                            } catch(e) {}
                            return res;
                        };
                        return adapter;
                    };

                })
                .catch(console.error);
        });

    })();
</script>


{{--@endsection--}}
