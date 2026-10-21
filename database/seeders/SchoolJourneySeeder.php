<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Section « Votre parcours » de la page L'école.
 *
 * Trois sortes de blocs : l'en-tête de la section, la carte illustrée des
 * pôles, et les quatre étapes du cursus. Les textes repris ici sont ceux qui
 * étaient écrits en dur dans le gabarit ; la refonte ne fait que les rendre
 * modifiables.
 *
 * Les intitulés des étapes recoupent la table `cycles`, qui porte déjà nom,
 * libellé court et description. Ce sont pourtant deux choses différentes :
 * la table décrit les cycles, ces blocs portent le discours editorial qui les
 * présente (« Entrer rapidement dans le métier »).
 */
class SchoolJourneySeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'ecole.parcours',
                'label' => 'Parcours — en-tête',
                'position' => 4,
                'eyebrow' => 'Le parcours',
                'title' => 'Votre parcours à l’ESCAT',
                'intro' => 'Un cursus continu, sans rupture : chaque cycle prolonge le précédent dans la même filière.',
                'cta_label' => 'Demander une préinscription',
                'cta_url' => '/contact',
            ],
            [
                'key' => 'ecole.parcours.pole',
                'label' => 'Parcours — carte des pôles',
                'position' => 5,
                'eyebrow' => 'Deux pôles',
                'title' => 'Technologie et Gestion',
                'intro' => 'Réseaux, informatique et électromécanique d’un côté ; comptabilité, banque et finance de l’autre.',
                'cta_url' => '/filieres',
            ],
            [
                'key' => 'ecole.parcours.1',
                'label' => 'Parcours — première étape',
                'position' => 6,
                'eyebrow' => 'CAP — Certificat d’Aptitude Professionnelle',
                'title' => 'Entrer rapidement dans le métier',
                'intro' => 'Une formation courte et pratique, orientée vers une insertion rapide sur le marché du travail.',
                'cta_url' => '/filieres',
            ],
            [
                'key' => 'ecole.parcours.2',
                'label' => 'Parcours — deuxième étape',
                'position' => 7,
                'eyebrow' => 'Licence — BAC +3',
                'title' => 'Approfondir et se spécialiser',
                'intro' => 'Une troisième année pour consolider un projet professionnel et se spécialiser dans sa filière.',
                'cta_url' => '/filieres',
            ],
            [
                'key' => 'ecole.parcours.3',
                'label' => 'Parcours — troisième étape',
                'position' => 8,
                'eyebrow' => 'Master — BAC +5',
                'title' => 'Devenir expert et encadrer',
                'intro' => 'Le cycle le plus avancé : expertise du domaine, conduite de projet et encadrement d’equipes.',
                'cta_url' => '/filieres',
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
