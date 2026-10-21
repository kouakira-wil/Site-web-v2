<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PageHeader;
use Illuminate\Database\Seeder;

/**
 * Page Admission : critères par cycle, processus, programmes et FAQ.
 *
 * Le bandeau de la page vit avec les autres, dans PageHeaderSeeder.
 *
 * Les critères d'admission énoncent des règles que seule l'école peut fixer.
 * Les textes posés ici reprennent ce que le site dit déjà ailleurs ; là où
 * rien n'existait, l'intitulé est posé et le corps laissé vide, pour que la
 * page montre ce qui reste à écrire plutôt qu'une règle inventée.
 */
class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->criteres();
        $this->processus();
        $this->programmes();
        $this->faq();
    }

    private function poser(string $cle, array $valeurs, int $position): void
    {
        PageHeader::query()->updateOrCreate(
            ['key' => $cle],
            $valeurs + ['key' => $cle, 'group' => 'admission', 'position' => $position],
        );
    }

    private function criteres(): void
    {
        $this->poser('admission.criteres', [
            'label' => 'Critères — en-tête',
            'title' => 'Critères et Processus d’Admission',
            'intro' => 'Découvrez les conditions d’accès à nos formations et suivez les étapes pour postuler en toute simplicité.',
        ], 10);

        $blocs = [
            [
                'label' => 'Critères — conditions générales',
                'title' => 'Conditions Générales d’Inscription',
                'body' => "Le dossier se dépose au secrétariat de l’école ou se transmet par e-mail à infos@escat.org.\n\nIl comprend une pièce d’identité, les relevés de notes et le dernier diplôme obtenu. Un conseiller revient ensuite vers le candidat pour l’entretien d’orientation.",
            ],
            [
                'label' => 'Critères — CAP',
                'title' => 'CAP (Certificat d’Aptitude Professionnelle)',
                'body' => '',
            ],
            [
                'label' => 'Critères — Licence',
                'title' => 'Licence (BAC +3)',
                'body' => '',
            ],
            [
                'label' => 'Critères — Master',
                'title' => 'Master (BAC +5)',
                'body' => '',
            ],
        ];

        foreach ($blocs as $rang => $bloc) {
            $this->poser('admission.criteres.'.($rang + 1), $bloc, 11 + $rang);
        }
    }

    private function processus(): void
    {
        $this->poser('admission.processus', [
            'label' => 'Processus — en-tête',
            'title' => 'Processus d’Admission',
            'intro' => 'Suivez ces étapes simples pour soumettre votre candidature.',
        ], 20);

        $etapes = [
            ['user-check', 'Vérifier les critères d’éligibilité', 'Assurez-vous que vous répondez aux critères du cycle choisi.'],
            ['document', 'Remplir le formulaire', 'Renseignez vos informations personnelles et académiques.'],
            ['send', 'Soumettre les documents', 'Déposez vos pièces justificatives au secrétariat ou par e-mail.'],
            ['check-circle', 'Attente de validation', 'Votre dossier est examiné par notre équipe pédagogique.'],
            ['card', 'Finalisation de l’inscription', 'Réglez vos frais d’inscription pour valider votre place.'],
        ];

        foreach ($etapes as $rang => [$icone, $titre, $texte]) {
            $this->poser('admission.processus.'.($rang + 1), [
                'label' => 'Processus — étape '.($rang + 1),
                'icon' => $icone,
                'title' => $titre,
                'intro' => $texte,
            ], 21 + $rang);
        }
    }

    private function programmes(): void
    {
        $this->poser('admission.programmes', [
            'label' => 'Programmes — en-tête',
            'title' => 'Nos Programmes Académiques',
            'intro' => 'Découvrez nos formations et leurs débouchés professionnels.',
        ], 30);
    }

    private function faq(): void
    {
        $this->poser('admission.faq', [
            'label' => 'FAQ — en-tête',
            'title' => 'Foire aux Questions',
            'intro' => 'Retrouvez ici les réponses aux questions les plus fréquentes sur l’admission.',
        ], 40);

        $questions = [
            [
                'Comment soumettre un dossier ?',
                'Le dossier se dépose au secrétariat de l’école ou se transmet par e-mail à infos@escat.org : pièce d’identité, relevés de notes et dernier diplôme obtenu. Un conseiller revient vers vous pour l’entretien d’orientation.',
            ],
            [
                'Quels sont les délais d’inscription ?',
                'Les inscriptions ouvrent avant chaque rentrée et se poursuivent jusqu’au remplissage des effectifs. Déposer son dossier tôt donne accès à un plus grand choix de filières.',
            ],
            [
                'Peut-on régler la scolarité en plusieurs fois ?',
                'Oui, un échelonnement est possible. Les modalités se fixent avec le service comptable au moment de l’inscription.',
            ],
            [
                'Peut-on passer d’un cycle au suivant à l’ESCAT ?',
                'Oui. Le cursus est conçu comme un continuum : un diplôme obtenu à l’ESCAT ouvre l’accès au cycle supérieur, dans la même filière.',
            ],
        ];

        foreach ($questions as $rang => [$titre, $reponse]) {
            $this->poser('admission.faq.'.($rang + 1), [
                'label' => 'FAQ — question '.($rang + 1),
                'title' => $titre,
                'body' => $reponse,
            ], 41 + $rang);
        }
    }
}
