<header class="sticky top-0 z-40 border-b bg-white/90 backdrop-blur">
    <div class="container-page h-14 sm:h-16 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('site.home') }}" class="flex items-center gap-2 font-semibold">
            <span class="inline-block h-5 w-5 rounded-sm bg-blue-600"></span>
            <span>EAD Pro</span>
        </a>

        {{-- Menu --}}
        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('site.home') }}" class="{{ request()->routeIs('site.home') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">Início</a>
            <a href="{{ route('site.cursos') }}" class="{{ request()->routeIs('site.cursos') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600' }}">Cursos</a>
            <a href="{{ route('site.home') }}#sobre" class="hover:text-blue-600">Sobre</a>
            <a href="{{ route('site.home') }}#contato" class="hover:text-blue-600">Contato</a>
        </nav>

        {{-- Ações --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('portal.aluno') }}" class="btn-outline">Portal do Aluno</a>
            <a href="{{ route('portal.professor') }}" class="btn-primary">Portal do Professor</a>
        </div>
    </div>
</header>
