<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PageHeader;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    /** En dessous de ce seuil, une recherche ramènerait la moitié du site. */
    private const LONGUEUR_MINIMALE = 2;

    private const MAX_PAR_TYPE = 12;

    public function __invoke(Request $request): View
    {
        $valide = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $terme = trim((string) ($valide['q'] ?? ''));
        $assezLong = mb_strlen($terme) >= self::LONGUEUR_MINIMALE;

        return view('site.search', [
            'terme' => $terme,
            'assezLong' => $assezLong,
            'longueurMinimale' => self::LONGUEUR_MINIMALE,
            'filieres' => $assezLong ? $this->filieres($terme) : collect(),
            'actualites' => $assezLong ? $this->actualites($terme) : collect(),
            'membres' => $assezLong ? $this->membres($terme) : collect(),
        ]);
    }

    /**
     * Prépare le terme pour une clause LIKE.
     *
     * `%` et `_` sont des jokers SQL : sans échappement, une recherche sur « % »
     * remonterait tout le catalogue. La valeur reste passée en liaison de
     * paramètre, jamais concaténée dans la requête.
     */
    private function motif(string $terme): string
    {
        return '%'.addcslashes($terme, '%_'.chr(92)).'%';
    }

    /**
     * @param  array<int, string>  $colonnes
     */
    private function chercherDans(Builder $requete, array $colonnes, string $terme): Builder
    {
        $motif = $this->motif($terme);

        return $requete->where(function (Builder $sous) use ($colonnes, $motif): void {
            foreach ($colonnes as $colonne) {
                $sous->orWhere($colonne, 'like', $motif);
            }
        });
    }

    private function filieres(string $terme): Collection
    {
        $motif = $this->motif($terme);

        $colonnes = ['title', 'excerpt', 'description', 'objectives', 'skills', 'outcomes', 'audience'];

        return Project::query()
            ->published()
            ->with(['category', 'cycles'])
            ->where(function (Builder $requete) use ($colonnes, $motif, $terme): void {
                foreach ($colonnes as $colonne) {
                    $requete->orWhere($colonne, 'like', $motif);
                }

                // « BTS », « Licence », « Master » nomment des cycles, pas des
                // filières : sans ceci, les recherches les plus évidentes du
                // menu de suggestions ne renverraient rien.
                $requete->orWhereHas('cycles', function (Builder $cycle) use ($motif): void {
                    $cycle->where('name', 'like', $motif)
                        ->orWhere('short_label', 'like', $motif);
                });
            })
            ->limit(self::MAX_PAR_TYPE)
            ->get();
    }

    private function actualites(string $terme): Collection
    {
        return $this->chercherDans(
            Post::query()->published()->with('category'),
            ['title', 'excerpt', 'body'],
            $terme
        )->latestFirst()->limit(self::MAX_PAR_TYPE)->get();
    }

    private function membres(string $terme): Collection
    {
        return $this->chercherDans(
            TeamMember::query()->active(),
            ['name', 'role', 'bio'],
            $terme
        )->ordered()->limit(self::MAX_PAR_TYPE)->get();
    }
}
