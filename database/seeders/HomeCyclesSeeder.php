<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Cartes des cycles, dans la section « Nos formations » de l'accueil.
 *
 * Les textes repris ici sont ceux qui étaient écrits en dur dans le gabarit ;
 * la refonte ne fait que les rendre modifiables. La numérotation [01], [02]…
 * est calculée à l'affichage : réordonner les blocs suffit à la renuméroter.
 *
 * Comme pour la section « parcours » de L'école, ces blocs recoupent la table
 * `cycles` sans la remplacer : celle-ci décrit les cycles, ceux-là portent le
 * discours qui les présente sur l'accueil.
 */
class HomeCyclesSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'home.services.1',
                'label' => 'Cycles — première carte',
                'position' => 31,
                'title' => 'CAP',
                'intro' => 'Une formation courte et pratique pour accéder rapidement au marché du travail.',
                'cta_url' => '/filieres',
            ],
            [
                'key' => 'home.services.2',
                'label' => 'Cycles — deuxième carte',
                'position' => 32,
                'title' => 'Cycle Licence (BAC +3)',
                'intro' => 'Trois ans pour approfondir un domaine et consolider un projet professionnel.',
                'cta_url' => '/filieres',
            ],
            [
                'key' => 'home.services.3',
                'label' => 'Cycles — troisième carte',
                'position' => 33,
                'title' => 'Cycle Master (BAC +5)',
                'intro' => 'Cinq ans pour devenir expert de son domaine et encadrer des équipes.',
                'cta_url' => '/filieres',
            ],
        ];

        foreach ($blocs as $bloc) {
            PageHeader::query()->updateOrCreate(
                ['key' => $bloc['key']],
                $bloc + ['group' => PageHeader::GROUPE_ACCUEIL],
            );
        }
    }
}
