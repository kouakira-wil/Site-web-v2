<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Support\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class Project extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'client',
        'year',
        'external_url',
        'excerpt',
        'description',
        'objectives',
        'skills',
        'outcomes',
        'audience',
        'learning_blocks',
        'cover_image',
        'status',
        'is_featured',
        'position',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'position' => 'integer',
            'year' => 'integer',
            'published_at' => 'datetime',
            'learning_blocks' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Study cycles this filière is offered at.
     */
    public function cycles(): BelongsToMany
    {
        return $this->belongsToMany(Cycle::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('position');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderByDesc('published_at');
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null
            && $this->published_at->isPast();
    }

    public function coverUrl(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    /**
     * Splits a textarea field into a list, one entry per non-empty line.
     *
     * @return array<int, string>
     */
    public function lines(string $attribute): array
    {
        return collect(preg_split('/\R/', (string) $this->{$attribute}))
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Teaching blocks shown under « Ce que vous y apprenez ».
     *
     * @return array<int, array{title: string, description: string, points: array<int, string>}>
     */
    public function learningBlocks(): array
    {
        return collect($this->learning_blocks ?? [])
            ->filter(fn ($bloc): bool => filled($bloc['title'] ?? null))
            ->map(fn (array $bloc): array => [
                'title' => (string) $bloc['title'],
                'description' => (string) ($bloc['description'] ?? ''),
                'points' => array_values(array_filter(array_map('trim', $bloc['points'] ?? []))),
                // Visuel propre au bloc ; à défaut, la couverture de la filière.
                'image' => filled($bloc['image'] ?? null)
                    ? Storage::url((string) $bloc['image'])
                    : $this->coverUrl(),
            ])
            ->values()
            ->all();
    }

    public function descriptionHtml(): HtmlString
    {
        return Markdown::toHtml($this->description);
    }
}
