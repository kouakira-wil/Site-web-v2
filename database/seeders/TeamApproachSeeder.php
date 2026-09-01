<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Bloc « Notre approche pédagogique » de la page Équipe.
 *
 * La position 0 porte le titre et l'accroche de la section ; les suivantes
 * sont les cartes, chacune avec son pictogramme. Tout se modifie depuis le
 * back-office, y compris le nom de l'icône.
 */
class TeamApproachSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'equipe.approche',
                'label' => 'Approche pédagogique — en-tête',
                'title' => 'Notre Approche Pédagogique',
                'intro' => 'Découvrez notre méthode d’enseignement qui prépare nos étudiants à exceller dans leur domaine.',
                'icon' => null,
            ],
            [
                'key' => 'equipe.approche.1',
                'label' => 'Approche pédagogique — carte 1',
                'title' => 'Approche centrée sur la pratique',
                'intro' => 'Des cours appliqués basés sur des cas concrets et des études de cas réels pour favoriser un apprentissage immersif.',
                'icon' => 'book',
            ],
            [
                'key' => 'equipe.approche.2',
                'label' => 'Approche pédagogique — carte 2',
                'title' => 'Interaction avec le monde professionnel',
                'intro' => 'Stages, immersions en entreprise et conférences animées par des experts pour renforcer les liens avec le marché du travail.',
                'icon' => 'briefcase',
            ],
            [
                'key' => 'equipe.approche.3',
                'label' => 'Approche pédagogique — carte 3',
                'title' => 'Suivi personnalisé et mentorat',
                'intro' => 'Un accompagnement sur mesure avec du tutorat, du coaching et un suivi académique approfondi pour chaque étudiant.',
                'icon' => 'users',
            ],
            [
                'key' => 'equipe.approche.4',
                'label' => 'Approche pédagogique — carte 4',
                'title' => 'Ouverture internationale et numérique',
                'intro' => 'Des collaborations avec des établissements étrangers et l’intégration des outils numériques pour une formation moderne.',
                'icon' => 'globe',
            ],
        ];

        foreach ($blocs as $position => $bloc) {
            PageHeader::query()->updateOrCreate(
                ['key' => $bloc['key']],
                $bloc + [
                    'group' => 'equipe',
                    'position' => $position,
                    'eyebrow' => null,
                    'title_accent' => null,
                    'cta_label' => null,
                    'cta_url' => null,
                    'tags' => [],
                ],
            );
        }
    }
}
