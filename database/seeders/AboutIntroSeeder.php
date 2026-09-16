<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Première section de la page L'école.
 *
 * Les textes repris ici sont ceux déjà publiés ailleurs sur le site — page
 * d'accueil et bandeaux — plutôt que des formulations inventées. Rien n'avance
 * de chiffre ni de date que l'ESCAT n'ait déjà rendu public.
 */
class AboutIntroSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'ecole.presentation',
                'label' => 'Présentation (première section)',
                // La colonne est non signée : pas de valeur négative.
                'position' => 0,
                'eyebrow' => 'À propos',
                'title' => 'Former à Brazzaville les techniciens et les cadres de demain',
                'intro' => 'L’ESCAT associe un enseignement rigoureux à la pratique de terrain : ateliers, projets encadrés et proximité avec les entreprises, du CAP au Master.',
                'tags' => [
                    'Un parcours continu, du CAP au Master, sans réorientation subie.',
                    'Des ateliers pratiques dès la première année.',
                    'Des programmes révisés avec les entreprises du secteur.',
                ],
                'cta_label' => 'Découvrir nos filières',
                'cta_url' => '/filieres',
            ],
            [
                'key' => 'ecole.reperes',
                'label' => 'Repères chiffrés',
                'position' => 3,
                'title' => 'L’école en chiffres',
                // Les valeurs sont comptées en base ; seuls les libellés se saisissent.
                'tags' => ['Cycles de formation', 'Filières ouvertes', 'Pôles d’excellence'],
                'cta_label' => 'Commencer le parcours',
                'cta_url' => '/contact',
            ],
            [
                'key' => 'ecole.presentation.1',
                'label' => 'Présentation — premier argument',
                'position' => 1,
                'title' => 'Exigence académique',
                'intro' => 'Des programmes construits avec les entreprises du secteur, revus à chaque rentrée.',
                'icon' => 'award',
            ],
            [
                'key' => 'ecole.presentation.2',
                'label' => 'Présentation — second argument',
                'position' => 2,
                'title' => 'Insertion professionnelle',
                'intro' => 'Stages encadrés, ateliers et réseau d’anciens accompagnent chaque étudiant jusqu’à l’emploi.',
                'icon' => 'briefcase',
            ],
        ];

        foreach ($blocs as $bloc) {
            PageHeader::query()->updateOrCreate(
                ['key' => $bloc['key']],
                $bloc + ['group' => 'ecole'],
            );
        }
    }
}
