<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Vue d’ensemble')]
class Dashboard extends Component
{
    private const MOIS_HISTORIQUE = 12;

    /**
     * Variation en pourcentage entre le mois en cours et le précédent.
     *
     * Renvoie `null` quand le mois précédent est vide : une progression
     * « infinie » n'aurait aucun sens, mieux vaut ne rien afficher.
     */
    private function variationMensuelle(Builder $requete, string $colonne = 'created_at'): ?float
    {
        $debutMois = CarbonImmutable::now()->startOfMonth();
        $debutMoisPrecedent = $debutMois->subMonth();

        $ceMois = (clone $requete)->whereBetween($colonne, [$debutMois, CarbonImmutable::now()])->count();
        $moisPrecedent = (clone $requete)->whereBetween($colonne, [$debutMoisPrecedent, $debutMois])->count();

        if ($moisPrecedent === 0) {
            return null;
        }

        return round((($ceMois - $moisPrecedent) / $moisPrecedent) * 100, 2);
    }

    /**
     * Publications par mois sur l'année écoulée, pour l'histogramme.
     *
     * @return array<int, array{mois: string, actualites: int, filieres: int}>
     */
    private function historique(): array
    {
        $depuis = CarbonImmutable::now()->startOfMonth()->subMonths(self::MOIS_HISTORIQUE - 1);

        $parMois = function (string $modele) use ($depuis): Collection {
            return $modele::query()
                ->whereNotNull('published_at')
                ->where('published_at', '>=', $depuis)
                ->get(['published_at'])
                ->groupBy(fn ($ligne): string => $ligne->published_at->format('Y-m'))
                ->map(fn (Collection $groupe): int => $groupe->count());
        };

        $actualites = $parMois(Post::class);
        $filieres = $parMois(Project::class);

        $lignes = [];

        for ($i = 0; $i < self::MOIS_HISTORIQUE; $i++) {
            $mois = $depuis->addMonths($i);
            $cle = $mois->format('Y-m');

            $lignes[] = [
                'mois' => $mois->translatedFormat('M'),
                'actualites' => $actualites->get($cle, 0),
                'filieres' => $filieres->get($cle, 0),
            ];
        }

        return $lignes;
    }

    /**
     * Grille du mois courant, avec le nombre de publications par jour.
     *
     * @return array{mois: string, semaines: array<int, array<int, array{jour: int|null, date: string|null, aujourdhui: bool, publications: int}>>}
     */
    private function calendrier(): array
    {
        $aujourdhui = CarbonImmutable::today();
        $debut = $aujourdhui->startOfMonth();
        $fin = $aujourdhui->endOfMonth();

        $publications = Post::query()
            ->whereNotNull('published_at')
            ->whereBetween('published_at', [$debut->startOfDay(), $fin->endOfDay()])
            ->get(['published_at'])
            ->groupBy(fn ($ligne): string => $ligne->published_at->toDateString())
            ->map(fn (Collection $groupe): int => $groupe->count());

        // La grille démarre le dimanche, comme la maquette.
        $decalage = (int) $debut->dayOfWeek;

        $cases = array_fill(0, $decalage, ['jour' => null, 'date' => null, 'aujourdhui' => false, 'publications' => 0]);

        for ($jour = 1; $jour <= $fin->day; $jour++) {
            $date = $debut->addDays($jour - 1);

            $cases[] = [
                'jour' => $jour,
                'date' => $date->toDateString(),
                'aujourdhui' => $date->isSameDay($aujourdhui),
                'publications' => $publications->get($date->toDateString(), 0),
            ];
        }

        // Compléter la dernière semaine pour garder une grille régulière.
        while (count($cases) % 7 !== 0) {
            $cases[] = ['jour' => null, 'date' => null, 'aujourdhui' => false, 'publications' => 0];
        }

        return [
            'mois' => $aujourdhui->translatedFormat('F Y'),
            'semaines' => array_chunk($cases, 7),
        ];
    }

    public function render(): View
    {
        $brouillonsFilieres = Project::query()->where('status', Project::STATUS_DRAFT)->count();
        $brouillonsPosts = Post::query()->where('status', Post::STATUS_DRAFT)->count();

        return view('livewire.admin.dashboard', [
            'stats' => [
                [
                    'label' => 'Filières publiées',
                    'value' => Project::query()->published()->count(),
                    'caption' => $brouillonsFilieres.' brouillon'.($brouillonsFilieres > 1 ? 's' : ''),
                    'icon' => 'layers',
                    'tone' => 'blue',
                    'trend' => $this->variationMensuelle(Project::query()),
                    'route' => route('admin.projects.index'),
                ],
                [
                    'label' => 'Actualités publiées',
                    'value' => Post::query()->published()->count(),
                    'caption' => $brouillonsPosts.' brouillon'.($brouillonsPosts > 1 ? 's' : ''),
                    'icon' => 'news',
                    'tone' => 'amber',
                    'trend' => $this->variationMensuelle(Post::query()),
                    'route' => route('admin.posts.index'),
                ],
                [
                    'label' => 'Membres actifs',
                    'value' => TeamMember::query()->active()->count(),
                    'caption' => TeamMember::query()->where('is_active', false)->count().' masqué(s)',
                    'icon' => 'users',
                    'tone' => 'cyan',
                    'trend' => null,
                    'route' => route('admin.team.index'),
                ],
                [
                    'label' => 'Messages non lus',
                    'value' => ContactMessage::query()->unread()->count(),
                    'caption' => ContactMessage::query()->count().' au total',
                    'icon' => 'mail',
                    'tone' => 'violet',
                    'trend' => $this->variationMensuelle(ContactMessage::query()),
                    'route' => route('admin.messages.index'),
                ],
            ],

            'historique' => $this->historique(),
            'calendrier' => $this->calendrier(),

            // Programmées : la date de parution est encore à venir.
            'aVenir' => Post::query()
                ->whereNotNull('published_at')
                ->where('published_at', '>', now())
                ->orderBy('published_at')
                ->take(4)
                ->get(),

            'latestMessages' => ContactMessage::query()->latest()->take(5)->get(),
            'draftProjects' => Project::query()
                ->where('status', Project::STATUS_DRAFT)
                ->latest('updated_at')
                ->take(5)
                ->get(),
        ]);
    }
}
