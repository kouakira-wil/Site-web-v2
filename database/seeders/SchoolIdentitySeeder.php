<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Vision, mission, objectif et valeurs de l'école.
 *
 * Les textes sont volontairement vides : ce sont des engagements que seule
 * l'ESCAT peut formuler. Le seeder ne pose que les intitulés et l'ordre ;
 * la section reste masquée sur le site tant que rien n'est saisi.
 */
class SchoolIdentitySeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            ['key' => 'ecole.vision', 'label' => 'Notre vision', 'title' => 'Notre vision'],
            ['key' => 'ecole.mission', 'label' => 'Notre mission', 'title' => 'Notre mission'],
            ['key' => 'ecole.objectif', 'label' => 'Notre objectif', 'title' => 'Notre objectif'],
            ['key' => 'ecole.valeurs', 'label' => 'Nos valeurs', 'title' => 'Nos valeurs'],
        ];

        foreach ($blocs as $position => $bloc) {
            PageHeader::query()->updateOrCreate(
                ['key' => $bloc['key']],
                $bloc + [
                    'group' => 'ecole',
                    'position' => $position,
                    'eyebrow' => null,
                    'title_accent' => null,
                    // À remplir depuis le back-office.
                    'intro' => null,
                    'cta_label' => null,
                    'cta_url' => null,
                    'tags' => [],
                ],
            );
        }
    }
}
