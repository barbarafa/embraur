@extends('layouts.app')
@section('title','Criar Novo Curso')

@section('content')
    <section class="container-page mx-auto py-6 max-w-5xl">

        {{-- NAV de seção (fixo no topo) --}}
        <nav class="sticky top-0 z-20 -mx-4 mb-4 bg-white/80 backdrop-blur border-b">
            <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm">
                    <a href="#sec-basicas" class="px-3 py-1 rounded-full border hover:bg-slate-50">1. Informações</a>
                    <a href="#sec-estrutura" class="px-3 py-1 rounded-full border hover:bg-slate-50">2. Estrutura</a>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <button type="submit" name="salvar" value="rascunho" class="btn btn-outline h-9">Salvar como Rascunho</button>
                    <button type="submit" name="salvar" value="publicar" class="btn btn-primary h-9">
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

                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Descrição Curta</label>
                    <input name="descricao_curta"
                           value="{{ old('descricao_curta', $curso->descricao_curta) }}"
                           placeholder="Uma breve descrição do curso em uma linha"
                           class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Descrição Completa</label>
                    <textarea name="descricao_completa"
                              placeholder="Descreva detalhadamente o que os alunos irão aprender..."
                              class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                              rows="5">{{ old('descricao_completa', $curso->descricao_completa) }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-medium">Nível *</label>
                    <select name="nivel" required
                            class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                        @php $nivelSel = old('nivel', $curso->nivel); @endphp
                        <option value="iniciante" @selected($nivelSel==='iniciante')>Iniciante</option>
                        <option value="intermediario" @selected($nivelSel==='intermediario')>Intermediário</option>
                        <option value="avancado" @selected($nivelSel==='avancado')>Avançado</option>
                    </select>
                </div>

                {{-- Duração removida do layout original via comentário – mantido assim conforme seu código --}}

                <div>
                    <label class="text-sm font-medium">Preço (R$)</label>
                    <input name="preco"
                           value="{{ old('preco', $curso->preco) }}"
                           type="number" min="0" step="0.01" placeholder="Ex.: 99,90"
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
                                 src="{{ $curso->imagem_capa ? asset($curso->imagem_capa) : '' }}"
                                 class="w-full h-full object-cover {{ $curso->imagem_capa ? '' : 'hidden' }}">
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
                                <h3 class="font-semibold">Módulo <span class="mod-num">{{ $mIdx + 1 }}</span></h3>
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
                                    <input name="modulos[{{ $mIdx }}][titulo]"
                                           value="{{ old("modulos.$mIdx.titulo", $modulo->titulo) }}"
                                           class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-sm font-medium">Descrição do Módulo</label>
                                    <textarea name="modulos[{{ $mIdx }}][descricao]" rows="3"
                                              class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">{{ old("modulos.$mIdx.descricao", $modulo->descricao) }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-3" data-aulas="{{ $mIdx }}">
                                @foreach($modulo->aulas as $aIdx => $aula)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 border rounded-md p-3 bg-white" data-aula="{{ $aIdx }}">
                                        <input type="hidden" name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][id]" value="{{ $aula->id }}">

                                        <div class="md:col-span-2">
                                            <label class="text-sm font-medium">Título da Aula</label>
                                            <input name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][titulo]"
                                                   value="{{ old("modulos.$mIdx.aulas.$aIdx.titulo", $aula->titulo) }}"
                                                   class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                        </div>

                                        <div>
                                            <label class="text-sm font-medium">Duração (min)</label>
                                            <input type="number" min="0" step="1"
                                                   name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][duracao_minutos]"
                                                   value="{{ old("modulos.$mIdx.aulas.$aIdx.duracao_minutos", $aula->duracao_minutos) }}"
                                                   class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                        </div>

                                        <div>
                                            <label class="text-sm font-medium">Tipo</label>
                                            @php $tipoSel = old("modulos.$mIdx.aulas.$aIdx.tipo", $aula->tipo); @endphp
                                            <select name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][tipo]"
                                                    class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                                <option value="video"   @selected($tipoSel==='video')>Vídeo</option>
                                                <option value="texto"   @selected($tipoSel==='texto')>Texto</option>
                                                <option value="quiz"    @selected($tipoSel==='quiz')>Quiz</option>
                                                <option value="arquivo" @selected($tipoSel==='arquivo')>Arquivo</option>
                                            </select>
                                        </div>

                                        <div class="md:col-span-4">
                                            <label class="text-sm font-medium">Descrição da Aula (opcional)</label>
                                            <input name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][descricao]"
                                                   value="{{ old("modulos.$mIdx.aulas.$aIdx.descricao", $aula->descricao) }}"
                                                   class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                                        </div>

                                        <div class="md:col-span-3">
                                            <label class="text-sm font-medium">URL do Conteúdo (opcional)</label>
                                            <input name="modulos[{{ $mIdx }}][aulas][{{ $aIdx }}][conteudo_url]"
                                                   value="{{ old("modulos.$mIdx.aulas.$aIdx.conteudo_url", $aula->conteudo_url) }}"
                                                   class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200" placeholder="https://...">
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

                            <div class="mt-3">
{{--                                <button type="button" class="btn btn-outline" onclick="window.addAula({{ $mIdx }})">＋ Adicionar Aula</button>--}}
                                <button type="button" class="btn btn-outline" data-action="add-aula" data-modulo="{{ $mIdx }}">＋ Adicionar Aula</button>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex items-center justify-between">
                <button type="button" class="btn btn-outline" id="addModuloBtn">＋ Adicionar Módulo</button>
                <span class="text-xs text-slate-500">Use os botões acima para organizar os módulos</span>
            </div>
        </div>

        {{-- Barra de ações fixa no rodapé --}}
        <div class="sticky bottom-0 z-20 mt-6 bg-white/80 backdrop-blur border-t">
            <div class="max-w-5xl mx-auto px-1 py-3 flex justify-end gap-2">
                <button type="submit" name="salvar" value="rascunho" class="btn btn-outline">Salvar como Rascunho</button>
                <button type="submit" name="salvar" value="publicar" class="btn btn-primary">
                    {{ ($mode ?? 'create') === 'edit' ? 'Salvar Alterações' : 'Criar Curso' }}
                </button>
            </div>
        </div>

    </section>

    {{-- JS: preview, colapsar módulos, numerar e atalhos --}}
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

            // Renumera títulos "Módulo N" após add/remove
            function renumberModules(){
                modWrap.querySelectorAll('[data-modulo]').forEach((el, i)=>{
                    const num = el.querySelector('.mod-num');
                    if (num) num.textContent = (i+1);
                });
            }
            window.removeModulo = function(btn){
                const card = btn.closest('[data-modulo]');
                if (!card) return;
                card.remove();
                renumberModules();
            };

            // Colapsar/expandir módulos
            function setExpanded(card, expanded){
                const btn = card.querySelector('.toggle-modulo');
                const body = card.querySelector('.modulo-body');
                if (!btn || !body) return;
                btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                btn.querySelector('.i').textContent = expanded ? '▾' : '▸';
                body.style.display = expanded ? '' : 'none';
            }
            modWrap.querySelectorAll('[data-modulo]').forEach(card=>{
                const btn = card.querySelector('.toggle-modulo');
                btn?.addEventListener('click', ()=> {
                    const expanded = btn.getAttribute('aria-expanded') !== 'true';
                    setExpanded(card, expanded);
                });
                // Começa expandido
                setExpanded(card, true);
            });
            document.getElementById('btnExpandAll')?.addEventListener('click', ()=>{
                modWrap.querySelectorAll('[data-modulo]').forEach(card=> setExpanded(card, true));
            });
            document.getElementById('btnCollapseAll')?.addEventListener('click', ()=>{
                modWrap.querySelectorAll('[data-modulo]').forEach(card=> setExpanded(card, false));
            });

            // Adicionar módulo (mantém seus campos/nomes)
            function esc(s){ return (s ?? '').toString().replace(/"/g,'&quot;'); }
            function moduloTemplate(idx){
                return `
      <div class="rounded-lg border p-0 overflow-hidden" data-modulo="\${idx}">
        <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b">
          <div class="flex items-center gap-3">
            <button type="button" class="toggle-modulo h-8 w-8 rounded-md border bg-white hover:bg-slate-100 grid place-items-center" aria-expanded="true"><span class="i">▾</span></button>
            <h3 class="font-semibold">Módulo <span class="mod-num">\${idx+1}</span></h3>
          </div>
          <button type="button" class="text-red-600 hover:underline" onclick="window.removeModulo(this)">Remover</button>
        </div>
        <div class="modulo-body p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
            <div class="md:col-span-2">
              <label class="text-sm font-medium">Título do Módulo</label>
              <input name="modulos[\${idx}][titulo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
            </div>
            <div class="md:col-span-2">
              <label class="text-sm font-medium">Descrição do Módulo</label>
              <textarea name="modulos[\${idx}][descricao]" rows="3" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:border-slate-400 focus:ring-2 focus:ring-slate-200"></textarea>
            </div>
          </div>
          <div class="space-y-3" data-aulas="\${idx}"></div>
          <div class="mt-3">
            <button type="button" class="btn btn-outline" onclick="window.addAula(${idx})">＋ Adicionar Aula</button>
          </div>
        </div>
      </div>
    `;
            }
            function aulaTemplate(mIdx, aIdx){
                return `
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3 border rounded-md p-3 bg-white" data-aula="${aIdx}">
        <div class="md:col-span-2">
          <label class="text-sm font-medium">Título da Aula</label>
          <input name="modulos[${mIdx}][aulas][${aIdx}][titulo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
        </div>
        <div>
          <label class="text-sm font-medium">Duração (min)</label>
          <input type="number" min="0" step="1" name="modulos[${mIdx}][aulas][${aIdx}][duracao_minutos]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
        </div>
        <div>
          <label class="text-sm font-medium">Tipo</label>
          <select name="modulos[${mIdx}][aulas][${aIdx}][tipo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
            <option value="video">Vídeo</option>
            <option value="texto">Texto</option>
            <option value="quiz">Quiz</option>
            <option value="arquivo">Arquivo</option>
          </select>
        </div>
        <div class="md:col-span-4">
          <label class="text-sm font-medium">Descrição da Aula (opcional)</label>
          <input name="modulos[${mIdx}][aulas][${aIdx}][descricao]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
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
            window.addAula = function(modIdx){
                const cont = document.querySelector(`[data-aulas="${modIdx}"]`);
                if (!cont) return console.warn('Container de aulas não encontrado para módulo', modIdx);
                const next = cont.querySelectorAll('[data-aula]').length;
                cont.insertAdjacentHTML('beforeend', aulaTemplate(modIdx, next));
            };
            function addModulo(){
                const idx = modWrap.querySelectorAll('[data-modulo]').length;
                modWrap.insertAdjacentHTML('beforeend', moduloTemplate(idx));
                renumberModules();
            }
            addModuloBtn?.addEventListener('click', addModulo);

        })();
    </script>
@endsection
