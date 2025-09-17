{{-- resources/views/prof/_tabs.blade.php --}}
<div class="rounded-xl border bg-white p-1 mt-4 flex items-center gap-2 text-sm overflow-x-auto">
    {{-- Visão Geral --}}
    <a href="{{ route('prof.dashboard') }}"
       class="px-4 py-2 rounded-lg {{ ($active ?? '')==='dashboard' ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
        Visão Geral
    </a>

    {{-- Meus Cursos.php --}}
    <a href="{{ route('prof.cursos.index') }}"
       class="px-4 py-2 rounded-lg {{ ($active ?? '')==='cursos' ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
        Meus Cursos
    </a>

    {{-- Alunos --}}
    <a href="{{ route('prof.alunos.index') }}"
       class="px-4 py-2 rounded-lg {{ ($active ?? '')==='alunos' ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
        Alunos
    </a>


    {{-- Relatórios (desativado por enquanto) --}}
    <span class="px-4 py-2 rounded-lg text-gray-400 cursor-default">Relatórios</span>
</div>
