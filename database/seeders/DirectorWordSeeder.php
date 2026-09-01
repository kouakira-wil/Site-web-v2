<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Mot du directeur, sur la page L'école.
 *
 * L'accroche reste toujours visible ; le corps se déplie au clic. Les deux se
 * modifient depuis le back-office.
 */
class DirectorWordSeeder extends Seeder
{
    public function run(): void
    {
        $corps = <<<'TEXTE'
        Nous avons conçu des programmes innovants pour répondre aux besoins du marché et assurer la réussite de nos étudiants. Nous mettons un accent particulier sur l’excellence académique à travers des formations pointues, dispensées par des enseignants experts. L’environnement sécurisé que nous offrons permet un apprentissage optimal, tandis que nos infrastructures modernes garantissent des conditions idéales pour développer vos compétences.

        Nous croyons en la puissance de l’éducation pour transformer les vies et les communautés. Rejoignez-nous à l’ESCAT et faites partie d’une institution qui valorise l’innovation et la réussite.
        TEXTE;

        PageHeader::query()->updateOrCreate(
            ['key' => 'ecole.directeur'],
            [
                'group' => 'ecole',
                // La colonne est un entier non signé : on reste à 0, et la
                // section identité écarte cette clé explicitement.
                'position' => 0,
                'label' => 'Mot du directeur',
                'title' => 'Mot du Directeur',
                'title_accent' => null,
                'eyebrow' => null,
                'intro' => 'Chers étudiants, partenaires et visiteurs, bienvenue à l’ESCAT. Notre école s’engage à offrir une éducation de qualité, centrée sur l’excellence académique et le développement personnel.',
                'body' => $corps,
                'icon' => null,
                'cta_label' => null,
                'cta_url' => null,
                'tags' => [],
            ],
        );
    }
}
