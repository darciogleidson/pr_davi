<div>
    <section class="overflow-hidden bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:px-8 sm:py-20 lg:grid-cols-[1fr_0.9fr] lg:items-center lg:py-24">
            <div class="flex flex-col gap-6">
                <span class="w-fit rounded-full border border-blue-300/30 bg-blue-300/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-blue-100">Guia local da igreja</span>
                <div class="flex flex-col gap-4">
                    <h1 class="max-w-2xl text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl">Encontre empresas que atendem a nossa comunidade.</h1>
                    <p class="max-w-xl text-base leading-7 text-blue-100/85 sm:text-lg">Pesquise por serviço, categoria ou cidade e encontre contatos úteis para o seu dia a dia.</p>
                </div>
                <div class="flex flex-wrap gap-3 text-sm text-blue-100/85">
                    <span class="rounded-full bg-white/10 px-4 py-2">Comércio local</span>
                    <span class="rounded-full bg-white/10 px-4 py-2">Serviços</span>
                    <span class="rounded-full bg-white/10 px-4 py-2">Hospedagem</span>
                </div>
            </div>

            <div class="rounded-3xl border border-white/15 bg-white p-5 text-slate-900 shadow-2xl shadow-black/20 sm:p-7">
                <div class="mb-5 flex flex-col gap-2">
                    <p class="text-sm font-semibold text-blue-800">Busca de empresas</p>
                    <h2 class="text-2xl font-semibold tracking-tight">O que você está procurando?</h2>
                </div>

                <div class="grid gap-4">
                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Nome ou serviço
                        <input
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Ex.: hotel, oficina, depósito"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10"
                        >
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                            Categoria
                            <select wire:model.live="categorySlug" class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none transition focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                                <option value="">Todas as categorias</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                            Cidade
                            <select wire:model.live="city" class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none transition focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                                <option value="">Todas as cidades</option>
                                @foreach ($cities as $availableCity)
                                    <option value="{{ $availableCity }}">{{ $availableCity }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-10 sm:px-8 sm:py-14">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-blue-800">Diretório</p>
                <h2 class="text-3xl font-semibold tracking-tight text-slate-900">Empresas da comunidade</h2>
            </div>
            <p class="text-sm text-slate-500">{{ number_format($companies->total(), 0, ',', '.') }} {{ $companies->total() === 1 ? 'empresa encontrada' : 'empresas encontradas' }}</p>
        </div>

        @if ($companies->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($companies as $company)
                    <article class="flex h-full flex-col justify-between gap-6 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex flex-col gap-2">
                                    <h3 class="text-xl font-semibold leading-snug text-slate-900">{{ $company->name }}</h3>
                                    @if ($company->city)
                                        <p class="flex items-center gap-2 text-sm text-slate-500">
                                            <span aria-hidden="true">⌖</span>
                                            <span>{{ $company->city }}@if ($company->state), {{ $company->state }}@endif</span>
                                        </p>
                                    @endif
                                </div>
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-lg text-blue-800" aria-hidden="true">✦</span>
                            </div>

                            @if ($company->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($company->categories as $category)
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            @if ($company->description)
                                <p class="text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($company->description, 170) }}</p>
                            @endif

                            @if ($company->instagram_url)
                                <div class="flex flex-col gap-1 rounded-2xl bg-stone-50 px-4 py-3">
                                    <a href="{{ $company->instagram_url }}" target="_blank" rel="noopener noreferrer" class="w-fit text-sm font-semibold text-blue-800 underline decoration-blue-300 underline-offset-4">Instagram da empresa</a>
                                    @if ($company->instagram_followers_at_registration !== null)
                                        <p class="text-sm text-slate-600">{{ number_format($company->instagram_followers_at_registration, 0, ',', '.') }} seguidores informados no cadastro</p>
                                        @if ($company->instagram_followers_recorded_at)
                                            <p class="text-xs text-slate-500">Registro de {{ $company->instagram_followers_recorded_at->timezone(config('app.timezone'))->format('d/m/Y') }} às {{ $company->instagram_followers_recorded_at->timezone(config('app.timezone'))->format('H:i') }}</p>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-3 border-t border-stone-100 pt-5">
                            <a href="{{ route('companies.show', ['company' => $company->slug]) }}" class="rounded-full bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-900">Ver contatos</a>
                            @if ($company->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $company->whatsapp) }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-stone-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-800 hover:text-blue-900">WhatsApp</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div>{{ $companies->links() }}</div>
        @else
            <div class="flex flex-col items-center gap-4 rounded-3xl border border-dashed border-stone-300 bg-white px-6 py-14 text-center">
                <span class="flex size-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-800" aria-hidden="true">⌕</span>
                <div class="flex flex-col gap-2">
                    <h3 class="text-lg font-semibold text-slate-900">Nenhuma empresa encontrada</h3>
                    <p class="max-w-md text-sm leading-6 text-slate-500">Tente mudar o termo da busca ou selecionar outra categoria ou cidade.</p>
                </div>
            </div>
        @endif
    </section>
</div>
