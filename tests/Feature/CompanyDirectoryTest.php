<?php

namespace Tests\Feature;

use App\Livewire\CompanyDirectory;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyDirectoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_public_directory_hides_unpublished_companies_and_displays_instagram_snapshot(): void
    {
        $category = Category::factory()->create([
            'name' => 'Pousadas',
            'slug' => 'pousadas',
        ]);

        $publishedCompany = Company::factory()->published()->create([
            'name' => 'Pousada Esperança',
            'slug' => 'pousada-esperanca',
            'instagram_url' => 'https://www.instagram.com/pousadaesperanca/',
            'instagram_followers_at_registration' => 12345,
            'instagram_followers_recorded_at' => '2026-11-05 09:30:00',
        ]);
        $publishedCompany->categories()->attach($category);

        Company::factory()->create([
            'name' => 'Hotel em rascunho',
            'slug' => 'hotel-em-rascunho',
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Pousada Esperança')
            ->assertSee('12.345')
            ->assertSee('Pousadas')
            ->assertDontSee('Hotel em rascunho');
    }

    public function test_public_directory_filters_published_companies_by_name_category_and_city(): void
    {
        $hotelCategory = Category::factory()->create([
            'name' => 'Hotéis',
            'slug' => 'hoteis',
        ]);
        $serviceCategory = Category::factory()->create([
            'name' => 'Serviços',
            'slug' => 'servicos',
        ]);

        $pousada = Company::factory()->published()->create([
            'name' => 'Pousada Serra Azul',
            'city' => 'Niterói',
        ]);
        $pousada->categories()->attach($hotelCategory);

        $market = Company::factory()->published()->create([
            'name' => 'Mercado do Centro',
            'city' => 'Niterói',
        ]);
        $market->categories()->attach($serviceCategory);

        $workshop = Company::factory()->published()->create([
            'name' => 'Oficina do Sul',
            'city' => 'Campinas',
        ]);
        $workshop->categories()->attach($hotelCategory);

        Livewire::test(CompanyDirectory::class)
            ->set('search', 'Serra Azul')
            ->assertSee('Pousada Serra Azul')
            ->assertDontSee('Mercado do Centro')
            ->set('search', '')
            ->set('categorySlug', 'hoteis')
            ->assertSee('Pousada Serra Azul')
            ->assertSee('Oficina do Sul')
            ->assertDontSee('Mercado do Centro')
            ->set('categorySlug', '')
            ->set('city', 'Niterói')
            ->assertSee('Pousada Serra Azul')
            ->assertSee('Mercado do Centro')
            ->assertDontSee('Oficina do Sul');
    }

    public function test_unpublished_company_details_return_not_found(): void
    {
        $company = Company::factory()->create([
            'slug' => 'empresa-em-rascunho',
        ]);

        $this->get(route('companies.show', ['company' => $company->slug]))
            ->assertNotFound();
    }

    public function test_published_company_details_display_instagram_followers_and_timestamp(): void
    {
        $company = Company::factory()->published()->create([
            'name' => 'Pousada do Bosque',
            'slug' => 'pousada-do-bosque',
            'instagram_url' => 'https://www.instagram.com/pousadadobosque/',
            'instagram_followers_at_registration' => 1234,
            'instagram_followers_recorded_at' => '2026-11-05 09:30:00',
        ]);

        $this->get(route('companies.show', ['company' => $company->slug]))
            ->assertOk()
            ->assertSee('<!DOCTYPE html>', false)
            ->assertSee('Pousada do Bosque')
            ->assertSee('1.234 seguidores informados no cadastro')
            ->assertSee('05/11/2026')
            ->assertSee('09:30');
    }

    public function test_public_directory_escapes_company_names(): void
    {
        Company::factory()->published()->create([
            'name' => 'Pousada <script>alert(1)</script>',
            'slug' => 'pousada-script',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Pousada <script>alert(1)</script>')
            ->assertDontSee('<script>', false);
    }
}
