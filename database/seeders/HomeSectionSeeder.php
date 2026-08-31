<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Sections de la page d'accueil.
 *
 * Elles partagent la table des en-têtes : mêmes champs, groupe différent.
 * Les textes repris ici sont ceux déjà en ligne — rien n'est inventé, la
 * refonte ne fait que les rendre modifiables.
 *
 * Convention pour `tags`, rappelée dans l'aide du formulaire : une entrée par
 * ligne, et lorsqu'une entrée porte deux informations, elles sont séparées par
 * une barre verticale (« valeur | libellé »).
 */
class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'home.hero',
                'label' => 'Bandeau principal',
                'eyebrow' => 'Une référence de la formation au Congo',
                'title' => 'Formez-vous auprès des meilleurs pour',
                'title_accent' => 'devenir un leader de demain.',
                'intro' => 'Des cursus adaptés au marché, un encadrement de proximité et des ateliers pratiques dès la première année.',
                'cta_label' => 'Découvrir nos formations',
                'cta_url' => '/filieres',
                'tags' => ['Réseaux et Télécommunications', 'Génie Informatique', 'Comptabilité et Finance', 'Électromécanique'],
            ],
            [
                'key' => 'home.about',
                'label' => 'L’école en bref',
                'eyebrow' => 'L’école',
                'title' => 'L’ESCAT forme des techniciens et des cadres immédiatement opérationnels, en associant enseignement rigoureux et pratique de terrain.',
                'title_accent' => null,
                'intro' => null,
                'cta_label' => 'Nous contacter',
                'cta_url' => '/contact',
                'tags' => [
                    'Excellence académique | Des programmes exigeants, révisés avec les entreprises du secteur.',
                    'Insertion professionnelle | Ateliers pratiques, stages encadrés et réseau d’anciens.',
                ],
            ],
            [
                'key' => 'home.services',
                'label' => 'Cycles de formation',
                'eyebrow' => 'Nos formations',
                'title' => 'Filières principales',
                'title_accent' => null,
                'intro' => 'Brazzaville, Congo',
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
            [
                'key' => 'home.portfolio',
                'label' => 'Filières phares',
                'eyebrow' => null,
                'title' => 'Nos filières phares',
                'title_accent' => null,
                'intro' => 'Quatre parcours construits avec les entreprises du secteur, où la théorie se vérifie en atelier.',
                'cta_label' => 'Voir toutes les filières',
                'cta_url' => '/filieres',
                'tags' => [],
            ],
            [
                'key' => 'home.marquee',
                'label' => 'Chiffres défilants',
                'eyebrow' => null,
                'title' => 'Chiffres clés',
                'title_accent' => null,
                'intro' => null,
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [
                    '4 | Cycles, du CAP au Master',
                    '5 | Filières ouvertes',
                    '2 | Pôles d’excellence',
                    '20 | Promotions diplômées',
                    '5 | Années d’études',
                ],
            ],
            [
                'key' => 'home.testimonials',
                'label' => 'Pourquoi l’ESCAT',
                'eyebrow' => 'Pourquoi l’ESCAT',
                'title' => 'Un enseignement exigeant, des infrastructures modernes et un accompagnement de proximité.',
                'title_accent' => null,
                'intro' => 'L’ESCAT va au-delà des cours magistraux : ateliers pratiques, projets encadrés et suivi personnalisé.',
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
            [
                'key' => 'home.showcase',
                'label' => 'Témoignages',
                'eyebrow' => 'Ils témoignent',
                'title' => 'Ce que nos étudiants et diplômés retiennent de leur passage à l’ESCAT.',
                'title_accent' => null,
                'intro' => null,
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
            [
                'key' => 'home.process',
                'label' => 'Équipe et coordonnées',
                'eyebrow' => 'Notre équipe',
                'title' => 'Enseignants, encadrants et personnel administratif accompagnent chaque étudiant, de l’orientation jusqu’à l’insertion.',
                'title_accent' => null,
                'intro' => null,
                'cta_label' => 'Rejoindre l’équipe',
                'cta_url' => '/equipe',
                'tags' => [
                    'Brazzaville | République du Congo',
                    'Service des admissions | +242 06 884 83 44',
                ],
            ],
            [
                'key' => 'home.faq',
                'label' => 'Questions fréquentes',
                'eyebrow' => 'FAQ',
                'title' => 'Les questions que l’on nous pose le plus souvent sur l’admission.',
                'title_accent' => null,
                'intro' => 'Une question sans réponse ? Écrivez-nous.',
                'cta_label' => 'Le service des admissions vous répond',
                'cta_url' => '/contact',
                'tags' => [],
            ],
            [
                'key' => 'home.banner',
                'label' => 'Appel à l’action',
                'eyebrow' => 'Brazzaville',
                'title' => 'Faites le choix de la réussite.',
                'title_accent' => 'Rejoignez ESCAT !',
                'intro' => 'L’École Supérieure de Commerce, d’Administration et de Technologie forme à Brazzaville les techniciens et cadres de demain.',
                'cta_label' => 'Prendre une préinscription',
                'cta_url' => '/contact',
                'tags' => ['Du lundi au samedi, 8h - 17h'],
            ],
            [
                'key' => 'home.journal',
                'label' => 'Actualités',
                'eyebrow' => 'Vie de l’école',
                'title' => 'Actualités et événements de l’ESCAT',
                'title_accent' => null,
                'intro' => null,
                'cta_label' => 'Toutes les actualités',
                'cta_url' => '/journal',
                'tags' => [],
            ],
        ];

        foreach ($sections as $position => $section) {
            PageHeader::query()->updateOrCreate(
                ['key' => $section['key']],
                $section + [
                    'group' => PageHeader::GROUPE_ACCUEIL,
                    'position' => $position,
                ],
            );
        }
    }
}
