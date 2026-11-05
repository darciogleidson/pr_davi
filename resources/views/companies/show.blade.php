@extends('layouts.app', ['title' => $company->name])

@section('content')
<section class="mx-auto flex max-w-5xl flex-col gap-8 px-5 py-10 sm:px-8 sm:py-14">
    <a href="{{ route('home') }}" class="w-fit text-sm font-semibold text-blue-800 underline decoration-blue-300 underline-offset-4">← Voltar às empresas</a>

    <article class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
        <div class="flex flex-col gap-5 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 p-6 text-white sm:p-10">
            <div class="flex flex-wrap gap-2">
                @foreach ($company->categories as $category)
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-blue-50">{{ $category->name }}</span>
                @endforeach
            </div>
            <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">{{ $company->name }}</h1>
            @if ($company->city)
                <p class="text-sm text-blue-100">{{ $company->city }}@if ($company->state), {{ $company->state }}@endif</p>
            @endif
        </div>

        <div class="grid gap-8 p-6 sm:p-10 md:grid-cols-[1.2fr_0.8fr]">
            <div class="flex flex-col gap-7">
                @if ($company->description)
                    <div class="flex flex-col gap-3">
                        <h2 class="text-lg font-semibold text-slate-900">Sobre a empresa</h2>
                        <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $company->description }}</p>
                    </div>
                @endif

                @if ($company->address)
                    <div class="flex flex-col gap-2">
                        <h2 class="text-lg font-semibold text-slate-900">Endereço</h2>
                        <p class="text-sm leading-6 text-slate-600">{{ $company->address }}@if ($company->city), {{ $company->city }}@endif @if ($company->state), {{ $company->state }}@endif</p>
                    </div>
                @endif
            </div>

            <aside class="flex flex-col gap-4 rounded-2xl bg-stone-50 p-5 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-900">Contato</h2>

                @if ($company->phone)
                    <a href="tel:{{ preg_replace('/\D+/', '', $company->phone) }}" class="rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-blue-700 hover:text-blue-900">Telefone: {{ $company->phone }}</a>
                @endif

                @if ($company->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $company->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-blue-800 px-4 py-3 text-center text-sm font-semibold text-white transition hover:bg-blue-900">Conversar pelo WhatsApp</a>
                @endif

                @if ($company->email)
                    <a href="mailto:{{ $company->email }}" class="break-all rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-blue-700 hover:text-blue-900">{{ $company->email }}</a>
                @endif

                @if ($company->website)
                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="break-all rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-blue-700 hover:text-blue-900">Visitar site</a>
                @endif

                @if ($company->instagram_url)
                    <div class="flex flex-col gap-2 rounded-xl border border-stone-200 bg-white px-4 py-3">
                        <a href="{{ $company->instagram_url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-blue-800 underline decoration-blue-300 underline-offset-4">Instagram da empresa</a>
                        @if ($company->instagram_followers_at_registration !== null)
                            <p class="text-sm text-slate-700">{{ number_format($company->instagram_followers_at_registration, 0, ',', '.') }} seguidores informados no cadastro</p>
                            @if ($company->instagram_followers_recorded_at)
                                <p class="text-xs text-slate-500">Registro de {{ $company->instagram_followers_recorded_at->timezone(config('app.timezone'))->format('d/m/Y') }} às {{ $company->instagram_followers_recorded_at->timezone(config('app.timezone'))->format('H:i') }}</p>
                            @endif
                        @endif
                    </div>
                @endif

                @if (! $company->phone && ! $company->whatsapp && ! $company->email && ! $company->website && ! $company->instagram_url)
                    <p class="text-sm leading-6 text-slate-500">Ainda não há formas de contato cadastradas para esta empresa.</p>
                @endif
            </aside>
        </div>
    </article>
</section>
@endsection
