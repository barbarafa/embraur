{{-- resources/views/prof/quizzes/_questoes.blade.php --}}
<div id="questoesWrap" class="space-y-4">
    @foreach(old('questoes', $quiz->questoes ?? [ [] ]) as $qIdx => $questao)
        <div class="questao-card border rounded-md p-4 bg-slate-50" data-q="{{ $qIdx }}">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold">Questão <span class="q-num">{{ $qIdx+1 }}</span></h4>
                <button type="button" class="text-red-600 text-xs" onclick="this.closest('.questao-card').remove(); renumberQuestoes()">Remover</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Enunciado *</label>
                    <textarea name="questoes[{{ $qIdx }}][enunciado]"
                              class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-200"
                              rows="3">{{ old("questoes.$qIdx.enunciado", $questao['enunciado'] ?? '') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-medium">Tipo *</label>
                    @php $tipo = old("questoes.$qIdx.tipo", $questao['tipo'] ?? 'multipla'); @endphp
                    <select name="questoes[{{ $qIdx }}][tipo]"
                            class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white">
                        <option value="multipla" @selected($tipo==='multipla')>Múltipla Escolha</option>
{{--                        <option value="texto" @selected($tipo==='texto')>Resposta em Texto</option>--}}
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Pontuação</label>
                    <input type="number" min="0.25" step="0.25"
                           name="questoes[{{ $qIdx }}][pontuacao]"
                           value="{{ old("questoes.$qIdx.pontuacao", $questao['pontuacao'] ?? 1) }}"
                           class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3">
                </div>
            </div>

            {{-- Opções (somente se múltipla) --}}
            <div class="mt-3 space-y-2 opcoesWrap">
                @php $opcoes = old("questoes.$qIdx.opcoes", $questao['opcoes'] ?? [ ['texto'=>''] ]); @endphp
                @foreach($opcoes as $oIdx => $op)
                    <div class="flex items-center gap-2 border rounded px-3 py-2 bg-white">
                        <input type="text"
                               name="questoes[{{ $qIdx }}][opcoes][{{ $oIdx }}][texto]"
                               value="{{ $op['texto'] ?? '' }}"
                               placeholder="Opção..."
                               class="flex-1 h-9 rounded-md border border-slate-300 px-2">
                        <label class="flex items-center gap-1 text-sm">
                            <input type="checkbox"
                                   name="questoes[{{ $qIdx }}][opcoes][{{ $oIdx }}][correta]"
                                   value="1" @checked(!empty($op['correta']))>
                            Correta
                        </label>
                        <button type="button" class="text-red-600 text-xs"
                                onclick="this.closest('div').remove()">Remover</button>
                    </div>
                @endforeach
                <button type="button"
                        class="text-xs px-2 py-1 rounded border hover:bg-slate-50"
                        onclick="addOpcao(this)">＋ Adicionar Opção</button>
            </div>
        </div>
    @endforeach
</div>

<div class="mt-4">
    <button type="button" class="btn btn-outline" onclick="addQuestao()">＋ Adicionar Questão</button>
</div>

{{-- JS para duplicar questões/opções --}}
<script>
    function renumberQuestoes(){
        document.querySelectorAll('#questoesWrap .questao-card').forEach((card,i)=>{
            card.dataset.q = i;
            card.querySelector('.q-num').textContent = i+1;
            card.querySelectorAll('[name]').forEach(inp=>{
                inp.name = inp.name.replace(/questoes\[\d+\]/, `questoes[${i}]`);
            });
        });
    }
    function addQuestao(){
        const wrap = document.getElementById('questoesWrap');
        const idx = wrap.querySelectorAll('.questao-card').length;
        const tpl = `
        <div class="questao-card border rounded-md p-4 bg-slate-50" data-q="${idx}">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold">Questão <span class="q-num">${idx+1}</span></h4>
                <button type="button" class="text-red-600 text-xs" onclick="this.closest('.questao-card').remove(); renumberQuestoes()">Remover</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Enunciado *</label>
                    <textarea name="questoes[${idx}][enunciado]" rows="3"
                              class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium">Tipo *</label>
                    <select name="questoes[${idx}][tipo]" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white">
                        <option value="multipla">Múltipla Escolha</option>
                        <option value="texto">Resposta em Texto</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Pontuação</label>
                    <input type="number" min="0.25" step="0.25" name="questoes[${idx}][pontuacao]" value="1"
                           class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3">
                </div>
            </div>
            <div class="mt-3 space-y-2 opcoesWrap">
                <div class="flex items-center gap-2 border rounded px-3 py-2 bg-white">
                    <input type="text" name="questoes[${idx}][opcoes][0][texto]" placeholder="Opção..."
                           class="flex-1 h-9 rounded-md border border-slate-300 px-2">
                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" name="questoes[${idx}][opcoes][0][correta]" value="1"> Correta
                    </label>
                    <button type="button" class="text-red-600 text-xs" onclick="this.closest('div').remove()">Remover</button>
                </div>
                <button type="button" class="text-xs px-2 py-1 rounded border hover:bg-slate-50"
                        onclick="addOpcao(this)">＋ Adicionar Opção</button>
            </div>
        </div>`;
        wrap.insertAdjacentHTML('beforeend', tpl);
    }
    function addOpcao(btn){
        const wrap = btn.closest('.opcoesWrap');
        const qIdx = btn.closest('.questao-card').dataset.q;
        const oIdx = wrap.querySelectorAll('input[name^="questoes['+qIdx+'][opcoes]"]').length/2;
        const tpl = `
        <div class="flex items-center gap-2 border rounded px-3 py-2 bg-white">
            <input type="text" name="questoes[${qIdx}][opcoes][${oIdx}][texto]" placeholder="Opção..."
                   class="flex-1 h-9 rounded-md border border-slate-300 px-2">
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" name="questoes[${qIdx}][opcoes][${oIdx}][correta]" value="1"> Correta
            </label>
            <button type="button" class="text-red-600 text-xs" onclick="this.closest('div').remove()">Remover</button>
        </div>`;
        btn.insertAdjacentHTML('beforebegin', tpl);
    }
</script>
