<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Cycle;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Local development seed data for the ESCAT site.
 *
 * The seeded password is a well-known development placeholder and must never
 * be used on a deployed environment.
 */
class DatabaseSeeder extends Seeder
{
    private const DEV_PASSWORD = 'password';

    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@escat.org'],
            [
                'name' => 'Administrateur ESCAT',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make(self::DEV_PASSWORD),
                'email_verified_at' => now(),
            ],
        );

        $this->seedCycles();
        $this->seedFilieres();
        $this->seedActualites($admin);
        $this->seedEquipe();
        $this->call(PageHeaderSeeder::class);
        $this->call(HomeSectionSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(SchoolIdentitySeeder::class);
        $this->call(AboutIntroSeeder::class);
        $this->call(VisionStepsSeeder::class);
        $this->call(WhyEscatSeeder::class);
        $this->call(HomeAboutSeeder::class);
        $this->call(TeamApproachSeeder::class);
        $this->call(DirectorWordSeeder::class);

        ContactMessage::factory()->count(7)->create();
        ContactMessage::factory()->count(3)->create([
            'status' => ContactMessage::STATUS_READ,
            'read_at' => now()->subDays(2),
        ]);
    }


    /**
     * Study cycles, in ascending order of level.
     */
    private function seedCycles(): void
    {
        $cycles = [
            ['CAP', 'CAP', 'Certificat d’Aptitude Professionnelle - une formation courte et pratique pour entrer rapidement dans le métier.'],
            ['Cycle BTS', 'BAC +2', 'Deux ans pour acquérir les compétences techniques attendues en entreprise.'],
            ['Cycle Licence', 'BAC +3', 'Trois ans pour approfondir un domaine et consolider un projet professionnel.'],
            ['Cycle Master', 'BAC +5', 'Cinq ans pour devenir expert de son domaine et encadrer des équipes.'],
        ];

        foreach ($cycles as $index => [$nom, $niveau, $description]) {
            Cycle::query()->create([
                'name' => $nom,
                'short_label' => $niveau,
                'description' => $description,
                'position' => $index,
            ]);
        }
    }

    /**
     * Filières, grouped by the school's two teaching poles.
     *
     * The `cycles` key is DEMO data: escat.org does not publish which filière
     * runs at which cycle, so the mapping must be corrected from the back
     * office rather than trusted as-is.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    private function filieres(): array
    {
        return [
            'Pôle Technologie' => [
                [
                    'title' => 'Réseaux et Télécommunications',
                    'objectives' => "Concevoir une architecture réseau adaptée à un besoin
Installer et configurer les équipements actifs
Superviser et diagnostiquer une infrastructure en production",
                    'skills' => "Câblage et commutation
Routage IP
Liaisons faisceaux hertziens
Supervision et métrologie",
                    'outcomes' => "Technicien réseaux
Administrateur infrastructure
Technicien télécoms
Support niveau 2",
                    'audience' => "Titulaires du BAC ou d’un CAP technique
Professionnels en reconversion vers les métiers du réseau",
                    'learning_blocks' => [
                        ['title' => 'Enseignements fondamentaux', 'description' => 'Les bases théoriques du domaine, revues avec les entreprises du secteur.', 'points' => ['Cours magistraux', 'Travaux dirigés', 'Évaluations continues']],
                        ['title' => 'Ateliers pratiques', 'description' => 'Des travaux pratiques sur équipements réels, encadrés par des professionnels en exercice.', 'points' => ['Équipements réels', 'Encadrement métier', 'Projets notés']],
                        ['title' => 'Stage et insertion', 'description' => 'Un stage encadré en entreprise et un accompagnement jusqu’à l’insertion.', 'points' => ['Stage en entreprise', 'Suivi individuel', 'Réseau d’anciens']],
                    ],
                    'cycles' => ['BAC +2', 'BAC +3', 'BAC +5'],
                    'excerpt' => 'Concevoir, déployer et superviser les infrastructures réseau et les liaisons de télécommunication.',
                    'description' => "La filière forme des techniciens capables d'installer et de maintenir des infrastructures réseau fiables : câblage, commutation, routage, liaisons faisceaux hertziens et supervision.\n\nLes travaux pratiques se déroulent sur équipements réels, encadrés par des professionnels en exercice.",
                ],
                [
                    'title' => 'Génie Informatique et Réseaux',
                    'objectives' => "Développer une application répondant à un cahier des charges
Administrer systèmes et bases de données
Sécuriser un système d’information",
                    'skills' => "Programmation
Administration système
Bases de données
Sécurité applicative",
                    'outcomes' => "Développeur d’applications
Administrateur systèmes et réseaux
Chef de projet informatique",
                    'audience' => "Titulaires du BAC scientifique ou technique
Candidats motivés par le développement logiciel",
                    'learning_blocks' => [
                        ['title' => 'Enseignements fondamentaux', 'description' => 'Les bases théoriques du domaine, revues avec les entreprises du secteur.', 'points' => ['Cours magistraux', 'Travaux dirigés', 'Évaluations continues']],
                        ['title' => 'Ateliers pratiques', 'description' => 'Des travaux pratiques sur équipements réels, encadrés par des professionnels en exercice.', 'points' => ['Équipements réels', 'Encadrement métier', 'Projets notés']],
                        ['title' => 'Stage et insertion', 'description' => 'Un stage encadré en entreprise et un accompagnement jusqu’à l’insertion.', 'points' => ['Stage en entreprise', 'Suivi individuel', 'Réseau d’anciens']],
                    ],
                    'cycles' => ['BAC +2', 'BAC +3', 'BAC +5'],
                    'excerpt' => 'Développement, administration système et sécurité des systèmes d’information.',
                    'description' => "Ce parcours associe développement logiciel, administration des systèmes et des bases de données, et sécurité des réseaux.\n\nÀ la sortie, l'étudiant conçoit une architecture, la déploie et la maintient en conditions réelles.",
                ],
                [
                    'title' => 'Électromécanique et Maintenance Industrielle',
                    'objectives' => "Diagnostiquer une panne sur un équipement industriel
Réaliser la maintenance préventive et corrective
Respecter les règles de sécurité sur site",
                    'skills' => "Mécanique industrielle
Électricité industrielle
Automatismes
Hygiène et sécurité",
                    'outcomes' => "Technicien de maintenance Électromécanicien Agent de production",
                    'audience' => "Titulaires du BEPC pour le CAP Titulaires du BAC pour les cycles supérieurs",
                    'learning_blocks' => [
                        ['title' => 'Enseignements fondamentaux', 'description' => 'Les bases théoriques du domaine, revues avec les entreprises du secteur.', 'points' => ['Cours magistraux', 'Travaux dirigés', 'Évaluations continues']],
                        ['title' => 'Ateliers pratiques', 'description' => 'Des travaux pratiques sur équipements réels, encadrés par des professionnels en exercice.', 'points' => ['Équipements réels', 'Encadrement métier', 'Projets notés']],
                        ['title' => 'Stage et insertion', 'description' => 'Un stage encadré en entreprise et un accompagnement jusqu’à l’insertion.', 'points' => ['Stage en entreprise', 'Suivi individuel', 'Réseau d’anciens']],
                    ],
                    'cycles' => ['CAP', 'BAC +2', 'BAC +3'],
                    'excerpt' => 'Maintenance des équipements industriels, automatismes et sécurité des installations.',
                    'description' => "La formation couvre la mécanique, l'électricité industrielle et les automatismes.\n\nElle prépare aux fonctions de maintenance préventive et corrective sur les sites de production.",
                ],
            ],
            'Pôle Gestion' => [
                [
                    'title' => 'Comptabilité, Banque et Finance',
                    'objectives' => "Tenir une comptabilité générale et analytique
Établir et interpréter les états financiers
Participer au pilotage financier de l’entreprise",
                    'skills' => "Comptabilité générale
Analyse financière
Fiscalité
Opérations bancaires",
                    'outcomes' => "Comptable
Assistant financier
Chargé de clientèle bancaire
Contrôleur de gestion junior",
                    'audience' => "Titulaires du BAC, séries générales ou techniques
Professionnels souhaitant valider leurs acquis",
                    'learning_blocks' => [
                        ['title' => 'Enseignements fondamentaux', 'description' => 'Les bases théoriques du domaine, revues avec les entreprises du secteur.', 'points' => ['Cours magistraux', 'Travaux dirigés', 'Évaluations continues']],
                        ['title' => 'Ateliers pratiques', 'description' => 'Des travaux pratiques sur équipements réels, encadrés par des professionnels en exercice.', 'points' => ['Équipements réels', 'Encadrement métier', 'Projets notés']],
                        ['title' => 'Stage et insertion', 'description' => 'Un stage encadré en entreprise et un accompagnement jusqu’à l’insertion.', 'points' => ['Stage en entreprise', 'Suivi individuel', 'Réseau d’anciens']],
                    ],
                    'cycles' => ['BAC +2', 'BAC +3', 'BAC +5'],
                    'excerpt' => 'Tenue comptable, analyse financière et pilotage de la performance de l’entreprise.',
                    'description' => "Le parcours forme aux techniques comptables, à l'analyse des états financiers et aux opérations bancaires.\n\nLes études de cas s'appuient sur des situations d'entreprises du Congo.",
                ],
                [
                    'title' => 'Administration des Entreprises',
                    'objectives' => "Organiser et suivre l’activité administrative
Accompagner la prise de décision
Gérer les achats et les ressources",
                    'skills' => "Organisation administrative
Gestion des ressources humaines
Gestion des achats
Outils bureautiques",
                    'outcomes' => "Assistant de direction
Gestionnaire administratif
Chargé des ressources humaines",
                    'audience' => "Titulaires du BAC
Professionnels en poste souhaitant évoluer",
                    'learning_blocks' => [
                        ['title' => 'Enseignements fondamentaux', 'description' => 'Les bases théoriques du domaine, revues avec les entreprises du secteur.', 'points' => ['Cours magistraux', 'Travaux dirigés', 'Évaluations continues']],
                        ['title' => 'Ateliers pratiques', 'description' => 'Des travaux pratiques sur équipements réels, encadrés par des professionnels en exercice.', 'points' => ['Équipements réels', 'Encadrement métier', 'Projets notés']],
                        ['title' => 'Stage et insertion', 'description' => 'Un stage encadré en entreprise et un accompagnement jusqu’à l’insertion.', 'points' => ['Stage en entreprise', 'Suivi individuel', 'Réseau d’anciens']],
                    ],
                    'cycles' => ['BAC +2', 'BAC +3'],
                    'excerpt' => 'Organisation, gestion administrative et accompagnement de la décision.',
                    'description' => "La filière prépare aux fonctions support : administration, ressources humaines, gestion des achats et suivi de l'activité.",
                ],
            ],
        ];
    }

    private function seedFilieres(): void
    {
        $position = 0;
        $categoryPosition = 0;

        foreach ($this->filieres() as $pole => $filieres) {
            $category = Category::query()->create([
                'name' => $pole,
                'type' => Category::TYPE_PROJECT,
                'position' => $categoryPosition++,
            ]);

            foreach ($filieres as $filiere) {
                $projet = Project::query()->create([
                    'category_id' => $category->id,
                    'title' => $filiere['title'],
                    'excerpt' => $filiere['excerpt'],
                    'description' => $filiere['description'],
                    'objectives' => $filiere['objectives'],
                    'skills' => $filiere['skills'],
                    'outcomes' => $filiere['outcomes'],
                    'audience' => $filiere['audience'],
                    'learning_blocks' => $filiere['learning_blocks'],
                    'client' => 'ESCAT',
                    'year' => 2026,
                    'status' => Project::STATUS_PUBLISHED,
                    'is_featured' => $position < 4,
                    'position' => $position,
                    'published_at' => now()->subDays(++$position),
                ]);

                // Rattachement de DÉMONSTRATION : la répartition réelle des
                // filières par cycle est à corriger depuis le back-office.
                $projet->cycles()->attach(
                    Cycle::query()->whereIn('short_label', $filiere['cycles'])->pluck('id')
                );
            }
        }
    }

    private function seedActualites(User $admin): void
    {
        $categories = collect(['Vie de l’école', 'Événements', 'Orientation'])
            ->map(fn (string $name, int $index): Category => Category::query()->create([
                'name' => $name,
                'type' => Category::TYPE_POST,
                'position' => $index,
            ]));

        $tags = collect(['Admissions', 'Travaux pratiques', 'Stages', 'Diplômés', 'Portes ouvertes'])
            ->map(fn (string $name): Tag => Tag::query()->create(['name' => $name]));

        $actualites = [
            ['Soutenance publique des étudiants finalistes à l’ESCAT', '2024-12-21',
                'Les étudiants finalistes présentent leurs projets devant un jury composé d’enseignants et de professionnels du secteur.'],
            ['Bal de fin d’année organisé par les étudiants', '2024-08-17',
                'Une soirée organisée par les étudiants pour clôturer l’année académique.'],
            ['TP : installation de liaisons FH avec des antennes Ubiquiti', '2024-05-10',
                'Travaux pratiques sur équipements réels, encadrés par nos intervenants du pôle Technologie.'],
            ['L’ESCAT célèbre la Journée internationale des jeunes filles dans les TIC', '2024-04-25',
                'Des activités pour encourager les jeunes filles à explorer les carrières du numérique.'],
            ['Rentrée académique : les inscriptions sont ouvertes', '2024-09-02',
                'Le dossier se dépose au secrétariat ou s’envoie à infos@escat.org.'],
        ];

        foreach ($actualites as $index => [$titre, $date, $accroche]) {
            $post = Post::query()->create([
                'user_id' => $admin->id,
                'category_id' => $categories[$index % $categories->count()]->id,
                'title' => $titre,
                'excerpt' => $accroche,
                'body' => $accroche."\n\nCette actualité est un contenu de démonstration : remplacez-la depuis le back-office.",
                'status' => Post::STATUS_PUBLISHED,
                'published_at' => $date,
            ]);

            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id')->all());
        }
    }

    private function seedEquipe(): void
    {
        $membres = [
            ['Direction pédagogique', 'Coordination des cycles et des programmes'],
            ['Responsable Pôle Technologie', 'Réseaux, informatique et électromécanique'],
            ['Responsable Pôle Gestion', 'Comptabilité, banque et finance'],
            ['Service des admissions', 'Orientation, dossiers et inscriptions'],
            ['Service de la scolarité', 'Suivi des étudiants et vie étudiante'],
        ];

        foreach ($membres as $index => [$nom, $role]) {
            TeamMember::query()->create([
                'name' => $nom,
                'role' => $role,
                'bio' => 'Contenu de démonstration : renseignez la fiche depuis le back-office.',
                'email' => 'infos@escat.org',
                'is_active' => true,
                'position' => $index,
            ]);
        }
    }
}
