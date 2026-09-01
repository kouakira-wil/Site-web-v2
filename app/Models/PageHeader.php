<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * En-tête éditorial d'une page du site.
 *
 * Les clés sont fixées par le code : le back-office modifie les textes mais
 * n'ajoute ni ne supprime de page, pour qu'aucune vue ne se retrouve sans
 * en-tête.
 */
class PageHeader extends Model
{
    use HasFactory;

    public const CACHE_PREFIX = 'site.page-header.';

    public const GROUPE_PAGES = 'pages';

    public const GROUPE_ACCUEIL = 'accueil';

    protected $fillable = [
        'key',
        'group',
        'position',
        'label',
        'eyebrow',
        'title',
        'title_accent',
        'intro',
        'body',
        'image',
        'icon',
        'cta_label',
        'cta_url',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'position' => 'integer',
        ];
    }

    /**
     * Visuel de fond du hero, s'il a été téléversé.
     */
    public function imageUrl(): ?string
    {
        return filled($this->image) ? \Illuminate\Support\Facades\Storage::url($this->image) : null;
    }

    /**
     * Étiquettes affichées en bas du hero.
     *
     * @return array<int, string>
     */
    /**
     * Texte long découpé en paragraphes.
     *
     * Une ligne vide sépare deux paragraphes, comme à la saisie ; un
     * retour simple reste dans le même paragraphe.
     *
     * @return array<int, string>
     */
    public function bodyParagraphs(): array
    {
        $texte = trim((string) $this->body);

        if ($texte === '') {
            return [];
        }

        return collect(preg_split('/(?:\R\s*){2,}/', $texte))
            ->map(fn (string $bloc): string => trim(preg_replace('/\s+/', ' ', $bloc)))
            ->filter()
            ->values()
            ->all();
    }

    public function tagList(): array
    {
        return array_values(array_filter(array_map('trim', $this->tags ?? [])));
    }

    public static function forKey(string $key): ?self
    {
        return Cache::rememberForever(
            self::CACHE_PREFIX.$key,
            fn (): ?self => self::query()->where('key', $key)->first()
        );
    }

    /**
     * Étiquettes découpées en couples « valeur | libellé ».
     *
     * Une entrée sans barre verticale renvoie sa valeur et un libellé vide :
     * la vue reste utilisable même si l'éditeur a oublié le séparateur.
     *
     * @return array<int, array{0: string, 1: string}>
     */
    public function tagPairs(): array
    {
        return array_map(static function (string $tag): array {
            $morceaux = array_map('trim', explode('|', $tag, 2));

            return [$morceaux[0], $morceaux[1] ?? ''];
        }, $this->tagList());
    }

    protected static function booted(): void
    {
        // Le cache est vidé dès qu'un en-tête change, sinon le site
        // continuerait de servir l'ancien texte.
        $oublier = fn (self $header) => Cache::forget(self::CACHE_PREFIX.$header->key);

        static::saved($oublier);
        static::deleted($oublier);
    }
}
