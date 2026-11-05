<div>
    <section class="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-10 sm:px-8 sm:py-14">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-blue-800">Área administrativa</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Empresas</h1>
                <p class="max-w-2xl text-sm leading-6 text-slate-600">Cadastre empresas, escolha as categorias e publique as informações que serão exibidas no diretório.</p>
            </div>
            @if (! $showForm)
                <button type="button" wire:click="create" class="rounded-full bg-blue-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900">Cadastrar empresa</button>
            @endif
        </div>

        @if (session('status'))
            <div role="status" class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm font-medium text-blue-900">{{ session('status') }}</div>
        @endif

        @if ($showForm)
            <form wire:submit="save" class="flex flex-col gap-7 rounded-3xl border border-stone-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="flex flex-col gap-2 border-b border-stone-100 pb-5">
                    <h2 class="text-xl font-semibold text-slate-900">{{ $editingCompanyId ? 'Editar empresa' : 'Nova empresa' }}</h2>
                    <p class="text-sm text-slate-500">Campos com * são obrigatórios.</p>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700 md:col-span-2">
                        Nome da empresa *
                        <input wire:model="name" type="text" maxlength="160" required class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('name') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700 md:col-span-2">
                        Descrição
                        <textarea wire:model="description" rows="3" maxlength="5000" class="resize-y rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10"></textarea>
                        @error('description') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Telefone
                        <input wire:model="phone" type="tel" maxlength="40" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('phone') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        WhatsApp
                        <input wire:model="whatsapp" type="tel" maxlength="40" placeholder="Ex.: (21) 99999-9999" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('whatsapp') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        E-mail
                        <input wire:model="email" type="email" maxlength="254" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('email') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Site
                        <input wire:model="website" type="url" maxlength="255" placeholder="https://empresa.com.br" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('website') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700 md:col-span-2">
                        Endereço
                        <input wire:model="address" type="text" maxlength="255" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('address') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Cidade
                        <input wire:model="city" type="text" maxlength="100" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('city') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Estado (UF)
                        <input wire:model="state" type="text" maxlength="2" placeholder="RJ" class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal uppercase text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('state') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="grid gap-5 rounded-2xl bg-stone-50 p-5 md:grid-cols-2">
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <h3 class="font-semibold text-slate-900">Instagram</h3>
                        <p class="text-sm leading-6 text-slate-600">A quantidade de seguidores fica registrada como um retrato do momento do cadastro. Alterar outros dados da empresa não muda essa data.</p>
                    </div>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Link do perfil
                        <input wire:model="instagramUrl" type="url" maxlength="255" placeholder="https://www.instagram.com/empresa/" class="rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('instagramUrl') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>

                    <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                        Seguidores no cadastro
                        <input wire:model="instagramFollowersAtRegistration" type="number" min="0" step="1" inputmode="numeric" class="rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                        @error('instagramFollowersAtRegistration') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                    </label>
                </div>

                <fieldset class="flex flex-col gap-3">
                    <legend class="mb-1 text-sm font-semibold text-slate-800">Categorias</legend>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($categories as $category)
                            <label class="flex items-start gap-3 rounded-xl border border-stone-200 bg-white p-3 text-sm text-slate-700">
                                <input wire:model="selectedCategoryIds" type="checkbox" value="{{ $category->id }}" class="mt-0.5 size-4 rounded border-stone-300 text-blue-800 focus:ring-blue-700">
                                <span class="flex flex-col gap-1">
                                    <span class="font-medium">{{ $category->name }}</span>
                                    @unless ($category->is_active)
                                        <span class="text-xs text-amber-700">Inativa — não aparece na consulta pública</span>
                                    @endunless
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500">Cadastre uma categoria antes de associá-la à empresa.</p>
                        @endforelse
                    </div>
                    @error('selectedCategoryIds.*') <span class="text-sm text-red-700">{{ $message }}</span> @enderror
                </fieldset>

                <label class="flex items-center gap-3 rounded-xl border border-stone-200 px-4 py-3 text-sm font-medium text-slate-700">
                    <input wire:model="isPublished" type="checkbox" class="size-4 rounded border-stone-300 text-blue-800 focus:ring-blue-700">
                    Publicar esta empresa no diretório
                </label>

                <div class="flex flex-wrap gap-3 border-t border-stone-100 pt-5">
                    <button type="submit" class="rounded-full bg-blue-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900">Salvar empresa</button>
                    <button type="button" wire:click="cancel" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-500">Cancelar</button>
                </div>
            </form>
        @else
            <div class="flex flex-col gap-5">
                <label class="flex max-w-lg flex-col gap-2 text-sm font-medium text-slate-700">
                    Buscar nos cadastros
                    <input wire:model.live.debounce.300ms="search" type="search" placeholder="Nome ou cidade" class="rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                </label>

                <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
                    <div class="divide-y divide-stone-100">
                        @forelse ($companies as $company)
                            <article class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                                <div class="flex min-w-0 flex-col gap-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-lg font-semibold text-slate-900">{{ $company->name }}</h2>
                                        @if ($company->is_published)
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800">Publicada</span>
                                        @else
                                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">Rascunho</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-500">{{ $company->city ?: 'Cidade não informada' }}@if ($company->state), {{ $company->state }}@endif</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($company->categories as $category)
                                            <span class="rounded-full bg-stone-100 px-3 py-1 text-xs text-slate-600">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="edit({{ $company->id }})" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-800 hover:text-blue-900">Editar</button>
                                    <button type="button" wire:click="togglePublication({{ $company->id }})" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-800 hover:text-blue-900">{{ $company->is_published ? 'Ocultar' : 'Publicar' }}</button>
                                    <button type="button" wire:click="delete({{ $company->id }})" wire:confirm="Remover esta empresa permanentemente?" class="rounded-full border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50">Remover</button>
                                </div>
                            </article>
                        @empty
                            <div class="px-6 py-12 text-center text-sm text-slate-500">Ainda não há empresas cadastradas.</div>
                        @endforelse
                    </div>
                </div>

                <div>{{ $companies->links() }}</div>
            </div>
        @endif
    </section>
</div>
