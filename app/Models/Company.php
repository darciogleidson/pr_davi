<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name',
    'slug',
    'description',
    'phone',
    'whatsapp',
    'email',
    'website',
    'address',
    'city',
    'state',
    'instagram_url',
    'instagram_followers_at_registration',
    'instagram_followers_recorded_at',
    'logo_path',
    'is_published',
])]
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    /**
     * The categories assigned to the company.
     *
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    /**
     * Get the attribute casts for the model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'instagram_followers_at_registration' => 'integer',
            'instagram_followers_recorded_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }
}
