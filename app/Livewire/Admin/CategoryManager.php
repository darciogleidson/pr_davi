<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public ?int $editingCategoryId = null;

    public string $search = '';

    public string $name = '';

    public bool $isActive = true;

    public bool $showForm = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $categoryId): void
    {
        $category = Category::query()->findOrFail($categoryId);

        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->isActive = $category->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($this->editingCategoryId),
            ],
            'isActive' => ['boolean'],
        ], [
            'name.required' => 'Informe o nome da categoria.',
            'name.unique' => 'Já existe uma categoria com esse nome.',
        ]);

        $category = $this->editingCategoryId === null
            ? new Category
            : Category::query()->findOrFail($this->editingCategoryId);

        $category->fill([
            'name' => trim($validated['name']),
            'slug' => $this->uniqueSlug($validated['name'], $category->exists ? $category->id : null),
            'is_active' => $validated['isActive'],
        ]);
        $category->save();

        session()->flash(
            'status',
            $this->editingCategoryId === null
                ? 'Categoria cadastrada com sucesso.'
                : 'Categoria atualizada com sucesso.',
        );

        $this->resetForm();
    }

    public function toggleActivation(int $categoryId): void
    {
        $category = Category::query()->findOrFail($categoryId);
        $category->is_active = ! $category->is_active;
        $category->save();

        session()->flash(
            'status',
            $category->is_active ? 'Categoria ativada.' : 'Categoria desativada.',
        );
    }

    public function delete(int $categoryId): void
    {
        Category::query()->findOrFail($categoryId)->delete();

        session()->flash('status', 'Categoria removida com sucesso.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function render(): View
    {
        $searchTerm = trim($this->search);

        $categories = Category::query()
            ->when($searchTerm !== '', static function (Builder $query) use ($searchTerm): void {
                $query->where('name', 'like', '%'.$searchTerm.'%');
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(10);

        return view('livewire.admin.category-manager', [
            'categories' => $categories,
        ])->layout('layouts.app', ['title' => 'Gerenciar categorias']);
    }

    private function uniqueSlug(string $name, ?int $categoryId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'categoria';
        $slug = $baseSlug;
        $suffix = 2;

        while (true) {
            $query = Category::query()->where('slug', $slug);

            if ($categoryId !== null) {
                $query->where('id', '!=', $categoryId);
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }
    }

    private function resetForm(): void
    {
        $this->reset(['editingCategoryId', 'name', 'isActive', 'showForm']);
        $this->isActive = true;
        $this->resetValidation();
    }
}
