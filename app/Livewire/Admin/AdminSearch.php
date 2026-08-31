<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Recherche interne au back-office.
 *
 * Contrairement à la recherche publique, elle ne filtre pas sur le statut :
 * un brouillon doit pouvoir être retrouvé, c'est même le cas d'usage principal.
 */
#[Layout('layouts.admin')]
#[Title('Recherche')]
class AdminSearch extends Component
{
    private const LONGUEUR_MINIMALE = 2;

    private const MAX_PAR_TYPE = 10;

    #[Url(as: 'q')]
    public string $q = '';

    /**
     * `%` et `_` sont des jokers SQL : sans échappement, une recherche sur
     * « % » remonterait toute la base.
     */
    private function motif(): string
    {
        return '%'.addcslashes(trim($this->q), '%_'.chr(92)).'%';
    }

    /**
     * @param  array<int, string>  $colonnes
     */
    private function chercher(Builder $requete, array $colonnes): Collection
    {
        $motif = $this->motif();

        return $requete
            ->where(function (Builder $sous) use ($colonnes, $motif): void {
                foreach ($colonnes as $colonne) {
                    $sous->orWhere($colonne, 'like', $motif);
                }
            })
            ->limit(self::MAX_PAR_TYPE)
            ->get();
    }

    public function render(): View
    {
        $terme = trim($this->q);
        $assezLong = mb_strlen($terme) >= self::LONGUEUR_MINIMALE;

        return view('livewire.admin.search', [
            'terme' => $terme,
            'assezLong' => $assezLong,
            'longueurMinimale' => self::LONGUEUR_MINIMALE,
            'filieres' => $assezLong ? $this->chercher(Project::query(), ['title', 'excerpt', 'description']) : collect(),
            'actualites' => $assezLong ? $this->chercher(Post::query(), ['title', 'excerpt', 'body']) : collect(),
            'membres' => $assezLong ? $this->chercher(TeamMember::query(), ['name', 'role', 'bio']) : collect(),
            'messages' => $assezLong ? $this->chercher(ContactMessage::query(), ['name', 'email', 'subject', 'message']) : collect(),
        ]);
    }
}
