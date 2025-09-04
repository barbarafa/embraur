@extends('layouts.app')
@section('title', ($curso->exists ? 'Editar Curso' : 'Criar Curso').' - Professor')

@section('content')
    <form
        action="{{ $curso->exists ? route('prof.cursos.update',$curso) : route('prof.cursos.store') }}"
        method="post" enctype="multipart/form-data">
        @csrf
        @if($curso->exists) @method('PUT') @endif

        <div class="container-page py-6 space-y-4">

            {{-- Barra superior de ações --}}
            <div class="bg-white border rounded-xl p-3 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2 text-slate-600">
                    <a href="{{ route('prof.cursos.index') }}" class="btn btn-outline">← Meus Cursos</a>
                    <span class="text-sm">/</span>
                    <span class="text-sm font-medium">
          {{ $curso->exists ? 'Editar Curso' : 'Criar Curso' }}
        </span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" name="salvar" value="rascunho" class="btn btn-outline">Salvar rascunho</button>
                    <button type="submit" name="salvar" value="publicar" class="btn-primary">Salvar e continuar</button>
                </div>
            </div>

            {{-- Grid principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- Coluna esquerda (form principal) --}}
                <div class="lg:col-span-2 space-y-4">

                    {{-- Informações do Curso --}}
                    <div class="rounded-xl border bg-white p-4 shadow-sm">
                        <h3 class="text-lg font-semibold mb-3">Informações do Curso</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Categoria --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Categoria *</label>
                                <select name="categoria_id" required
                                        class="mt-1 w-full h-10 rounded-md border border-slate-300 bg-white px-3
                     focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Selecione...</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('categoria_id', $curso->categoria_id) == $cat->id)>
                                            {{ $cat->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Título --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Título</label>
                                <input type="text" name="titulo" value="{{ old('titulo',$curso->titulo) }}" required
                                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ex.: Segurança do Trabalho - NR10">
                                @error('titulo') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Resumo --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Resumo</label>
                                <textarea name="descricao_curta" rows="3"
                                          class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Breve resumo do curso...">{{ old('descricao_curta',$curso->descricao_curta) }}</textarea>
                                @error('descricao_curta') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Nível --}}
                            <div>
                                <label class="text-sm font-medium">Nível *</label>
                                @php $niv = old('nivel', $curso->nivel ?? 'iniciante'); @endphp
                                <select name="nivel" required
                                        class="mt-1 w-full h-10 rounded-md border border-slate-300 bg-white px-3
                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="iniciante"     {{ $niv === 'iniciante' ? 'selected' : '' }}>Iniciante</option>
                                    <option value="intermediario" {{ $niv === 'intermediario' ? 'selected' : '' }}>Intermediário</option>
                                    <option value="avancado"      {{ $niv === 'avancado' ? 'selected' : '' }}>Avançado</option>
                                </select>
                                @error('nivel') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Carga horária --}}
                            <div>
                                <label class="text-sm font-medium">Carga Horária (h)</label>
                                <input type="number" name="carga_horaria_total" value="{{ old('carga_horaria_total',$curso->carga_horaria_total) }}" min="0" step="1"
                                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('carga_horaria_total') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Preço --}}
                            <div>
                                <label class="text-sm font-medium">Preço (R$)</label>
                                <input type="number" name="preco" value="{{ old('preco',$curso->preco) }}" min="0" step="0.01"
                                       class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0,00">
                                @error('preco') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Publicado --}}
                            <div class="flex items-center gap-3 mt-6">
                                <input id="publicado" type="checkbox" name="publicado" value="1"
                                       class="h-4 w-4 rounded border border-slate-300
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    {{ old('publicado',$curso->publicado) ? 'checked' : '' }}>
                                <label for="publicado" class="text-sm">Publicado (visível no catálogo)</label>
                            </div>

                            {{-- Descrição --}}
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Descrição</label>
                                <textarea name="descricao_completa" rows="8"
                                          class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Descrição completa do curso, objetivos, público-alvo...">{{ old('descricao_completa',$curso->descricao_completa) }}</textarea>
                                @error('descricao_completa') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                        </div>
                    </div>


                    {{-- Módulos & Aulas - call to action (apenas após salvar) --}}
                    @if($curso->exists)
                        <div class="rounded-xl border bg-white p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">Estrutura: Módulos & Aulas</h3>
                                <a href="{{ route('prof.cursos.modulos.index',$curso) }}" class="btn btn-outline">Gerenciar</a>
                            </div>
                            <p class="text-sm text-slate-600 mt-2">
                                Organize o conteúdo do curso em módulos e aulas. Você pode definir ordem, anexar vídeos/PDFs e liberar por gotejamento.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Coluna direita (capa + status) --}}
                <div class="space-y-4">
                    {{-- Capa --}}
                    <div class="rounded-xl border bg-white p-4 shadow-sm">
                        <h3 class="text-lg font-semibold">Capa do Curso</h3>
                        <p class="text-sm text-slate-600">Imagem de destaque (proporção 16:9 recomendada).</p>

                        <div class="mt-3">
                            <div class="aspect-video rounded-lg border bg-slate-50 overflow-hidden"
                                 style="--tw-aspect-h:9; --tw-aspect-w:16;">
                                <img id="previewCapa"
                                     src="{{ $curso->imagem_capa ? asset('storage/'.$curso->imagem_capa) : '' }}"
                                     class="w-full h-full object-cover {{ $curso->imagem_capa ? '' : 'hidden' }}">
                                <div id="placeholderCapa"
                                     class="w-full h-full flex items-center justify-center text-slate-400 {{ $curso->imagem_capa ? 'hidden' : '' }}">
                                    📷 Pré-visualização
                                </div>
                            </div>

                            <input type="file" name="imagem_capa" accept="image/*"
                                   class="mt-3 block w-full text-sm file:mr-4 file:py-2 file:px-3 file:rounded-md
                          file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            @error('imagem_capa') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror

                            @if($curso->exists && $curso->imagem_capa)
                                <p class="text-xs text-slate-500 mt-1">Atual: {{ $curso->imagem_capa }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Resumo do status --}}
                    <div class="rounded-xl border bg-white p-4 shadow-sm">
                        <h3 class="text-lg font-semibold">Status</h3>
                        <ul class="text-sm text-slate-600 mt-2 space-y-1">
                            <li>• {{ $curso->exists ? 'Curso em edição' : 'Novo curso' }}</li>
                            <li>• Visibilidade: <strong>{{ old('publicado',$curso->publicado) ? 'Publicado' : 'Rascunho' }}</strong></li>
                        </ul>
                        @if($curso->exists)
                            <a href="{{ route('site.curso.detalhe',$curso->slug ?? '#') }}"
                               class="btn btn-soft mt-3 w-full text-center">Ver página pública</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- Preview da capa --}}
    <script>
        const input = document.querySelector('input[name="capa"]');
        if (input) {
            input.addEventListener('change', e => {
                const [file] = e.target.files || [];
                if (!file) return;
                const img = document.getElementById('previewCapa');
                const ph  = document.getElementById('placeholderCapa');
                img.src = URL.createObjectURL(file);
                img.classList.remove('hidden');
                ph.classList.add('hidden');
            });
        }
    </script>
@endsection
