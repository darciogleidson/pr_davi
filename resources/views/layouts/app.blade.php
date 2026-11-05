<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#064e3b">
        <title>{{ $title ?? config('app.name') }} | {{ config('app.name') }}</title>
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-stone-50 font-sans text-slate-900 antialiased">
        <header class="border-b border-stone-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 sm:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-2xl bg-emerald-800 text-sm font-bold tracking-wide text-white">GC</span>
                    <span class="flex flex-col">
                        <span class="text-sm font-semibold text-slate-900">{{ config('app.name') }}</span>
                        <span class="text-xs text-slate-500">Serviços da comunidade</span>
                    </span>
                </a>

                <nav class="flex items-center gap-3 text-sm font-medium">
                    <a href="{{ route('home') }}" class="rounded-full px-3 py-2 text-slate-600 transition hover:bg-stone-100 hover:text-emerald-900 sm:px-4">Empresas</a>
                    @auth
                        <a href="{{ route('admin.companies.index') }}" class="rounded-full px-3 py-2 text-slate-600 transition hover:bg-stone-100 hover:text-emerald-900 sm:px-4">Painel</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full border border-stone-300 px-4 py-2 text-slate-700 transition hover:border-emerald-800 hover:text-emerald-900">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-stone-300 px-4 py-2 text-slate-700 transition hover:border-emerald-800 hover:text-emerald-900">Acesso responsável</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main>
            @isset($slot)
                {{ $slot }}
            @endisset
            @yield('content')
        </main>

        <footer class="border-t border-stone-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 py-7 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <p>{{ config('app.name') }}</p>
                <p>Um guia local para fortalecer a comunidade.</p>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
