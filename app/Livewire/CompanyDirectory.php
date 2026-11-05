<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyDirectory extends Component
{
    use WithPagination;

    public string $search = '';

    public string $categorySlug = '';

    public string $city = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategorySlug(): void
    {
        $this->resetPage();
    }

    public function updatedCity(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $searchTerm = trim($this->search);

        $companies = Company::query()
            ->where('is_published', true)
            ->with(['categories' => static function (BelongsToMany $query): void {
                $query->where('is_active', true);
            }])
            ->when($searchTerm !== '', static function (Builder $query) use ($searchTerm): void {
                $query->where(static function (Builder $searchQuery) use ($searchTerm): void {
                    $searchQuery
                        ->where('name', 'like', '%'.$searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$searchTerm.'%');
                });
            })
            ->when($this->categorySlug !== '', function (Builder $query): void {
                $query->whereHas('categories', function (Builder $categoryQuery): void {
                    $categoryQuery
                        ->where('categories.slug', $this->categorySlug)
                        ->where('categories.is_active', true);
                });
            })
            ->when($this->city !== '', fn (Builder $query): Builder => $query->where('city', $this->city))
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(9);

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $cities = Company::query()
            ->where('is_published', true)
            ->whereNotNull('city')
            ->where('city', '<>', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('livewire.company-directory', [
            'companies' => $companies,
            'categories' => $categories,
            'cities' => $cities,
        ])->layout('layouts.app', ['title' => 'Encontre empresas da comunidade']);
    }
}
