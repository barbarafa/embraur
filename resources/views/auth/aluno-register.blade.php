@extends('layouts.app')
@section('title','Cadastro de Aluno')

@section('content')
    <section class="mx-auto container-page px-4 py-12">
        <div class="max-w-md mx-auto card p-6">
            <h1 class="text-xl font-bold mb-3">Criar conta</h1>
            <form method="post" action="{{ route('aluno.register.do') }}" class="space-y-3">
                @csrf

                <input type="hidden" name="intended" value="{{ $intended }}">
                <input type="hidden" name="curso" value="{{ $curso }}">

                <div>
                    <label class="text-sm">Nome</label>
                    <input name="nome" value="{{ old('nome') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('nome')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">CPF</label>
                    <input
                        id="cpf"
                        name="cpf"
                        value="{{ old('cpf') }}"
                        class="w-full px-3 py-2 border rounded-md"
                        placeholder="000.000.000-00"
                        inputmode="numeric"
                        maxlength="14">
                    @error('cpf')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">Celular</label>
                    <input
                        id="telefone"
                        name="telefone"
                        value="{{ old('telefone') }}"
                        class="w-full px-3 py-2 border rounded-md"
                        placeholder="(00) 90000-0000"
                        inputmode="tel"
                        maxlength="15">
                    @error('telefone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">Data Nascimento</label>
                    <input
                        id="data_nascimento"
                        name="data_nascimento"
                        value="{{ old('data_nascimento') }}"
                        class="w-full px-3 py-2 border rounded-md"
                        placeholder="00/00/0000"
                        inputmode="numeric"
                        maxlength="10"
                        autocomplete="bday">
                    @error('data_nascimento')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>


                <div>
                    <label class="text-sm">E-mail</label>
                    <input name="email" type="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-md">
                    @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">Senha</label>
                    <input name="password" type="password" class="w-full px-3 py-2 border rounded-md">
                    @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm">Confirmar Senha</label>
                    <input name="password_confirmation" type="password" class="w-full px-3 py-2 border rounded-md">
                </div>

                <button class="btn btn-primary w-full">Cadastrar</button>
            </form>
        </div>
    </section>
@endsection


{{-- IMask via CDN --}}
@push('scripts')
    {{-- IMask via CDN (mais confiável que unpkg em algumas redes) --}}
    <script src="https://cdn.jsdelivr.net/npm/imask"></script>
    <script>
        (function initMasksWhenReady(){
            function init(){
                const IM = window.IMask;
                if(!IM) return; // CDN não carregou

                const cpfEl  = document.getElementById('cpf');
                const foneEl = document.getElementById('telefone');
                const dataEl = document.getElementById('data_nascimento');

                if (cpfEl)  IM(cpfEl,  { mask: '000.000.000-00' });
                if (foneEl) IM(foneEl, {
                    mask: [
                        { mask: '(00) 0000-0000' },   // 10 dígitos
                        { mask: '(00) 00000-0000' }   // 11 dígitos (celular)
                    ],
                    dispatch: function (appended, m) {
                        const number = (m.value + appended).replace(/\D/g,'');
                        return number.length > 10 ? m.compiledMasks[1] : m.compiledMasks[0];
                    }
                });
                if (dataEl) IM(dataEl, { mask: '00/00/0000' });
            }

            // funciona com páginas estáticas, Turbo/Livewire, etc.
            document.addEventListener('DOMContentLoaded', init);
            window.addEventListener('turbo:load', init);
            document.addEventListener('livewire:navigated', init);
        })();
    </script>
@endpush


