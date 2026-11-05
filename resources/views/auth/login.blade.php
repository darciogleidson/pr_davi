@extends('layouts.app', ['title' => 'Acesso responsável'])

@section('content')
<div class="mx-auto flex min-h-[70vh] max-w-6xl items-center justify-center px-5 py-12 sm:px-8">
    <div class="grid w-full max-w-4xl overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-xl shadow-slate-900/5 md:grid-cols-2">
        <div class="flex flex-col justify-between gap-10 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 p-8 text-white sm:p-10">
            <div class="flex flex-col gap-5">
                <span class="flex size-12 items-center justify-center rounded-2xl bg-white/10 text-sm font-bold tracking-wide">GC</span>
                <div class="flex flex-col gap-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-emerald-200">Área administrativa</p>
                    <h1 class="text-3xl font-semibold tracking-tight">Acesse os cadastros do guia.</h1>
                    <p class="text-sm leading-6 text-emerald-100/85">Entre com a conta do responsável para manter empresas e categorias atualizadas.</p>
                </div>
            </div>
            <a href="{{ route('home') }}" class="w-fit text-sm font-semibold text-emerald-100 underline decoration-emerald-300/60 underline-offset-4">Voltar ao diretório</a>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col justify-center gap-6 p-8 sm:p-10">
            @csrf
            <div class="flex flex-col gap-2">
                <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Entrar</h2>
                <p class="text-sm text-slate-500">Use o e-mail e a senha do responsável.</p>
            </div>

            <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                E-mail
                <input name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10">
                @error('email') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
            </label>

            <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                Senha
                <input name="password" type="password" autocomplete="current-password" required class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-emerald-700 focus:ring-4 focus:ring-emerald-700/10">
                @error('password') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
            </label>

            <label class="flex items-center gap-3 text-sm text-slate-600">
                <input name="remember" type="checkbox" value="1" class="size-4 rounded border-stone-300 text-emerald-800 focus:ring-emerald-700">
                Manter conectado neste dispositivo
            </label>

            <button type="submit" class="rounded-full bg-emerald-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-900">Entrar na administração</button>
        </form>
    </div>
</div>
@endsection
