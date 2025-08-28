<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data
      x-bind:data-theme="localStorage.getItem('theme') ?? 'light'">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title')@yield('title') · @endif{{ config('app.name', 'Laravel') }}</title>

    {{-- SEO básico / Open Graph --}}
    <meta name="description" content="@yield('meta_description', config('app.name').' – Aplicação')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', config('app.name').' – Aplicação')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Segurança --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire --}}
    @if(class_exists(\Livewire\Livewire::class))
        @livewireStyles
    @endif

    {{-- Estilos extras --}}
    @stack('styles')

    <style>[x-cloak]{display:none !important}</style>

    {{-- Head extra --}}
    @stack('head')
</head>
<body class="min-h-full antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">

{{-- Navbar dinâmica --}}
@hasSection('navbar')
    @yield('navbar')
@else
    @if (request()->routeIs('site.*'))
        {{-- Navbar pública do site --}}
        @includeIf('site.navbar')
    @else
        {{-- Navbar padrão (dashboard/admin) --}}
        <header class="border-b bg-white/70 backdrop-blur dark:bg-gray-900/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="font-semibold tracking-tight">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <nav class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="hover:underline">Início</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hover:underline">Dashboard</a>
                    @endauth
                </nav>
            </div>
        </header>
    @endif
@endif

{{-- Flash messages --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4 space-y-2">
    @includeWhen(View::exists('partials.flash'), 'partials.flash')

    @if(session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-100">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-100">
            <strong>Ops!</strong> Corrija os erros abaixo.
        </div>
    @endif
</div>

{{-- Page header/actions --}}
@if(View::hasSection('page_header') || View::hasSection('page_actions'))
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>@yield('page_header')</div>
            <div class="flex items-center gap-2">@yield('page_actions')</div>
        </div>
    </div>
@endif

{{-- Conteúdo principal --}}
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    @yield('content')
</main>

{{-- Footer --}}
@hasSection('footer')
    @yield('footer')
@else
    <footer class="border-t bg-white/70 backdrop-blur dark:bg-gray-900/70">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-sm text-gray-500 dark:text-gray-400">
            © {{ now()->year }} {{ config('app.name', 'Laravel') }}. Todos os direitos reservados.
        </div>
    </footer>
@endif

{{-- Livewire Scripts --}}
@if(class_exists(\Livewire\Livewire::class))
    @livewireScripts
@endif

{{-- Scripts extras --}}
@stack('scripts')
</body>
</html>
