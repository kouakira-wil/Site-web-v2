<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Vision de l'ESCAT, en trois étapes.
 *
 * Les textes sont ceux fournis par l'école : formation théorique, pratique
 * professionnelle, carrière et insertion.
 */
class VisionStepsSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'ecole.vision',
                'label' => 'Notre vision',
                'group' => 'ecole',
                'position' => 0,
                'eyebrow' => 'Notre vision',
                'title' => 'La vision de l’ESCAT : un modèle éducatif réinventé',
                'intro' => 'Préparer chaque étudiant à être un acteur performant dans son domaine grâce à un équilibre entre théorie, pratique et expérience terrain.',
                'icon' => 'compass',
            ],
            [
                'key' => 'ecole.vision.1',
                'label' => 'Vision — première étape',
                'group' => 'ecole',
                'position' => 1,
                'eyebrow' => 'Formation théorique',
                'title' => 'Les connaissances',
                'intro' => 'Acquérir une formation académique professionnelle solide pour bâtir un savoir fondamental.',
                'icon' => 'book',
            ],
            [
                'key' => 'ecole.vision.2',
                'label' => 'Vision — deuxième étape',
                'group' => 'ecole',
                'position' => 2,
                'eyebrow' => 'Pratique professionnelle',
                'title' => 'Les compétences',
                'intro' => 'Transformer les connaissances en expertise grâce aux stages et aux mises en situation réelles.',
                'icon' => 'briefcase',
            ],
            [
                'key' => 'ecole.vision.3',
                'label' => 'Vision — troisième étape',
                'group' => 'ecole',
                'position' => 3,
                'eyebrow' => 'Carrière et insertion',
                'title' => 'L’expérience professionnelle',
                'intro' => 'Pratiquer et perfectionner ses compétences au sein des entreprises partenaires.',
                'icon' => 'award',
            ],
        ];

        foreach ($blocs as $bloc) {
            PageHeader::query()->updateOrCreate(['key' => $bloc['key']], $bloc);
        }
    }
}
