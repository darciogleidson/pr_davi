<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyManager extends Component
{
    use WithPagination;

    public ?int $editingCompanyId = null;

    public string $search = '';

    public string $name = '';

    public ?string $description = null;

    public ?string $phone = null;

    public ?string $whatsapp = null;

    public ?string $email = null;

    public ?string $website = null;

    public ?string $address = null;

    public ?string $city = null;

    public ?string $state = null;

    public ?string $instagramUrl = null;

    public ?string $instagramFollowersAtRegistration = null;

    /** @var array<int, int|string> */
    public array $selectedCategoryIds = [];

    public bool $isPublished = false;

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

    public function edit(int $companyId): void
    {
        $company = Company::query()->with('categories')->findOrFail($companyId);

        $this->editingCompanyId = $company->id;
        $this->name = $company->name;
        $this->description = $company->description;
        $this->phone = $company->phone;
        $this->whatsapp = $company->whatsapp;
        $this->email = $company->email;
        $this->website = $company->website;
        $this->address = $company->address;
        $this->city = $company->city;
        $this->state = $company->state;
        $this->instagramUrl = $company->instagram_url;
        $this->instagramFollowersAtRegistration = $company->instagram_followers_at_registration === null
            ? null
            : (string) $company->instagram_followers_at_registration;
        $this->selectedCategoryIds = $company->categories->modelKeys();
        $this->isPublished = $company->is_published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->normalizeOptionalFields();

        if ($this->instagramUrl === null && $this->instagramFollowersAtRegistration !== null) {
            $this->addError('instagramUrl', 'Informe o perfil do Instagram para registrar a quantidade de seguidores.');

            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:5000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:254'],
            'website' => ['nullable', 'url', 'max:255', 'regex:/^https?:\/\//i'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'size:2'],
            'instagramUrl' => [
                'nullable',
                'url',
                'max:255',
                'regex:/^https?:\/\/(?:www\.)?instagram\.com\/[A-Za-z0-9._]+\/?$/i',
            ],
            'instagramFollowersAtRegistration' => [
                'nullable',
                'integer',
                'min:0',
                'required_with:instagramUrl',
            ],
            'selectedCategoryIds' => ['array'],
            'selectedCategoryIds.*' => ['integer', 'distinct', 'exists:categories,id'],
            'isPublished' => ['boolean'],
        ], [
            'name.required' => 'Informe o nome da empresa.',
            'website.regex' => 'O site deve começar com http:// ou https://.',
            'instagramUrl.regex' => 'Informe um link válido de perfil do Instagram.',
            'instagramFollowersAtRegistration.required_with' => 'Informe os seguidores atuais do perfil.',
            'instagramFollowersAtRegistration.integer' => 'A quantidade de seguidores deve ser um número inteiro.',
            'instagramFollowersAtRegistration.min' => 'A quantidade de seguidores não pode ser negativa.',
        ]);

        $company = $this->editingCompanyId === null
            ? new Company
            : Company::query()->findOrFail($this->editingCompanyId);

        $followersCount = isset($validated['instagramFollowersAtRegistration'])
            ? (int) $validated['instagramFollowersAtRegistration']
            : null;

        if ($followersCount === null) {
            $followersRecordedAt = null;
        } elseif ($this->instagramSnapshotIsUnchanged($company, $validated, $followersCount)) {
            $followersRecordedAt = $company->instagram_followers_recorded_at;
        } else {
            $followersRecordedAt = now();
        }

        $company->fill([
            'name' => trim($validated['name']),
            'slug' => $this->uniqueSlug($validated['name'], $company->exists ? $company->id : null),
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => isset($validated['state']) ? mb_strtoupper($validated['state']) : null,
            'instagram_url' => $validated['instagramUrl'] ?? null,
            'instagram_followers_at_registration' => $followersCount,
            'instagram_followers_recorded_at' => $followersRecordedAt,
            'is_published' => $validated['isPublished'],
        ]);
        $company->save();
        $company->categories()->sync($validated['selectedCategoryIds'] ?? []);

        session()->flash(
            'status',
            $this->editingCompanyId === null
                ? 'Empresa cadastrada com sucesso.'
                : 'Empresa atualizada com sucesso.',
        );

        $this->resetForm();
    }

    public function togglePublication(int $companyId): void
    {
        $company = Company::query()->findOrFail($companyId);
        $company->is_published = ! $company->is_published;
        $company->save();

        session()->flash(
            'status',
            $company->is_published ? 'Empresa publicada.' : 'Empresa ocultada do diretório.',
        );
    }

    public function delete(int $companyId): void
    {
        Company::query()->findOrFail($companyId)->delete();

        session()->flash('status', 'Empresa removida com sucesso.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function render(): View
    {
        $searchTerm = trim($this->search);

        $companies = Company::query()
            ->with('categories')
            ->when($searchTerm !== '', static function (Builder $query) use ($searchTerm): void {
                $query->where('name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('city', 'like', '%'.$searchTerm.'%');
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(10);

        return view('livewire.admin.company-manager', [
            'companies' => $companies,
            'categories' => Category::query()->orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Gerenciar empresas']);
    }

    private function normalizeOptionalFields(): void
    {
        foreach ([
            'description',
            'phone',
            'whatsapp',
            'email',
            'website',
            'address',
            'city',
            'state',
            'instagramUrl',
            'instagramFollowersAtRegistration',
        ] as $field) {
            if ($this->{$field} !== null) {
                $trimmedValue = trim($this->{$field});
                $this->{$field} = $trimmedValue === '' ? null : $trimmedValue;
            }
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function instagramSnapshotIsUnchanged(Company $company, array $validated, int $followersCount): bool
    {
        return $company->exists
            && $company->instagram_url === ($validated['instagramUrl'] ?? null)
            && $company->instagram_followers_at_registration === $followersCount
            && $company->instagram_followers_recorded_at !== null;
    }

    private function uniqueSlug(string $name, ?int $companyId = null): string
    {
        $baseSlug = Str::slug($name) ?: 'empresa';
        $slug = $baseSlug;
        $suffix = 2;

        while (true) {
            $query = Company::query()->where('slug', $slug);

            if ($companyId !== null) {
                $query->where('id', '!=', $companyId);
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
        $this->reset([
            'editingCompanyId',
            'name',
            'description',
            'phone',
            'whatsapp',
            'email',
            'website',
            'address',
            'city',
            'state',
            'instagramUrl',
            'instagramFollowersAtRegistration',
            'selectedCategoryIds',
            'isPublished',
            'showForm',
        ]);
        $this->resetValidation();
    }
}
