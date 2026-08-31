<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

class PageHeaderSeeder extends Seeder
{
    /**
     * Reprend les textes déjà en ligne : rien n'est inventé ici, la refonte
     * ne fait que les rendre modifiables depuis le back-office.
     *
     * Les visuels de fond sont laissés vides : ils se téléversent depuis le
     * back-office, et le hero retombe entre-temps sur son fond sombre.
     */
    public function run(): void
    {
        $entetes = [
            [
                'key' => 'about',
                'label' => 'L’école',
                'eyebrow' => 'L’école',
                'title' => 'Une école supérieure privée',
                'title_accent' => 'au service du Congo',
                'intro' => 'L’ESCAT forme à Brazzaville les techniciens et cadres de demain, du CAP au Master, en Technologie et en Gestion.',
                'cta_label' => 'Nous contacter',
                'cta_url' => '/contact',
                'tags' => ['Brazzaville', 'Technologie', 'Gestion'],
            ],
            [
                'key' => 'filieres',
                'label' => 'Filières',
                'eyebrow' => 'Filières et parcours',
                'title' => 'Nos filières',
                'title_accent' => 'et parcours',
                'intro' => 'Un catalogue organisé par cycle de formation, du CAP au Master.',
                'cta_label' => 'Demander une préinscription',
                'cta_url' => '/contact',
                'tags' => ['CAP', 'BTS', 'Licence', 'Master'],
            ],
            [
                'key' => 'journal',
                'label' => 'Actualités',
                'eyebrow' => 'Actualités & vie étudiante',
                'title' => 'Actualités',
                'title_accent' => 'de l’ESCAT',
                'intro' => 'Événements, travaux pratiques et moments forts de la vie de l’école, à Brazzaville.',
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
            [
                'key' => 'equipe',
                'label' => 'Équipe',
                'eyebrow' => 'Notre équipe',
                'title' => 'Celles et ceux',
                'title_accent' => 'qui vous accompagnent',
                'intro' => 'Enseignants, encadrants d’atelier et personnel administratif : une équipe disponible, du premier entretien d’orientation jusqu’à l’insertion.',
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
            [
                'key' => 'contact',
                'label' => 'Contact',
                'eyebrow' => 'Nous contacter',
                'title' => 'Parlons de votre',
                'title_accent' => 'projet de formation',
                'intro' => 'Orientation, admission, préinscription : notre équipe vous répond.',
                'cta_label' => null,
                'cta_url' => null,
                'tags' => ['Brazzaville', 'infos@escat.org'],
            ],
            [
                'key' => 'recherche',
                'label' => 'Recherche',
                'eyebrow' => 'Recherche',
                'title' => 'Rechercher',
                'title_accent' => 'sur le site',
                'intro' => null,
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
        ];

        foreach ($entetes as $entete) {
            // updateOrCreate : rejouer le seeder ne duplique rien.
            PageHeader::query()->updateOrCreate(['key' => $entete['key']], $entete);
        }
    }
}
