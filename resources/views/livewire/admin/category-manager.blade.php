<div>
    <section class="mx-auto flex max-w-7xl flex-col gap-8 px-5 py-10 sm:px-8 sm:py-14">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-2">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-blue-800">Área administrativa</p>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Categorias</h1>
                <p class="max-w-2xl text-sm leading-6 text-slate-600">Organize o diretório por tipos de empresa. Categorias inativas deixam de aparecer nos filtros públicos.</p>
            </div>
            @if (! $showForm)
                <button type="button" wire:click="create" class="rounded-full bg-blue-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900">Cadastrar categoria</button>
            @endif
        </div>

        @if (session('status'))
            <div role="status" class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-sm font-medium text-blue-900">{{ session('status') }}</div>
        @endif

        @if ($showForm)
            <form wire:submit="save" class="flex max-w-2xl flex-col gap-5 rounded-3xl border border-stone-200 bg-white p-5 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-slate-900">{{ $editingCategoryId ? 'Editar categoria' : 'Nova categoria' }}</h2>
                <label class="flex flex-col gap-2 text-sm font-medium text-slate-700">
                    Nome da categoria *
                    <input wire:model="name" type="text" maxlength="100" required class="rounded-xl border border-stone-300 px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                    @error('name') <span class="text-sm font-normal text-red-700">{{ $message }}</span> @enderror
                </label>
                <label class="flex items-center gap-3 text-sm font-medium text-slate-700">
                    <input wire:model="isActive" type="checkbox" class="size-4 rounded border-stone-300 text-blue-800 focus:ring-blue-700">
                    Categoria ativa
                </label>
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="rounded-full bg-blue-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-900">Salvar categoria</button>
                    <button type="button" wire:click="cancel" class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-500">Cancelar</button>
                </div>
            </form>
        @else
            <div class="flex flex-col gap-5">
                <label class="flex max-w-lg flex-col gap-2 text-sm font-medium text-slate-700">
                    Buscar categoria
                    <input wire:model.live.debounce.300ms="search" type="search" placeholder="Nome da categoria" class="rounded-xl border border-stone-300 bg-white px-4 py-3 text-base font-normal text-slate-900 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-700/10">
                </label>

                <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
                    <div class="divide-y divide-stone-100">
                        @forelse ($categories as $category)
                            <article class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                                <div class="flex items-center gap-3">
                                    <h2 class="font-semibold text-slate-900">{{ $category->name }}</h2>
                                    @if ($category->is_active)
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800">Ativa</span>
                                    @else
                                        <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-slate-500">Inativa</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="edit({{ $category->id }})" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-800 hover:text-blue-900">Editar</button>
                                    <button type="button" wire:click="toggleActivation({{ $category->id }})" class="rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-800 hover:text-blue-900">{{ $category->is_active ? 'Desativar' : 'Ativar' }}</button>
                                    <button type="button" wire:click="delete({{ $category->id }})" wire:confirm="Remover esta categoria? As empresas associadas deixarão de exibi-la." class="rounded-full border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50">Remover</button>
                                </div>
                            </article>
                        @empty
                            <div class="px-6 py-12 text-center text-sm text-slate-500">Nenhuma categoria encontrada.</div>
                        @endforelse
                    </div>
                </div>

                <div>{{ $categories->links() }}</div>
            </div>
        @endif
    </section>
</div>
