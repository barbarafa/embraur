@php
    $editing = (bool) $quiz;
    $cursoSel = old('curso_id', $quiz->curso_id ?? request('curso'));
    $moduloSel = old('modulo_id', $quiz->modulo_id ?? request('modulo'));
    $escopoSel = old('escopo', $quiz->escopo ?? 'curso');
    $questoes = collect(old('questoes', $quiz ? $quiz->questoes->map(function($q){
        return [
            'id' => $q->id,
            'enunciado' => $q->enunciado,
            'tipo' => $q->tipo,
            'pontuacao' => $q->pontuacao,
            'opcoes' => $q->opcoes->map(fn($o)=>[
                'id'=>$o->id,'texto'=>$o->texto,'correta'=>$o->correta ? 1 : 0
            ])->toArray(),
        ];
    })->toArray() : []));
@endphp

{{-- Campos principais --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="text-sm font-medium">Título *</label>
        <input name="titulo" value="{{ old('titulo', $quiz->titulo ?? '') }}"
               class="mt-1 w-full h-10 rounded-md border px-3" required>
        @error('titulo') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="text-sm font-medium">Escopo *</label>
        <select name="escopo" class="mt-1 w-full h-10 rounded-md border px-3">
            <option value="curso"  @selected($escopoSel==='curso')>Curso</option>
            <option value="modulo" @selected($escopoSel==='modulo')>Módulo</option>
        </select>
    </div>

    <div>
        <label class="text-sm font-medium">Curso *</label>
        <select name="curso_id" id="selCurso" class="mt-1 w-full h-10 rounded-md border px-3" required>
            <option value="">— Selecione —</option>
            @foreach($cursos as $c)
                <option value="{{ $c->id }}" @selected($cursoSel == $c->id)>{{ $c->titulo }}</option>
            @endforeach
        </select>
        @error('curso_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="text-sm font-medium">Módulo (se escopo = módulo)</label>
        <select name="modulo_id" id="selModulo" class="mt-1 w-full h-10 rounded-md border px-3">
            <option value="">— Opcional —</option>
            @foreach(($modulosPorCurso[$cursoSel] ?? []) as $m)
                <option value="{{ $m->id }}" @selected($moduloSel == $m->id)>{{ $m->titulo }}</option>
            @endforeach
        </select>
        @error('modulo_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="text-sm font-medium">Descrição</label>
        <textarea name="descricao" rows="3" class="mt-1 w-full rounded-md border px-3 py-2">{{ old('descricao', $quiz->descricao ?? '') }}</textarea>
    </div>

{{--    <div class="md:col-span-2">--}}
{{--        <label class="inline-flex items-center gap-2 text-sm">--}}
{{--            <input type="checkbox" name="correcao_manual" value="1"--}}
{{--                @checked(old('correcao_manual', $quiz->correcao_manual ?? false))>--}}
{{--            Exigir correção manual (quando houver questões de texto)--}}
{{--        </label>--}}
{{--    </div>--}}
</div>

{{-- Questões --}}
<div class="rounded-xl border p-4">
    <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold">Questões</h2>
        <button type="button" class="btn btn-outline h-9" id="btnAddQuestao">＋ Adicionar questão</button>
    </div>

    <div id="questoesWrap" class="space-y-4">
        @forelse($questoes as $qIdx => $q)
            @include('prof.quizzes._questoes', ['qIdx' => $qIdx, 'q' => $q])
        @empty
            {{-- vazio, o JS começa do zero --}}
        @endforelse
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ url()->previous() }}" class="btn btn-outline">Cancelar</a>
    <button class="btn btn-primary">{{ $editing ? 'Salvar alterações' : 'Criar Quiz' }}</button>
</div>

{{-- templates inline para JS --}}
<script>
    (function(){
        const modulosPorCurso = @json(collect($modulosPorCurso)->map(fn($c)=>$c->map(fn($m)=>['id'=>$m->id,'titulo'=>$m->titulo])));
        const wrap = document.getElementById('questoesWrap');
        const addBtn = document.getElementById('btnAddQuestao');

        function questaoTemplate(i){
            return `
<div class="border rounded-md p-3" data-q="${i}">
  <input type="hidden" name="questoes[${i}][id]">
  <div class="flex items-center justify-between">
    <div class="font-medium">Questão ${i+1}</div>
    <button type="button" class="text-red-600 text-sm" data-action="rm-q">Remover</button>
  </div>

  <label class="text-sm mt-2 block">Enunciado *</label>
  <textarea name="questoes[${i}][enunciado]" rows="2" class="w-full rounded-md border px-3 py-2" required></textarea>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-2">
    <div>
      <label class="text-sm">Tipo</label>
      <select name="questoes[${i}][tipo]" class="w-full h-10 rounded-md border px-3" data-role="tipo">
        <option value="multipla">Múltipla escolha</option>
        <option value="texto">Resposta em texto</option>
      </select>
    </div>
    <div>
      <label class="text-sm">Pontuação</label>
      <input type="number" step="0.1" min="0" name="questoes[${i}][pontuacao]" value="1" class="w-full h-10 rounded-md border px-3">
    </div>
  </div>

  <div class="mt-3" data-opcoes>
    <div class="flex items-center justify-between mb-2">
      <div class="text-sm font-medium">Opções (marque a correta)</div>
      <button type="button" class="text-sm btn btn-outline h-8" data-action="add-op">＋ Opção</button>
    </div>
    <div class="space-y-2" data-opcoes-wrap></div>
  </div>
</div>`;
        }

        function opcaoTemplate(i, j){
            return `
<div class="flex items-center gap-2" data-op="${j}">
  <input type="hidden" name="questoes[${i}][opcoes][${j}][id]">
  <label class="inline-flex items-center gap-2 flex-1 border rounded-md px-2 py-2">
    <input type="radio" name="questoes[${i}][opcao_correta]" value="${j}">
    <input name="questoes[${i}][opcoes][${j}][texto]" class="flex-1 outline-none" placeholder="Texto da opção">
  </label>
  <button type="button" class="text-red-600 text-sm" data-action="rm-op">Remover</button>
</div>`;
        }

        function renumber(){
            wrap.querySelectorAll('[data-q]').forEach((card, idx)=>{
                card.querySelector('.font-medium').textContent = `Questão ${idx+1}`;
            });
        }

        function addQuestao(){
            const idx = wrap.querySelectorAll('[data-q]').length;
            wrap.insertAdjacentHTML('beforeend', questaoTemplate(idx));
            const card = wrap.querySelector('[data-q]:last-child');
            // adiciona 4 opções padrão
            const opWrap = card.querySelector('[data-opcoes-wrap]');
            for(let j=0;j<4;j++){
                opWrap.insertAdjacentHTML('beforeend', opcaoTemplate(idx, j));
            }
            bindCard(card);
            renumber();
        }

        function addOpcao(card){
            const i = +card.dataset.q;
            const opWrap = card.querySelector('[data-opcoes-wrap]');
            const j = opWrap.querySelectorAll('[data-op]').length;
            opWrap.insertAdjacentHTML('beforeend', opcaoTemplate(i, j));
        }

        function bindCard(card){
            card.addEventListener('click', (e)=>{
                const rmQ = e.target.closest('[data-action="rm-q"]');
                if(rmQ){ card.remove(); renumber(); return; }

                const addOp = e.target.closest('[data-action="add-op"]');
                if(addOp){ addOpcao(card); return; }

                const rmOp = e.target.closest('[data-action="rm-op"]');
                if(rmOp){ rmOp.closest('[data-op]').remove(); return; }
            });

            const selTipo = card.querySelector('[data-role="tipo"]');
            const blocoOp = card.querySelector('[data-opcoes]');
            function refreshTipo(){
                blocoOp.style.display = selTipo.value === 'multipla' ? '' : 'none';
            }
            selTipo.addEventListener('change', refreshTipo);
            refreshTipo();
        }

        addBtn?.addEventListener('click', addQuestao);

        // já renderizadas pelo servidor:
        wrap.querySelectorAll('[data-q]').forEach(bindCard);

        // carrega módulos de acordo com o curso
        const selCurso = document.getElementById('selCurso');
        const selModulo = document.getElementById('selModulo');
        function refreshModulos(){
            const cid = selCurso.value;
            const lista = modulosPorCurso[cid] || [];
            selModulo.innerHTML = '<option value="">— Opcional —</option>';
            lista.forEach(m=>{
                const opt = document.createElement('option');
                opt.value = m.id; opt.textContent = m.titulo;
                selModulo.appendChild(opt);
            });
        }
        selCurso?.addEventListener('change', refreshModulos);
    })();
</script>
