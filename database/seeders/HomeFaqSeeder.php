<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Questions fréquentes de l'accueil.
 *
 * Un bloc pour la carte de contact à gauche, un par question. L'ordre des
 * questions suit la position : la numérotation affichée est recalculée, et la
 * première de la liste est celle qui s'ouvre au chargement.
 *
 * Les textes repris ici sont ceux qui étaient écrits en dur dans le gabarit.
 */
class HomeFaqSeeder extends Seeder
{
    public function run(): void
    {
        $blocs = [
            [
                'key' => 'home.faq.contact',
                'label' => 'FAQ — carte de contact',
                'position' => 81,
                'title' => 'Une question sans réponse ? Écrivez-nous.',
                'intro' => 'Le service des admissions vous répond',
                'cta_label' => 'Service des admissions',
                'cta_url' => '/contact',
            ],
            [
                'key' => 'home.faq.1',
                'label' => 'FAQ — première question',
                'position' => 82,
                'title' => 'Comment soumettre un dossier ?',
                'body' => 'Le dossier se dépose au secrétariat de l’école ou se transmet par e-mail à infos@escat.org : pièce d’identité, relevés de notes et dernier diplôme obtenu. Un conseiller revient vers vous pour l’entretien d’orientation.',
            ],
            [
                'key' => 'home.faq.2',
                'label' => 'FAQ — deuxième question',
                'position' => 83,
                'title' => 'Quels sont les délais d’inscription ?',
                'body' => 'Les inscriptions ouvrent avant chaque rentrée et se poursuivent jusqu’au remplissage des effectifs. Déposer son dossier tôt donne accès à un plus grand choix de filières.',
            ],
            [
                'key' => 'home.faq.3',
                'label' => 'FAQ — troisième question',
                'position' => 84,
                'title' => 'Peut-on régler la scolarité en plusieurs fois ?',
                'body' => 'Oui, un échelonnement est possible. Les modalités se fixent avec le service comptable au moment de l’inscription.',
            ],
            [
                'key' => 'home.faq.4',
                'label' => 'FAQ — quatrième question',
                'position' => 85,
                'title' => 'Peut-on passer du BTS à la Licence à l’ESCAT ?',
                'body' => 'Oui. Le cursus est conçu comme un continuum : un BTS obtenu à l’ESCAT ouvre directement l’accès à la Licence, puis au Master, dans la même filière.',
            ],
        ];

        foreach ($blocs as $bloc) {
            PageHeader::query()->updateOrCreate(
                ['key' => $bloc['key']],
                $bloc + ['group' => PageHeader::GROUPE_ACCUEIL],
            );
        }
    }
}
