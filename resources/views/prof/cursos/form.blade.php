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
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Título *</label>
                                <input type="text" name="titulo" value="{{ old('titulo',$curso->titulo) }}"
                                       class="mt-1 w-full h-10 rounded-md border-slate-300"
                                       placeholder="Ex.: Segurança do Trabalho - NR10" required>
                                @error('titulo') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium">Preço (R$)</label>
                                <input type="number" name="preco" value="{{ old('preco',$curso->preco) }}"
                                       class="mt-1 w-full h-10 rounded-md border-slate-300" min="0" step="0.01" placeholder="0,00">
                                @error('preco') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium">Nível</label>
                                <input type="text" name="nivel" value="{{ old('nivel',$curso->nivel) }}"
                                       class="mt-1 w-full h-10 rounded-md border-slate-300" placeholder="Básico, Intermediário...">
                                @error('nivel') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium">Carga horária (h)</label>
                                <input type="number" name="carga_horaria" value="{{ old('carga_horaria',$curso->carga_horaria) }}"
                                       class="mt-1 w-full h-10 rounded-md border-slate-300" min="0" step="1">
                                @error('carga_horaria') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Resumo</label>
                                <textarea name="resumo" rows="3" class="mt-1 w-full rounded-md border-slate-300"
                                          placeholder="Breve resumo do curso...">{{ old('resumo',$curso->resumo) }}</textarea>
                                @error('resumo') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium">Descrição</label>
                                <textarea name="descricao" rows="8" class="mt-1 w-full rounded-md border-slate-300"
                                          placeholder="Descrição completa do curso, objetivos, público-alvo...">{{ old('descricao',$curso->descricao) }}</textarea>
                                @error('descricao') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="flex items-center gap-3 md:col-span-2 mt-2">
                                <input id="publicado" type="checkbox" name="publicado" value="1"
                                       class="h-4 w-4 rounded border-slate-300"
                                    {{ old('publicado',$curso->publicado) ? 'checked' : '' }}>
                                <label for="publicado" class="text-sm">Publicar curso</label>
                            </div>
                        </div>
                    </div>

                    {{-- Módulos & Aulas - CTA (apenas após salvar) --}}
                    @if($curso->exists)
                        <div class="rounded-xl border bg-white p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">Estrutura: Módulos & Aulas</h3>
                                <a href="{{ route('prof.cursos.modulos.index',$curso) }}" class="btn btn-outline">Gerenciar</a>
                            </div>
                            <p class="text-sm text-slate-600 mt-2">
                                Organize o conteúdo do curso em módulos e aulas. Defina ordem, anexos (vídeo/PDF) e liberação.
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
                                     src="{{ $curso->capa_path ? asset('storage/'.$curso->capa_path) : '' }}"
                                     class="w-full h-full object-cover {{ $curso->capa_path ? '' : 'hidden' }}">
                                <div id="placeholderCapa"
                                     class="w-full h-full flex items-center justify-center text-slate-400 {{ $curso->capa_path ? 'hidden' : '' }}">
                                    📷 Pré-visualização
                                </div>
                            </div>

                            <input type="file" name="capa" accept="image/*"
                                   class="mt-3 block w-full text-sm file:mr-4 file:py-2 file:px-3 file:rounded-md
                          file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            @error('capa') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror

                            @if($curso->exists && $curso->capa_path)
                                <p class="text-xs text-slate-500 mt-1">Atual: {{ $curso->capa_path }}</p>
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
