<div class="mt-6">
    <h1 class="text-2xl font-semibold">Olá, {{ $aluno->nome_completo ?? 'Aluno' }}! 👋</h1>
    <p class="text-slate-600 mt-1">Bem-vindo de volta à sua jornada de aprendizado.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
    <div class="rounded-xl border bg-white p-4 shadow-sm">
        <div class="text-sm text-slate-600">Cursos</div>
        <div class="text-2xl font-semibold mt-1">{{ $stats['cursos'] ?? 0 }}</div>
    </div>
    <div class="rounded-xl border bg-white p-4 shadow-sm">
        <div class="text-sm text-slate-600">Concluídos</div>
        <div class="text-2xl font-semibold mt-1">{{ $stats['concluidos'] ?? 0 }}</div>
    </div>
    <div class="rounded-xl border bg-white p-4 shadow-sm">
        <div class="text-sm text-slate-600">Horas Estudadas</div>
        <div class="text-2xl font-semibold mt-1">{{ $stats['horas'] ?? 0 }}h</div>
    </div>
    <div class="rounded-xl border bg-white p-4 shadow-sm">
        <div class="text-sm text-slate-600">Progresso Geral</div>
        <div class="text-2xl font-semibold mt-1">{{ $stats['progressoGeral'] ?? 0 }}%</div>
    </div>
</div>

<div class="mt-4">
    <div class="flex gap-2 border rounded-xl bg-white p-1 w-full overflow-x-auto">
        <a href="{{ route('aluno.dashboard') }}"
           class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('aluno.dashboard') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">Visão Geral</a>
        <a href="{{ route('aluno.cursos') }}"
           class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('aluno.cursos') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">Meus Cursos</a>
        <a href="{{ route('aluno.certificados') }}"
           class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('aluno.certificados') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">Certificados</a>
        <a href="{{ route('aluno.pagamentos') }}"
           class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('aluno.pagamentos') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">Pagamentos</a>
        <a href="{{ route('aluno.perfil') }}"
           class="px-4 py-2 rounded-lg text-sm {{ request()->routeIs('aluno.perfil') ? 'bg-gray-100 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">Perfil</a>
    </div>
</div>
