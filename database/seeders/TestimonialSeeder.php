<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

/**
 * Témoignages de diplômés.
 *
 * Les entrées reprennent mot pour mot celles déjà publiées sur la page
 * d'accueil. Elles sont anonymes : le nom reste vide et la signature se
 * déduit de la promotion, comme auparavant.
 */
class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $temoignages = [
            [
                'quote' => '« Les ateliers pratiques et l’accompagnement des enseignants ont fait toute la différence dans ma préparation au marché du travail. »',
                'program' => 'Comptabilité, Banque et Finance',
                'graduation_year' => 2021,
                'location' => 'Brazzaville, Congo',
                'position' => 0,
            ],
            [
                'quote' => '« L’approche pédagogique favorise vraiment l’interaction entre étudiants et enseignants : on ne reste jamais seul face à une difficulté. »',
                'program' => 'Génie Informatique et Réseaux',
                'graduation_year' => 2024,
                'location' => 'Brazzaville, Congo',
                'position' => 1,
            ],
            [
                'quote' => '« J’ai acquis des compétences pratiques qui m’ont permis de décrocher un emploi rapidement après ma formation. »',
                'program' => 'Réseaux et Télécommunications',
                'graduation_year' => 2020,
                'location' => 'Brazzaville, Congo',
                'position' => 2,
            ],
        ];

        foreach ($temoignages as $temoignage) {
            // La citation sert de clé : rejouer le seeder ne duplique rien.
            Testimonial::query()->updateOrCreate(
                ['quote' => $temoignage['quote']],
                $temoignage + ['is_active' => true],
            );
        }
    }
}
