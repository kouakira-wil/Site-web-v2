<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    use HasSlug;

    public const TYPE_PROJECT = 'project';

    public const TYPE_POST = 'post';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function slugSourceColumn(): string
    {
        return 'name';
    }

    /**
     * Slugs are unique per type, so uniqueness must be scoped to the type.
     */
    protected function slugExists(string $slug): bool
    {
        return static::query()
            ->where('slug', $slug)
            ->where('type', $this->type)
            ->when($this->exists, fn (Builder $query) => $query->whereKeyNot($this->getKey()))
            ->exists();
    }

    /**
     * Category slugs are only unique per type, so binding by slug would be ambiguous.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('name');
    }
}
