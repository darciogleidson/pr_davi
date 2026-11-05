<?php

namespace Tests\Feature;

use App\Livewire\Admin\CompanyManager;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCompanyManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_company_page_redirects_guests_to_login(): void
    {
        $this->get(route('admin.companies.index'))
            ->assertRedirectToRoute('login');
    }

    public function test_admin_login_page_uses_the_application_layout(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<!DOCTYPE html>', false)
            ->assertSee('Acesso responsável | Guia de Empresas da Igreja')
            ->assertSee('text-emerald-800');
    }

    public function test_public_user_registration_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_responsible_can_log_in_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'responsavel@igreja.org',
            'password' => 'password',
        ]);

        $this->withoutMiddleware(ThrottleRequests::class)
            ->withSession(['_token' => 'test-token'])
            ->from(route('login'))
            ->post(route('login.store'), [
                '_token' => 'test-token',
                'email' => 'responsavel@igreja.org',
                'password' => 'password',
            ])->assertRedirectToRoute('admin.companies.index');

        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_login_returns_an_error_for_the_email_field(): void
    {
        Auth::logout();
        session()->flush();
        $this->actingAsGuest();
        $this->assertGuest();

        $response = $this->withoutMiddleware(ThrottleRequests::class)
            ->withSession(['_token' => 'test-token'])
            ->from(route('login'))
            ->post(route('login.store'), [
                '_token' => 'test-token',
                'email' => 'responsavel@igreja.org',
                'password' => 'senha-incorreta',
            ]);

        $response->assertRedirectToRoute('login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_create_company_with_instagram_follower_snapshot(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create([
            'name' => 'Pousadas',
            'slug' => 'pousadas',
        ]);

        $this->travelTo('2026-11-05 10:15:00');

        Livewire::actingAs($admin)
            ->test(CompanyManager::class)
            ->set('name', 'Pousada do Vale')
            ->set('description', 'Hospedagem familiar próxima à igreja.')
            ->set('city', 'Niterói')
            ->set('state', 'RJ')
            ->set('instagramUrl', 'https://www.instagram.com/pousadadovale/')
            ->set('instagramFollowersAtRegistration', '12500')
            ->set('selectedCategoryIds', [$category->id])
            ->set('isPublished', true)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Empresa cadastrada com sucesso.');

        $company = Company::query()->where('name', 'Pousada do Vale')->firstOrFail();

        $this->assertSame('pousada-do-vale', $company->slug);
        $this->assertSame(12500, $company->instagram_followers_at_registration);
        $this->assertSame('2026-11-05 10:15:00', $company->instagram_followers_recorded_at->format('Y-m-d H:i:s'));
        $this->assertTrue($company->is_published);
        $this->assertTrue($company->categories->contains($category));
    }

    public function test_instagram_profile_requires_follower_count(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(CompanyManager::class)
            ->set('name', 'Pousada sem contagem')
            ->set('instagramUrl', 'https://www.instagram.com/pousadasemcontagem/')
            ->set('instagramFollowersAtRegistration', '')
            ->call('save')
            ->assertHasErrors('instagramFollowersAtRegistration');

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_follower_count_requires_an_instagram_profile(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(CompanyManager::class)
            ->set('name', 'Pousada sem perfil')
            ->set('instagramFollowersAtRegistration', '35')
            ->call('save')
            ->assertHasErrors('instagramUrl');

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_company_website_must_use_http_or_https(): void
    {
        $admin = User::factory()->create();

        Livewire::actingAs($admin)
            ->test(CompanyManager::class)
            ->set('name', 'Empresa com site inválido')
            ->set('website', 'javascript:alert(1)')
            ->call('save')
            ->assertHasErrors(['website' => ['regex']]);

        $this->assertDatabaseCount('companies', 0);
    }

    public function test_editing_other_company_fields_preserves_instagram_snapshot_time(): void
    {
        $admin = User::factory()->create();
        $company = Company::factory()->create([
            'name' => 'Pousada da Serra',
            'instagram_url' => 'https://www.instagram.com/pousadadaserra/',
            'instagram_followers_at_registration' => 842,
            'instagram_followers_recorded_at' => '2026-11-01 08:00:00',
        ]);

        Livewire::actingAs($admin)
            ->test(CompanyManager::class)
            ->call('edit', $company->id)
            ->set('description', 'Descrição atualizada.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(
            '2026-11-01 08:00:00',
            $company->fresh()->instagram_followers_recorded_at->format('Y-m-d H:i:s'),
        );
    }
}
