<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Veículos', 'Hotéis', 'Pousadas', 'Depósitos', 'Alimentação', 'Serviços'] as $name) {
            Category::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true],
            );
        }
    }
}
