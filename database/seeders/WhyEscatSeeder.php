<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Section « Pourquoi l'ESCAT » de la page d'accueil.
 *
 * Les deux cartes reprennent les arguments qui figuraient déjà dans cette
 * section sous les étiquettes anglaises « Active » et « Trusted », laissées
 * par le gabarit.
 */
class WhyEscatSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'home.testimonials',
                'label' => 'Pourquoi l’ESCAT',
                'group' => 'accueil',
                'cta_label' => 'Parlons de votre projet',
                'cta_url' => '/contact',
                'icon' => 'sparkle',
            ],
            [
                'key' => 'home.testimonials.1',
                'label' => 'Pourquoi l’ESCAT — premier argument',
                'group' => 'accueil',
                'position' => 61,
                'title' => 'Un parcours continu',
                'intro' => 'Du CAP au Master, sans rupture ni réorientation subie.',
                'icon' => 'compass',
            ],
            [
                'key' => 'home.testimonials.2',
                'label' => 'Pourquoi l’ESCAT — second argument',
                'group' => 'accueil',
                'position' => 62,
                'title' => 'Un réseau d’entreprises',
                'intro' => 'Des entreprises et institutions accueillent nos étudiants en stage et recrutent nos diplômés.',
                'icon' => 'briefcase',
            ],
        ];

        foreach ($blocs as $bloc) {
            // updateOrCreate : le bloc parent existe déjà, on ne fait qu'ajouter
            // ses nouveaux champs sans écraser son titre ni son accroche.
            PageHeader::query()->updateOrCreate(['key' => $bloc['key']], $bloc);
        }
    }
}
