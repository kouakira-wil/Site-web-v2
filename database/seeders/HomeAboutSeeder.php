<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Section « à propos » de la page d'accueil.
 *
 * Les textes reprennent ceux déjà publiés sur le site : présentation de
 * l'école, engagement pédagogique, argument d'insertion.
 */
class HomeAboutSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'home.about',
                'group' => 'accueil',
                'icon' => 'book',
                'intro' => 'Ateliers pratiques, projets encadrés et suivi personnalisé : la théorie s’éprouve sur le terrain dès la première année.',
                'body' => 'Former des techniciens et des cadres immédiatement opérationnels, du CAP au Master.',
                'tags' => ['Un accompagnement de proximité, de l’orientation jusqu’à l’insertion.'],
                'cta_url' => '/l-ecole',
            ],
            [
                'key' => 'home.about.1',
                'label' => 'À propos — argument',
                'group' => 'accueil',
                'position' => 21,
                'title' => 'Insertion professionnelle',
                'intro' => 'Stages encadrés et réseau d’anciens accompagnent chaque étudiant jusqu’à l’emploi.',
                'icon' => 'briefcase',
            ],
        ];

        foreach ($blocs as $bloc) {
            // Le bloc parent existe : on complète ses champs sans écraser
            // son titre ni son surtitre.
            PageHeader::query()->updateOrCreate(['key' => $bloc['key']], $bloc);
        }
    }
}
