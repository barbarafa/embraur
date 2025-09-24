@extends('layouts.app')
@section('title', $cupom->exists ? 'Editar Cupom' : 'Novo Cupom')

@section('content')
    <section class="container-page mx-auto py-6 max-w-3xl">
        @include('prof._tabs', ['active' => 'cupons'])

        {{-- Barra de navegação simples (como no form de curso) --}}
        <nav class="sticky top-0 z-20 -mx-4 mb-4 bg-white/80 backdrop-blur border-b">
            <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm">
                    <span class="px-3 py-1 rounded-full border">Cupom</span>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <a href="{{ route('prof.cupons.index') }}" class="btn btn-outline h-9">Cancelar</a>
                    <button type="submit" form="cupomForm" class="btn btn-primary h-9">
                        {{ $cupom->exists ? 'Salvar Alterações' : 'Criar Cupom' }}
                    </button>
                </div>
            </div>
        </nav>

        {{-- Cabeçalho / Voltar --}}
        <div class="mb-4 flex items-center justify-between px-1">
            <a href="{{ route('prof.cupons.index') }}" class="btn btn-outline">← Voltar</a>
            <div class="flex gap-2 md:hidden">
                <a href="{{ route('prof.cupons.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" form="cupomForm" class="btn btn-primary">
                    {{ $cupom->exists ? 'Salvar' : 'Criar' }}
                </button>
            </div>
        </div>

        {{-- Card do formulário (visual idêntico ao resto do painel) --}}
        <div class="rounded-xl border bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">
                    {{ $cupom->exists ? 'Editar Cupom' : 'Novo Cupom' }}
                </h2>
                <span class="text-xs text-slate-500">Defina código, tipo, valor e vigência</span>
            </div>

            <form id="cupomForm" method="POST"
                  action="{{ $cupom->exists ? route('prof.cupons.update',$cupom) : route('prof.cupons.store') }}">
                @csrf
                @if($cupom->exists) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Código --}}
                    <div class="md:col-span-1">
                        <label class="text-sm font-medium">Código *</label>
                        <input type="text" name="codigo" required
                               value="{{ old('codigo',$cupom->codigo) }}"
                               placeholder="EXEMPLO10"
                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                        @error('codigo') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tipo --}}
                    <div class="md:col-span-1">
                        <label class="text-sm font-medium">Tipo *</label>
                        @php $tipoSel = old('tipo',$cupom->tipo ?: 'fixo'); @endphp
                        <select name="tipo" class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                            <option value="fixo" {{ $tipoSel==='fixo'?'selected':'' }}>Fixo (R$)</option>
                            <option value="percentual" {{ $tipoSel==='percentual'?'selected':'' }}>Percentual (%)</option>
                        </select>
                        @error('tipo') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Valor --}}
                    <div class="md:col-span-1">
                        <label class="text-sm font-medium">Valor *</label>
                        <input type="number" step="0.01" name="valor" required
                               value="{{ old('valor',$cupom->valor) }}"
                               placeholder="Ex.: 10 ou 10.00"
                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                        <p class="text-xs text-slate-500 mt-1">
                            Use número inteiro para % (ex.: 10 = 10%) ou valor em R$ quando tipo for Fixo.
                        </p>
                        @error('valor') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Ativo --}}
                    <div class="md:col-span-1">
                        <label class="text-sm font-medium">Ativo</label>
                        @php $ativoSel = old('ativo', $cupom->ativo ? '1' : '0'); @endphp
                        <select name="ativo"
                                class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                            <option value="1" {{ $ativoSel==='1'?'selected':'' }}>Sim</option>
                            <option value="0" {{ $ativoSel==='0'?'selected':'' }}>Não</option>
                        </select>
                    </div>

                    {{-- Início --}}
                    <div class="md:col-span-1">
                        <label class="text-sm font-medium">Início (opcional)</label>
                        <input type="datetime-local" name="inicio_em"
                               value="{{ old('inicio_em', optional($cupom->inicio_em)->format('Y-m-d\TH:i')) }}"
                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                        @error('inicio_em') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Fim --}}
                    <div class="md:col-span-1">
                        <label class="text-sm font-medium">Fim (opcional)</label>
                        <input type="datetime-local" name="fim_em"
                               value="{{ old('fim_em', optional($cupom->fim_em)->format('Y-m-d\TH:i')) }}"
                               class="mt-1 w-full h-10 rounded-md border border-slate-300 px-3 focus:border-slate-400 focus:ring-2 focus:ring-slate-200">
                        @error('fim_em') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Ações (desktop escondidas, mobile mostra; a barra fixa abaixo cobre o principal) --}}
                <div class="mt-4 md:hidden">
                    <button class="btn">Salvar</button>
                    <a href="{{ route('prof.cupons.index') }}" class="btn-secondary ml-2">Cancelar</a>
                </div>
            </form>
        </div>

        {{-- Barra fixa inferior com ações (como no form de curso) --}}
        <div class="sticky bottom-0 z-20 mt-6 bg-white/80 backdrop-blur border-t">
            <div class="max-w-3xl mx-auto px-1 py-3 flex justify-end gap-2">
                <a href="{{ route('prof.cupons.index') }}" class="btn btn-outline h-9">Cancelar</a>
                <button type="submit" form="cupomForm" class="btn btn-primary h-9">
                    {{ $cupom->exists ? 'Salvar Alterações' : 'Criar Cupom' }}
                </button>
            </div>
        </div>

    </section>
@endsection
