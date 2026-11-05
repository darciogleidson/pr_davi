<?php

namespace Tests\Feature;

use App\Livewire\Admin\CategoryManager;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(CategoryManager::class)
            ->set('name', 'Depósitos')
            ->set('isActive', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Categoria cadastrada com sucesso.');

        $this->assertDatabaseHas('categories', [
            'name' => 'Depósitos',
            'slug' => 'depositos',
            'is_active' => true,
        ]);
    }

    public function test_category_name_must_be_unique(): void
    {
        $admin = User::factory()->create();
        Category::factory()->create([
            'name' => 'Hotéis',
            'slug' => 'hoteis',
        ]);

        Livewire::actingAs($admin)
            ->test(CategoryManager::class)
            ->set('name', 'Hotéis')
            ->call('save')
            ->assertHasErrors('name');

        $this->assertDatabaseCount('categories', 1);
    }
}
