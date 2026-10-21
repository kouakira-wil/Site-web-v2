<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * Contenu des fiches filières, repris du site escat.org (CAP, Licence, Master).
 *
 * Chaque page y suit le même plan : présentation, objectifs, compétences
 * clés, débouchés, « Les Atouts de Notre Programme » et public visé. Ces
 * rubriques correspondent une à une aux champs d'une filière.
 *
 * Deux écarts assumés avec la source :
 *
 *  - Les accroches ne sont pas reprises. « Connectez le Monde de Demain »
 *    coiffe cinq pages différentes : c'est une bannière, pas la promesse
 *    d'une filière. Les accroches déjà écrites ici sont conservées, et les
 *    nouvelles filières en reçoivent une tirée de leur propre présentation.
 *
 *  - Deux rubriques de la source sont visiblement recopiées d'une autre
 *    fiche : le public visé de Réseaux & Télécommunications parle
 *    d'électricité. Dans ce cas, le texte local est gardé.
 *
 * Les doublons internes aux listes (« Business Developer » deux fois, un
 * objectif répété) sont retirés : ce sont des coquilles, pas du contenu.
 */
class FilieresEscatOrgSeeder extends Seeder
{
    public function run(): void
    {
        $cycles = Cycle::query()->get()->keyBy('slug');

        foreach ($this->filieres() as $position => $donnees) {
            $pole = Category::query()->where('slug', $donnees['pole'])->first();

            $filiere = Project::query()->updateOrCreate(
                ['slug' => $donnees['slug']],
                [
                    'title' => $donnees['title'],
                    'category_id' => $pole?->id,
                    'excerpt' => $donnees['excerpt'],
                    'description' => $donnees['description'],
                    'objectives' => $donnees['objectives'],
                    'skills' => $donnees['skills'],
                    'outcomes' => $donnees['outcomes'],
                    'audience' => $donnees['audience'],
                    'learning_blocks' => $donnees['learning_blocks'],
                    'status' => Project::STATUS_PUBLISHED,
                    'published_at' => now(),
                    'position' => $position,
                ],
            );

            // Le rattachement s'ajoute sans défaire les cycles déjà associés :
            // une filière peut s'ouvrir sur plusieurs niveaux.
            $cycle = $cycles->get($donnees['cycle'] ?? 'cycle-licence');

            if ($cycle !== null) {
                $filiere->cycles()->syncWithoutDetaching([$cycle->id]);
            }
        }
    }

    /**
     * @param  array<int, string>  $lignes
     */
    private function liste(array $lignes): string
    {
        // Une entrée par ligne, doublons retirés en conservant l'ordre.
        return implode("\n", array_unique($lignes));
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $atouts
     * @return array<int, array{title: string, description: string, points: array<int, string>, image: null}>
     */
    private function atouts(array $atouts): array
    {
        return array_map(
            fn (array $atout): array => [
                'title' => $atout[0],
                'description' => $atout[1],
                'points' => [],
                'image' => null,
            ],
            $atouts,
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filieres(): array
    {
        return [
            [
                'slug' => 'reseaux-et-telecommunications',
                'title' => 'Réseaux et Télécommunications',
                'pole' => 'pole-technologie',
                'excerpt' => 'Concevoir, déployer et superviser les infrastructures réseau et les liaisons de télécommunication.',
                'description' => 'La filière Réseau et Télécommunication vous prépare à devenir un expert des infrastructures qui connectent les individus, les entreprises et les technologies à travers le monde. Dans un secteur en pleine expansion, cette formation vous offre les compétences nécessaires pour concevoir, déployer et sécuriser des réseaux de communication performants.',
                'objectives' => $this->liste([
                    'Maîtriser les technologies avancées en réseaux (5G, IoT, Cloud Computing).',
                    'Concevoir et gérer des architectures réseau robustes et évolutives.',
                    'Appliquer des solutions innovantes pour répondre aux besoins de connectivité.',
                    'Assurer la sécurité et l’optimisation des systèmes de communication.',
                ]),
                'skills' => $this->liste([
                    'Administration de réseaux locaux (LAN), étendus (WAN) et sans fil (WLAN).',
                    'Configuration et gestion d’équipements réseau (routeurs, commutateurs, pare-feu).',
                    'Développement et gestion de services VoIP, IPTV et communications mobiles.',
                    'Mise en œuvre de la cybersécurité pour protéger les infrastructures.',
                    'Sécurisation des systèmes et réseaux (firewalls, IDS/IPS, audits de sécurité).',
                ]),
                'outcomes' => $this->liste([
                    'Administrateur Réseau et Systèmes',
                    'Ingénieur Réseaux et Systèmes',
                    'Développeur Logiciel ou Web',
                    'Chef de Projet IT',
                    'Analyste Big Data',
                    'Spécialiste Cloud Computing',
                    'Ingénieur Infrastructure IT',
                    'Ingénieur DevOps',
                    'Analyste Réseau',
                    'Responsable d’Exploitation Réseau',
                    'Responsable Data Center',
                ]),
                // Le public visé de la source décrit la filière Électricité
                // Industrielle : le texte local est conservé.
                'audience' => $this->liste([
                    'Titulaires du BAC ou d’un CAP technique',
                    'Professionnels en reconversion vers les métiers du réseau',
                ]),
                'learning_blocks' => $this->atouts([
                    ['Apprentissage pratique', 'Laboratoires équipés de technologies de pointe (Cisco, Huawei, etc.).'],
                    ['Projets concrets', 'Études de cas et simulations basées sur des problématiques réelles.'],
                    ['Partenariats', 'Collaboration avec des leaders du secteur pour des stages et des opportunités de carrière.'],
                ]),
            ],

            [
                'slug' => 'genie-informatique-et-reseaux',
                'title' => 'Génie Informatique et Réseaux',
                'pole' => 'pole-technologie',
                'excerpt' => 'Une formation polyvalente qui allie informatique, systèmes d’information et réseaux.',
                'description' => 'La filière Génie Informatique et Réseaux est une formation polyvalente qui allie les fondamentaux de l’informatique, des systèmes d’information et des réseaux. Conçue pour répondre aux besoins d’un monde numérique en constante évolution, cette filière vous prépare à relever les défis techniques les plus complexes et à jouer un rôle clé dans le développement de solutions technologiques innovantes.',
                'objectives' => $this->liste([
                    'Concevoir, développer et déployer des solutions logicielles performantes.',
                    'Maîtriser l’architecture et l’administration des systèmes et réseaux.',
                    'Intégrer les nouvelles technologies (Big Data, IA) dans des environnements réseau.',
                    'Maîtriser les fondamentaux de l’informatique et des réseaux : programmation, architecture système et infrastructure réseau.',
                    'Développer des applications logicielles performantes et sécurisées.',
                ]),
                'skills' => $this->liste([
                    'Programmation avancée (Python, Java, C++) et développement d’applications web et mobiles.',
                    'Conception et gestion d’architectures réseau (LAN, WAN, WLAN, 5G).',
                    'Administration de systèmes (Windows, Linux) et gestion des bases de données (SQL, NoSQL).',
                    'Analyse et résolution de problèmes informatiques complexes.',
                    'Mise en œuvre de solutions cloud et virtualisation (VMware, Azure, AWS).',
                    'Sécurisation des systèmes et réseaux (firewalls, IDS/IPS, audits de sécurité).',
                ]),
                'outcomes' => $this->liste([
                    'Administrateur Réseau et Systèmes',
                    'Technicien support informatique',
                    'Développeur logiciel',
                    'Développeur web et mobile',
                    'Ingénieur en développement informatique',
                    'Ingénieur en cybersécurité',
                    'Architecte cloud',
                    'Consultant en sécurité informatique',
                    'Administrateur de bases de données',
                    'Data analyst',
                    'Ingénieur big data',
                    'Ingénieur télécoms',
                    'Spécialiste IoT',
                ]),
                'audience' => 'Cette filière s’adresse aux passionnés de technologie, d’innovation logicielle et d’infrastructures réseau, qui souhaitent jouer un rôle clé dans la transformation numérique des entreprises et des organisations.',
                'learning_blocks' => $this->atouts([
                    ['Apprentissage pratique', 'Laboratoires équipés de technologies de pointe (Cisco, Huawei, etc.).'],
                    ['Projets concrets', 'Études de cas et simulations basées sur des problématiques réelles.'],
                    ['Collaboration internationale', 'Collaboration avec des leaders du secteur pour des stages et des opportunités de carrière.'],
                ]),
            ],

            [
                'slug' => 'electromecanique-et-maintenance-industrielle',
                'title' => 'Électromécanique et Maintenance Industrielle',
                'pole' => 'pole-technologie',
                'excerpt' => 'Concevoir, entretenir et optimiser les systèmes mécaniques et électriques des industries modernes.',
                'description' => 'La filière Électromécanique et Maintenance Industrielle forme des experts capables de concevoir, d’entretenir et d’optimiser les systèmes mécaniques et électriques qui constituent le cœur des industries modernes. Ce programme met l’accent sur l’innovation technologique, la maintenance prédictive et les solutions durables pour répondre aux défis d’un environnement industriel en constante évolution.',
                'objectives' => $this->liste([
                    'Maîtriser les principes fondamentaux de la mécanique, de l’électronique et de l’automatisation industrielle.',
                    'Développer des compétences en maintenance préventive, corrective et prédictive.',
                    'Concevoir et intégrer des systèmes électromécaniques performants et fiables.',
                    'Diagnostiquer et résoudre des problématiques techniques complexes.',
                    'Intégrer les nouvelles technologies (robotique, gestion énergétique) dans les processus industriels.',
                ]),
                'skills' => $this->liste([
                    'Conception et assemblage de systèmes électromécaniques.',
                    'Programmation et configuration d’automates industriels.',
                    'Utilisation d’outils de maintenance assistée par ordinateur (GMAO).',
                    'Diagnostic et réparation de pannes mécaniques, électriques et électroniques.',
                    'Optimisation énergétique des équipements industriels.',
                    'Application des normes de sécurité et des réglementations environnementales.',
                ]),
                'outcomes' => $this->liste([
                    'Technicien en Maintenance Industrielle',
                    'Ingénieur Électromécanicien',
                    'Responsable Maintenance et Exploitation',
                    'Chef de Projet en Automatisation',
                    'Consultant en Optimisation Énergétique',
                    'Technicien en Contrôle Qualité et Sécurité',
                    'Expert en Robotique Industrielle',
                    'Ingénieur en Systèmes Automatisés',
                    'Superviseur de Production Industrielle',
                ]),
                'audience' => 'Cette filière s’adresse aux passionnés de technologie et de mécanique, souhaitant relever les défis techniques de l’industrie moderne et devenir des acteurs clés dans l’optimisation des performances industrielles.',
                'learning_blocks' => $this->atouts([
                    ['Formation multidisciplinaire', 'Alliant électromécanique, automatisation et gestion industrielle.'],
                    ['Innovations technologiques', 'Découverte des outils de maintenance connectée et des solutions industrielles durables.'],
                    ['Partenariats', 'Collaboration avec des leaders du secteur pour des stages et des opportunités de carrière.'],
                ]),
            ],

            [
                'slug' => 'comptabilite-banque-et-finance',
                'title' => 'Comptabilité, Banque et Finance',
                'pole' => 'pole-gestion',
                'excerpt' => 'Devenir un acteur clé de la stratégie économique des entreprises.',
                'description' => 'La filière Comptabilité, Banque et Finance vous forme à devenir un acteur clé de la stratégie économique des entreprises. En combinant des compétences analytiques, des outils numériques avancés et une solide connaissance des marchés financiers, cette formation vous prépare à relever les défis complexes de la gestion et de l’optimisation des ressources financières.',
                'objectives' => $this->liste([
                    'Maîtriser les fondamentaux de la finance : comptabilité, analyse financière et fiscalité.',
                    'Concevoir et gérer des stratégies financières pour assurer la rentabilité et la pérennité des organisations.',
                    'Apprendre à utiliser des outils numériques modernes pour l’analyse de données financières.',
                    'Comprendre les dynamiques des marchés financiers et gérer les investissements.',
                    'Optimiser les processus budgétaires et les performances économiques.',
                ]),
                'skills' => $this->liste([
                    'Analyse et interprétation des états financiers (bilan, compte de résultat, flux de trésorerie).',
                    'Gestion de trésorerie, contrôle budgétaire et planification stratégique.',
                    'Élaboration de politiques d’investissement et de gestion des risques financiers.',
                    'Utilisation d’outils numériques (Excel avancé, logiciels ERP, Power BI, Tableau).',
                    'Connaissance des normes internationales (IFRS) et des réglementations fiscales.',
                    'Conseil et audit financier pour la prise de décision stratégique.',
                ]),
                'outcomes' => $this->liste([
                    'Analyste Financier',
                    'Comptable ou Expert-Comptable',
                    'Responsable de la Trésorerie',
                    'Auditeur Financier',
                    'Contrôleur de Gestion',
                    'Responsable des Risques Financiers',
                    'Consultant en Gestion Financière',
                    'Gestionnaire de Portefeuille',
                    'Responsable Administratif et Financier (RAF)',
                    'Directeur Financier (CFO)',
                    'Chargé d’Affaires Bancaires',
                    'Spécialiste en Finance Durable et ESG',
                ]),
                'audience' => 'Cette filière s’adresse aux étudiants souhaitant maîtriser les mécanismes financiers pour bâtir une carrière dans la gestion stratégique des ressources économiques et la prise de décision éclairée.',
                'learning_blocks' => $this->atouts([
                    ['Apprentissage pratique', 'Études de cas réels, simulations et projets en partenariat avec des entreprises.'],
                    ['Approche digitale', 'Intégration des technologies numériques dans l’analyse et la gestion financière.'],
                    ['Partenariats', 'Collaboration avec des leaders du secteur pour des stages et des opportunités de carrière.'],
                ]),
            ],

            [
                'slug' => 'gestion-des-ressources-humaines',
                'title' => 'Gestion des Ressources Humaines',
                'pole' => 'pole-gestion',
                'excerpt' => 'L’art de manager le capital humain.',
                'description' => 'La filière Gestion des Ressources Humaines (GRH) forme des professionnels capables d’accompagner les organisations dans la gestion et le développement de leur principal atout : leurs talents. En alliant des compétences en management, en droit du travail et en stratégie organisationnelle, cette formation prépare à relever les défis humains des entreprises dans un monde en constante évolution.',
                'objectives' => $this->liste([
                    'Acquérir une vision stratégique de la gestion des ressources humaines.',
                    'Développer des compétences en recrutement, formation et gestion des carrières.',
                    'Maîtriser les outils et techniques de gestion administrative et de paie.',
                    'Apprendre à gérer les relations sociales et à favoriser un climat de travail harmonieux.',
                    'Piloter des projets RH innovants pour accompagner les transformations organisationnelles.',
                ]),
                'skills' => $this->liste([
                    'Recrutement : élaboration de stratégies, conduite d’entretiens et intégration des talents.',
                    'Formation et développement des compétences : conception de plans de formation et évaluation des performances.',
                    'Gestion de la paie et administration du personnel : maîtrise des outils RH et des normes légales.',
                    'Droit du travail et relations sociales : prévention et gestion des conflits, négociation avec les partenaires sociaux.',
                    'Digitalisation RH : utilisation de logiciels de gestion (SIRH) et d’outils numériques pour optimiser les processus RH.',
                ]),
                'outcomes' => $this->liste([
                    'Responsable des Ressources Humaines',
                    'Chargé(e) de Recrutement',
                    'Gestionnaire de Paie et Administration du Personnel',
                    'Responsable Formation et Développement',
                    'Consultant en Stratégie RH',
                    'Talent Acquisition Manager',
                    'Responsable Relations Sociales',
                    'Coordinateur de Projets RH',
                    'Spécialiste en Diversité et Inclusion',
                    'HR Business Partner',
                    'Conseiller en Mobilité Professionnelle',
                ]),
                'audience' => 'Cette filière s’adresse aux étudiants passionnés par les relations humaines et désireux de devenir des acteurs clés dans le développement des organisations.',
                'learning_blocks' => $this->atouts([
                    ['Apprentissage pratique', 'Études de cas réels, jeux de rôle et simulations pour développer vos compétences sur le terrain.'],
                    ['Expertise multidisciplinaire', 'Un équilibre entre management, droit, psychologie et technologie.'],
                    ['Approche innovante', 'Une focalisation sur les tendances actuelles, telles que la gestion des talents à l’ère digitale et la RSE.'],
                ]),
            ],

            [
                'slug' => 'electricite-industrielle',
                'title' => 'Électricité Industrielle',
                'pole' => 'pole-technologie',
                'excerpt' => 'Concevoir, installer, maintenir et optimiser des systèmes électriques industriels modernes.',
                'description' => 'La filière Électricité Industrielle forme des professionnels capables de concevoir, installer, maintenir et optimiser des systèmes électriques industriels modernes. Au cœur des processus de production et des infrastructures, cette spécialisation vous prépare à relever les défis techniques des industries de demain grâce à une expertise en électricité, automatisation et énergies renouvelables.',
                'objectives' => $this->liste([
                    'Maîtriser les principes fondamentaux de l’électricité industrielle et des systèmes automatisés.',
                    'Concevoir et dimensionner des installations électriques performantes et conformes aux normes.',
                    'Diagnostiquer, maintenir et optimiser des systèmes électriques complexes.',
                    'Intégrer des solutions d’automatisation et de robotique dans les processus industriels.',
                    'Exploiter des sources d’énergies renouvelables pour des systèmes durables et efficaces.',
                ]),
                'skills' => $this->liste([
                    'Lecture et interprétation des schémas électriques et plans techniques.',
                    'Programmation et gestion des automates industriels (API/PLC).',
                    'Conception et maintenance des installations électriques industrielles.',
                    'Maîtrise des systèmes de variation de vitesse et d’entraînement électrique.',
                    'Intégration des énergies renouvelables dans les systèmes industriels.',
                    'Gestion des systèmes de supervision (SCADA).',
                ]),
                'outcomes' => $this->liste([
                    'Conception et maintenance des systèmes industriels électriques',
                    'Supervision des équipes et maintenance des installations',
                    'Création de systèmes électriques conformes aux normes',
                    'Mise en œuvre des solutions d’automatisation dans l’industrie',
                    'Installation et gestion des systèmes d’énergie durable',
                    'Gestion de projets complexes en ingénierie électrique',
                ]),
                'audience' => 'Cette filière s’adresse aux passionnés de l’électricité, de l’automatisation et de la transition énergétique, qui souhaitent contribuer au développement de systèmes industriels modernes et durables.',
                'learning_blocks' => $this->atouts([
                    ['Innovations technologiques', 'Explorez des outils de pointe et des méthodologies modernes.'],
                    ['Approche durable', 'Nous mettons l’accent sur le développement durable et la responsabilité sociale.'],
                    ['Collaboration internationale', 'Profitez d’opportunités d’échanges avec des partenaires internationaux.'],
                ]),
            ],

            [
                'slug' => 'mecanique-et-maintenance-industrielle',
                'title' => 'Mécanique et Maintenance Industrielle',
                'pole' => 'pole-technologie',
                'excerpt' => 'Concevoir, entretenir et réparer les équipements mécaniques indispensables à la production.',
                'description' => 'La filière Mécanique et Maintenance forme des experts capables de concevoir, d’entretenir et de réparer les équipements mécaniques et industriels indispensables à la production. Alliant connaissances techniques, rigueur et innovation, cette formation prépare à relever les défis de l’industrie 4.0 en intégrant les nouvelles technologies et les standards de durabilité.',
                'objectives' => $this->liste([
                    'Acquérir des compétences solides en conception, montage et maintenance d’équipements mécaniques.',
                    'Développer une expertise en diagnostic, réparation et optimisation des systèmes industriels.',
                    'Intégrer des technologies modernes comme l’automatisation, la robotique et la maintenance prédictive.',
                    'Appliquer des solutions durables pour optimiser la performance énergétique des systèmes.',
                ]),
                'skills' => $this->liste([
                    'Conception mécanique assistée par ordinateur (CAO) et fabrication assistée par ordinateur (FAO).',
                    'Maîtrise des systèmes hydrauliques, pneumatiques et électromécaniques.',
                    'Maintenance corrective, préventive et prédictive des équipements industriels.',
                    'Diagnostic des pannes et mise en œuvre de solutions adaptées.',
                    'Programmation et maintenance de robots industriels et d’automatismes.',
                    'Application des normes de sécurité et de gestion des risques industriels.',
                ]),
                'outcomes' => $this->liste([
                    'Technicien ou Ingénieur en Maintenance Industrielle',
                    'Responsable Maintenance et Fiabilité',
                    'Concepteur en Mécanique Industrielle',
                    'Technicien en Automatisation et Robotique',
                    'Superviseur d’Atelier de Production',
                    'Ingénieur Méthodes et Industrialisation',
                    'Consultant en Optimisation des Systèmes Industriels',
                    'Spécialiste en Gestion des Énergies et Maintenance Durable',
                    'Chef de Projet en Maintenance Prédictive',
                ]),
                'audience' => 'Cette filière s’adresse aux passionnés de mécanique, d’automatisation et de transition énergétique, qui souhaitent contribuer au développement de systèmes industriels modernes et durables.',
                // La source affiche deux atouts intitulés « Durabilité » : seul
                // celui qui parle vraiment de durabilité est conservé, l'autre
                // est rendu à son texte, qui porte sur les partenariats.
                'learning_blocks' => $this->atouts([
                    ['Formation complète', 'Alliant théorie et pratique, avec des ateliers et des équipements industriels modernes.'],
                    ['Partenariats', 'Études de cas et collaborations avec des entreprises leaders du secteur.'],
                    ['Focus durabilité', 'Formation aux pratiques écoresponsables pour une industrie plus verte.'],
                ]),
            ],

            [
                'slug' => 'gestion-commerciale',
                'title' => 'Gestion Commerciale',
                'pole' => 'pole-gestion',
                'excerpt' => 'Piloter des stratégies commerciales pour optimiser la performance des entreprises.',
                'description' => 'La filière Gestion Commerciale permet d’acquérir des compétences en marketing, négociation, gestion de la relation client et management commercial, tout en intégrant les outils numériques modernes.',
                'objectives' => $this->liste([
                    'Acquérir une maîtrise des fondamentaux du commerce et du marketing.',
                    'Développer des compétences en négociation et en gestion de la relation client.',
                    'Apprendre à analyser le marché, identifier les opportunités et anticiper les tendances.',
                    'Concevoir et mettre en place des stratégies de vente et de développement commercial.',
                ]),
                'skills' => $this->liste([
                    'Techniques de vente et négociation commerciale.',
                    'Analyse de marché et veille concurrentielle.',
                    'Marketing stratégique et digital.',
                    'Gestion de projet commercial et développement de nouvelles offres.',
                    'Management et animation d’une équipe commerciale.',
                    'Conception et mise en œuvre d’une stratégie commerciale.',
                ]),
                'outcomes' => $this->liste([
                    'Business Developer',
                    'Responsable commercial',
                    'Responsable marketing et communication',
                    'Attaché commercial',
                    'Chef de projet commercial',
                    'Responsable de la relation client',
                    'Consultant en stratégie commerciale',
                    'Responsable e-commerce',
                ]),
                'audience' => 'Cette filière s’adresse aux profils souhaitant se spécialiser dans la stratégie commerciale et la relation client.',
                'learning_blocks' => $this->atouts([
                    ['Un secteur dynamique', 'Forte demande pour des professionnels en gestion commerciale.'],
                    ['Polyvalence des compétences', 'Applicable dans plusieurs domaines : B2B, B2C, digital, retail.'],
                    ['Adaptabilité aux outils numériques', 'Maîtrise du digital et des nouvelles tendances du commerce.'],
                ]),
            ],

            [
                'slug' => 'informatique-de-gestion',
                'title' => 'Informatique de Gestion',
                'pole' => 'pole-technologie',
                'excerpt' => 'Mettre l’informatique au service du pilotage et des systèmes d’information de l’entreprise.',
                'description' => 'La filière Informatique de Gestion est une formation conçue pour répondre aux besoins d’un monde numérique en constante évolution. Elle vous prépare à relever les défis techniques les plus complexes et à jouer un rôle clé dans le développement de solutions technologiques innovantes.',
                'objectives' => $this->liste([
                    'Concevoir, développer et déployer des solutions logicielles performantes.',
                    'Maîtriser l’architecture et l’administration des systèmes et réseaux.',
                    'Intégrer les nouvelles technologies (Big Data, IA) dans des environnements réseau.',
                    'Maîtriser les fondamentaux de l’informatique et des réseaux : programmation, architecture système et infrastructure réseau.',
                    'Développer des applications logicielles performantes et sécurisées.',
                ]),
                'skills' => $this->liste([
                    'Programmation avancée (Python, Java, C++) et développement d’applications web et mobiles.',
                    'Conception et gestion d’architectures réseau (LAN, WAN, WLAN, 5G).',
                    'Administration de systèmes (Windows, Linux) et gestion des bases de données (SQL, NoSQL).',
                    'Analyse et résolution de problèmes informatiques complexes.',
                    'Mise en œuvre de solutions cloud et virtualisation (VMware, Azure, AWS).',
                    'Sécurisation des systèmes et réseaux (firewalls, IDS/IPS, audits de sécurité).',
                ]),
                'outcomes' => $this->liste([
                    'Administrateur Réseau et Systèmes',
                    'Ingénieur Télécommunication',
                    'Architecte Réseau',
                    'Responsable Cybersécurité',
                    'Consultant en Solutions de Connectivité',
                    'Expert en Sécurité des Réseaux',
                    'Technicien Réseau et Support Technique',
                    'Chef de Projet en Réseaux et Télécommunications',
                    'Ingénieur Infrastructure IT',
                    'Intégrateur Télécom et Réseaux',
                    'Analyste Réseau',
                    'Responsable d’Exploitation Réseau',
                    'Responsable Data Center',
                ]),
                'audience' => 'Cette filière s’adresse aux passionnés de technologie, d’innovation logicielle et d’infrastructures réseau, qui souhaitent jouer un rôle clé dans la transformation numérique des entreprises et des organisations.',
                'learning_blocks' => $this->atouts([
                    ['Apprentissage pratique', 'Laboratoires équipés de technologies de pointe (Cisco, Huawei, etc.).'],
                    ['Projets concrets', 'Études de cas et simulations basées sur des problématiques réelles.'],
                    ['Collaboration internationale', 'Collaboration avec des leaders du secteur pour des stages et des opportunités de carrière.'],
                ]),
            ],

            [
                'slug' => 'intelligence-artificielle',
                'title' => 'Intelligence Artificielle',
                'pole' => 'pole-technologie',
                'excerpt' => 'Concevoir et déployer des solutions d’intelligence artificielle, du modèle à la mise en production.',
                'description' => 'La filière Intelligence Artificielle combine programmation, mathématiques appliquées et apprentissage automatique. Vous y développerez les compétences nécessaires pour concevoir et déployer des solutions d’IA innovantes.',
                'objectives' => $this->liste([
                    'Acquérir les fondamentaux de l’intelligence artificielle : machine learning, deep learning et traitement du langage naturel.',
                    'Maîtriser les langages et outils de programmation (Python, TensorFlow, PyTorch, scikit-learn).',
                    'Concevoir et entraîner des modèles d’IA pour résoudre des problématiques complexes.',
                    'Comprendre les enjeux éthiques et réglementaires de l’IA.',
                    'Appliquer l’IA à divers secteurs : finance, santé, industrie, marketing.',
                ]),
                'skills' => $this->liste([
                    'Maîtrise des langages et bibliothèques spécialisés (Python, R, TensorFlow, PyTorch).',
                    'Conception et entraînement d’algorithmes de machine learning et deep learning.',
                    'Gestion des big data, prétraitement et visualisation des données avec Pandas, NumPy, Matplotlib.',
                    'Développement de modèles pour la reconnaissance vocale, la traduction automatique et la compréhension du texte.',
                    'Conception de systèmes de reconnaissance d’images et de détection d’objets.',
                    'Compréhension des défis liés à l’éthique, à la confidentialité et à la réglementation de l’intelligence artificielle.',
                ]),
                'outcomes' => $this->liste([
                    'Ingénieur en intelligence artificielle',
                    'Data scientist',
                    'Ingénieur en machine learning',
                    'Développeur IA',
                    'Spécialiste en traitement du langage naturel (NLP)',
                    'Chercheur en IA',
                    'Consultant en transformation numérique et IA',
                ]),
                'audience' => 'Cette filière s’adresse à toute personne souhaitant se spécialiser dans l’intelligence artificielle, quel que soit son niveau d’expérience.',
                'learning_blocks' => $this->atouts([
                    ['Un secteur en forte croissance', 'L’intelligence artificielle est au cœur des innovations technologiques et de la transformation numérique des entreprises.'],
                    ['Compétences recherchées', 'Les experts en IA sont parmi les profils les plus demandés sur le marché du travail.'],
                    ['Polyvalence et adaptabilité', 'Les connaissances en IA s’appliquent à divers domaines : santé, finance, industrie, éducation, cybersécurité.'],
                ]),
            ],

            // ---------------------------------------------------------------
            // Cycle Master
            // ---------------------------------------------------------------

            [
                'slug' => 'administration-des-entreprises',
                'title' => 'Administration des Entreprises',
                'pole' => 'pole-gestion',
                'cycle' => 'cycle-master',
                'excerpt' => 'Piloter la stratégie, la gestion et le développement des organisations.',
                'description' => 'Le Master en Administration des Entreprises forme des cadres capables de piloter la stratégie, la gestion et le développement des organisations.',
                'objectives' => $this->liste([
                    'Concevoir les structures organisationnelles des entreprises pour un meilleur fonctionnement.',
                    'Concevoir et piloter des stratégies marketing et commerciales.',
                    'Concevoir les outils modernes de gestion des entreprises : reporting, management des risques, outils d’analyse.',
                    'Développer des compétences en management, leadership et gestion des ressources humaines.',
                    'Maîtriser les outils de gestion financière, comptable et fiscale.',
                ]),
                'skills' => $this->liste([
                    'Gestion stratégique et pilotage de la performance.',
                    'Conception et mise en œuvre des stratégies marketing et commerciales.',
                    'Veille économique et analyse des marchés.',
                    'Analyse financière, contrôle de gestion et gestion des risques.',
                    'Gestion des ressources humaines et du développement des talents.',
                ]),
                'outcomes' => $this->liste([
                    'Directeur administratif et financier',
                    'Consultant en management',
                    'Responsable marketing et communication',
                    'Directeur des opérations',
                    'Gestionnaire de portefeuille clients',
                    'Analyste en intelligence économique',
                ]),
                'audience' => 'Cette formation s’adresse aux profils souhaitant occuper des fonctions de management et de direction.',
                'learning_blocks' => $this->atouts([
                    ['Une formation polyvalente et transversale', 'Adaptée à tous les secteurs.'],
                    ['Projets innovants', 'Réalisation de projets réels en partenariat avec des entreprises.'],
                    ['Insertion professionnelle', 'Stages intégrés et réseau de partenaires pour faciliter votre insertion.'],
                ]),
            ],

            [
                'slug' => 'ingenierie-commerciale',
                'title' => 'Ingénierie Commerciale',
                'pole' => 'pole-gestion',
                'cycle' => 'cycle-master',
                'excerpt' => 'Devenir expert en stratégie commerciale, en négociation et en développement d’affaires.',
                'description' => 'Le Master en Ingénierie Commerciale prépare à des postes de haute responsabilité dans la gestion des ventes complexes, le marketing stratégique et la relation client en B2B et B2C.',
                'objectives' => $this->liste([
                    'Élaborer les politiques commerciales des entreprises.',
                    'Concevoir des produits et services à commercialiser.',
                    'Élaborer les stratégies de vente.',
                    'Analyser, gérer et structurer le marché ainsi que le service après-vente.',
                    'Former et gérer les cadres commerciaux.',
                    'Établir les budgets d’investissement et d’exploitation.',
                ]),
                'skills' => $this->liste([
                    'Conception et mise en œuvre de stratégies commerciales et marketing.',
                    'Analyse des marchés et veille concurrentielle.',
                    'Optimisation des performances commerciales et gestion des indicateurs clés.',
                    'Maîtrise des stratégies de pricing et des modèles économiques.',
                    'Gestion des relations clients et fidélisation.',
                ]),
                'outcomes' => $this->liste([
                    'Ingénieur commercial',
                    'Responsable marketing et développement',
                    'Consultant en stratégie commerciale',
                    'Responsable de la relation client',
                    'Directeur des ventes',
                ]),
                'audience' => 'Cette formation s’adresse aux profils souhaitant évoluer dans la gestion commerciale et le développement des affaires.',
                'learning_blocks' => $this->atouts([
                    ['Des opportunités dans tous les secteurs', 'Industrie, services, finance, technologie.'],
                    ['Projets innovants', 'Réalisation de projets réels en partenariat avec des entreprises.'],
                    ['Insertion professionnelle', 'Stages intégrés et réseau de partenaires pour faciliter votre insertion.'],
                ]),
            ],

            [
                'slug' => 'ingenierie-des-reseaux-de-telecommunications',
                'title' => 'Ingénierie des Réseaux de Télécommunications',
                'pole' => 'pole-technologie',
                'cycle' => 'cycle-master',
                'excerpt' => 'Concevoir, déployer et gérer les infrastructures de télécommunication.',
                'description' => 'Le Master en Ingénierie des Réseaux de Télécommunications forme des experts capables de concevoir, déployer et optimiser des infrastructures de communication modernes. Alliant enseignements théoriques approfondis, travaux pratiques et projets collaboratifs, ce cursus vous prépare à relever les défis de la transformation digitale et à répondre aux exigences des réseaux de nouvelle génération.',
                'objectives' => $this->liste([
                    'Acquérir une expertise avancée en conception et gestion des réseaux de communication.',
                    'Maîtriser les technologies de transmission, fibre optique, réseaux sans fil et 5G.',
                    'Développer des compétences en sécurité des réseaux et cybersécurité.',
                    'Analyser et optimiser les performances des infrastructures télécoms.',
                    'Appliquer les normes internationales de télécommunications.',
                ]),
                'skills' => $this->liste([
                    'Conception et gestion des réseaux LAN, WAN et WLAN.',
                    'Installation et maintenance d’infrastructures télécoms.',
                    'Utilisation d’outils de diagnostic et de monitoring.',
                    'Mise en œuvre de solutions de sécurité et de cybersécurité.',
                    'Analyse de la performance et optimisation des réseaux.',
                ]),
                'outcomes' => $this->liste([
                    'Ingénieur Réseaux et Télécoms',
                    'Consultant en infrastructures de télécommunication',
                    'Responsable de la sécurité des réseaux',
                    'Administrateur de systèmes et réseaux',
                    'Chef de projet en télécommunications',
                ]),
                'audience' => 'Cette formation s’adresse aux diplômés et professionnels passionnés par l’innovation technologique, désireux de se spécialiser dans la conception et la gestion des infrastructures de communication.',
                'learning_blocks' => $this->atouts([
                    ['Enseignement pratique', 'Laboratoires équipés et ateliers pratiques pour une immersion totale dans les technologies de communication.'],
                    ['Projets collaboratifs', 'Réalisation de projets réels et partenariats avec des entreprises pour résoudre des problématiques concrètes.'],
                    ['Insertion professionnelle', 'Stages intégrés et réseau de partenaires pour faciliter votre intégration dans le secteur.'],
                ]),
            ],

            [
                'slug' => 'systemes-d-information-et-reseaux',
                'title' => 'Systèmes d’Information et Réseaux',
                'pole' => 'pole-technologie',
                'cycle' => 'cycle-master',
                'excerpt' => 'Concevoir, déployer et gérer des infrastructures informatiques complexes.',
                'description' => 'Le Master en Systèmes d’Information et Réseaux forme des experts capables de concevoir, déployer et gérer des infrastructures informatiques complexes. Alliant une base théorique solide à des applications pratiques via des études de cas, des projets collaboratifs et des stages en entreprise, ce cursus vous prépare à piloter des projets IT stratégiques et à accompagner la transformation digitale des organisations.',
                'objectives' => $this->liste([
                    'Construire les systèmes d’information des entreprises, organisations et opérateurs.',
                    'Interconnecter les entreprises au moyen des technologies réseaux et télécommunications.',
                    'Développer des compétences en cybersécurité et en analyse de données.',
                    'Optimiser la performance et la sécurité des infrastructures IT.',
                    'Implémenter des stratégies innovantes pour la transformation digitale.',
                ]),
                'skills' => $this->liste([
                    'Conception et déploiement d’architectures IT.',
                    'Développement et gestion des bases de données (SQL, NoSQL).',
                    'Analyse de performances et optimisation des réseaux.',
                    'Cybersécurité et protection des données.',
                    'Gestion de projets IT et transformation digitale.',
                    'Pilotage de projets IT et innovation technologique.',
                ]),
                'outcomes' => $this->liste([
                    'Architecte réseau et système',
                    'Administrateur systèmes et réseaux',
                    'Ingénieur cybersécurité',
                    'Expert en data center',
                    'Ingénieur cloud computing',
                ]),
                'audience' => 'Cette formation s’adresse aux diplômés et professionnels souhaitant se spécialiser en systèmes d’information et réseaux afin d’accompagner la transformation digitale des organisations.',
                'learning_blocks' => $this->atouts([
                    ['Approche pratique', 'Laboratoires équipés, ateliers pratiques et projets collaboratifs pour une immersion totale dans le monde IT.'],
                    ['Projets innovants', 'Réalisation de projets réels en partenariat avec des entreprises du secteur.'],
                    ['Insertion professionnelle', 'Stages intégrés et réseau de partenaires pour faciliter votre entrée dans le monde de l’informatique.'],
                ]),
            ],

            [
                'slug' => 'management-des-ressources-humaines',
                'title' => 'Management des Ressources Humaines',
                'pole' => 'pole-gestion',
                'cycle' => 'cycle-master',
                'excerpt' => 'Concevoir et mettre en œuvre des stratégies innovantes en gestion du capital humain.',
                'description' => 'Le Master en Management des Ressources Humaines forme des professionnels capables de concevoir et de mettre en œuvre des stratégies innovantes en gestion du capital humain. Ce programme allie une base théorique solide à des applications pratiques à travers des études de cas, des projets collaboratifs et des stages en entreprise. Vous serez préparé à occuper des postes stratégiques et à transformer la gestion des ressources humaines dans un environnement dynamique et compétitif.',
                'objectives' => $this->liste([
                    'Développer une expertise avancée en gestion du capital humain.',
                    'Maîtriser les stratégies de recrutement, de formation et de fidélisation.',
                    'Analyser et anticiper les évolutions du marché du travail.',
                    'Optimiser la performance des équipes par une gestion stratégique.',
                    'Appliquer des pratiques innovantes pour améliorer la gouvernance RH.',
                ]),
                'skills' => $this->liste([
                    'Gestion des talents et développement des compétences.',
                    'Stratégie de recrutement et intégration.',
                    'Évaluation et amélioration de la performance.',
                    'Leadership et management d’équipes.',
                    'Gestion des conflits et négociation.',
                    'Planification stratégique des ressources humaines.',
                ]),
                'outcomes' => $this->liste([
                    'Responsable des Ressources Humaines',
                    'Directeur RH',
                    'Consultant en management des ressources humaines',
                    'Chargé de recrutement et formation',
                    'Manager en développement des talents',
                ]),
                'audience' => 'Cette formation s’adresse aux diplômés et professionnels souhaitant se spécialiser en gestion des ressources humaines afin d’occuper des postes stratégiques et de transformer la gestion du capital humain dans les organisations.',
                'learning_blocks' => $this->atouts([
                    ['Approche pratique', 'Ateliers interactifs, études de cas et simulations pour une immersion complète dans la gestion RH.'],
                    ['Projets collaboratifs', 'Travaux de groupe et projets de consulting pour mettre en pratique les stratégies RH.'],
                    ['Insertion professionnelle', 'Stages intégrés et partenariats avec des entreprises pour faciliter votre insertion sur le marché du travail.'],
                ]),
            ],

            [
                'slug' => 'ingenierie-financiere',
                'title' => 'Ingénierie Financière',
                'pole' => 'pole-gestion',
                'cycle' => 'cycle-master',
                'excerpt' => 'Gestion des risques, finance de marché, conseil en investissement et structuration financière.',
                'description' => 'Le Master en Ingénierie Financière prépare les étudiants à évoluer dans les domaines de la gestion des risques, de la finance de marché, du conseil en investissement et de la structuration financière.',
                'objectives' => $this->liste([
                    'Maîtriser les techniques avancées d’analyse financière et d’évaluation des actifs.',
                    'Optimiser la gestion des risques et la rentabilité des investissements.',
                    'Développer des stratégies de financement et de levée de fonds pour les entreprises.',
                    'Utiliser les outils numériques et les algorithmes pour la modélisation financière.',
                    'Comprendre les réglementations financières et les normes internationales.',
                ]),
                'skills' => $this->liste([
                    'Analyse financière et évaluation des entreprises.',
                    'Stratégies de financement et d’investissement.',
                    'Optimisation des portefeuilles et gestion d’actifs.',
                    'Ingénierie fiscale et structuration financière avancée.',
                    'Utilisation des outils quantitatifs et logiciels financiers (Python, MATLAB, Bloomberg, Excel VBA).',
                ]),
                'outcomes' => $this->liste([
                    'Analyste financier',
                    'Spécialiste en finance quantitative',
                    'Directeur financier',
                    'Responsable des risques financiers',
                    'Analyste en fusions et acquisitions',
                ]),
                'audience' => 'Ce master s’adresse aux diplômés et professionnels ambitieux désireux d’approfondir leurs compétences en finance pour occuper des postes stratégiques au sein des organisations.',
                'learning_blocks' => $this->atouts([
                    ['Enseignement pratique', 'Ateliers, simulations et études de cas réels pour ancrer vos connaissances dans la pratique.'],
                    ['Projets collaboratifs', 'Travaux de groupe et projets de consulting en partenariat avec des entreprises du secteur.'],
                    ['Insertion professionnelle', 'Stages intégrés et partenariats avec des acteurs majeurs du secteur financier.'],
                ]),
            ],

            // ---------------------------------------------------------------
            // CAP
            // ---------------------------------------------------------------

            [
                'slug' => 'electricite',
                'title' => 'Électricité',
                'pole' => 'pole-technologie',
                'cycle' => 'cap',
                'excerpt' => 'Installer, entretenir et dépanner des systèmes électriques dans le respect des normes.',
                'description' => 'La formation CAP en Électricité offre une immersion pratique et technique dans le domaine de l’électricité. Vous apprendrez à installer, entretenir et dépanner divers systèmes électriques tout en respectant les normes de sécurité. Ce cursus, conçu pour répondre aux besoins du secteur industriel, combine théorie et ateliers pratiques pour former des techniciens compétents et opérationnels.',
                'objectives' => $this->liste([
                    'Comprendre les principes fondamentaux de l’électricité et de l’électronique.',
                    'Installer et entretenir des systèmes électriques diversifiés.',
                    'Diagnostiquer et résoudre les pannes en respectant les normes de sécurité.',
                    'Utiliser les outils et instruments de mesure avec précision.',
                    'Acquérir une expérience concrète grâce à des ateliers et stages en entreprise.',
                ]),
                'skills' => $this->liste([
                    'Lecture et interprétation de schémas électriques.',
                    'Techniques d’installation et de câblage de circuits.',
                    'Utilisation des instruments de mesure et de diagnostic.',
                    'Maintenance préventive et corrective des installations électriques.',
                    'Respect et application des normes de sécurité.',
                ]),
                'outcomes' => $this->liste([
                    'Technicien électricien',
                    'Installateur de systèmes électriques',
                    'Agent de maintenance en électricité',
                    'Spécialiste en dépannage électrique',
                    'Technicien en énergies renouvelables',
                ]),
                'audience' => 'Cette formation s’adresse aux jeunes motivés par les métiers techniques, désireux d’acquérir des compétences pratiques et d’intégrer rapidement le secteur industriel de l’électricité.',
                'learning_blocks' => $this->atouts([
                    ['Formation pratique', 'Des ateliers équipés et des stages en entreprise pour une immersion totale.'],
                    ['Projets concrets', 'Des projets appliqués pour valider et renforcer vos compétences techniques.'],
                    ['Insertion professionnelle', 'Un réseau d’entreprises partenaires pour faciliter votre entrée sur le marché du travail.'],
                ]),
            ],

            [
                'slug' => 'reseaux-informatique',
                'title' => 'Réseaux Informatique',
                'pole' => 'pole-technologie',
                'cycle' => 'cap',
                'excerpt' => 'Installer, configurer, dépanner et maintenir des infrastructures réseaux et systèmes.',
                'description' => 'La formation CAP en Maintenance des Réseaux Informatique forme des techniciens capables d’installer, configurer, dépanner et maintenir des infrastructures réseaux et systèmes informatiques.',
                'objectives' => $this->liste([
                    'Assurer la maintenance des postes de travail et des périphériques.',
                    'Installer et configurer des réseaux câblés et sans fil.',
                    'Diagnostiquer et résoudre les problèmes de connectivité.',
                    'Appliquer les normes de sécurité pour protéger les données.',
                    'Mettre en place des solutions de monitoring et de gestion de réseaux.',
                ]),
                'skills' => $this->liste([
                    'Configuration de routeurs et de commutateurs.',
                    'Installation et maintenance des réseaux LAN/WLAN.',
                    'Gestion des adresses IP et des protocoles (TCP/IP, DNS, DHCP).',
                    'Utilisation d’outils de diagnostic et de monitoring.',
                    'Application des standards de sécurité réseau.',
                ]),
                'outcomes' => $this->liste([
                    'Technicien de maintenance informatique',
                    'Installateur de réseaux LAN/WLAN',
                    'Support technique et maintenance réseau',
                    'Spécialiste en sécurité des infrastructures',
                    'Technicien de déploiement IT',
                ]),
                'audience' => 'Cette formation s’adresse aux passionnés d’informatique et aux curieux des infrastructures réseau, désireux d’acquérir des compétences techniques concrètes pour contribuer à la transformation numérique des entreprises.',
                'learning_blocks' => $this->atouts([
                    ['Formation pratique', 'Des ateliers équipés et des stages en entreprise pour une immersion totale.'],
                    ['Projets concrets', 'Réalisation de projets de déploiement et de sécurisation de réseaux.'],
                    ['Insertion professionnelle', 'Partenariats avec des entreprises du secteur IT pour faciliter votre intégration.'],
                ]),
            ],

            [
                'slug' => 'marketing-et-vente',
                'title' => 'Marketing et Vente',
                'pole' => 'pole-gestion',
                'cycle' => 'cap',
                'excerpt' => 'Assurer la promotion et la commercialisation de produits et de services.',
                'description' => 'Cette formation CAP en Marketing et Vente vous permet d’acquérir des compétences essentielles en techniques de vente, relation client et stratégies marketing, répondant ainsi aux besoins des entreprises et commerces.',
                'objectives' => $this->liste([
                    'Maîtriser les techniques de vente et de négociation commerciale.',
                    'Comprendre les bases du marketing et du comportement des consommateurs.',
                    'Apprendre à analyser les tendances du marché et la concurrence.',
                    'Utiliser les outils numériques pour la communication et la promotion des produits.',
                    'Développer des stratégies de fidélisation et d’attraction des clients.',
                ]),
                'skills' => $this->liste([
                    'Techniques de vente et argumentation commerciale.',
                    'Gestion des stocks et des rayons en point de vente.',
                    'Utilisation des outils de communication et de marketing digital.',
                    'Maîtrise des bases de la publicité et des promotions.',
                    'Gestion des réclamations et fidélisation client.',
                    'Compréhension des indicateurs de performance commerciale.',
                    'Réalisation d’études de marché et analyse des tendances.',
                ]),
                'outcomes' => $this->liste([
                    'Assistant commercial',
                    'Technico-commercial',
                    'Entrepreneur en commerce et marketing',
                    'Vendeur en grande distribution',
                    'Agent de service client',
                    'Gestionnaire de point de vente',
                    'Assistant marketing',
                ]),
                'audience' => 'Ce CAP s’adresse à toute personne souhaitant se former aux métiers du commerce et de la vente.',
                'learning_blocks' => $this->atouts([
                    ['Formation pratique', 'Ateliers interactifs, études de cas et simulations pour vous confronter à des situations réelles.'],
                    ['Projets concrets', 'Réalisation de projets collaboratifs afin de mettre en pratique les techniques de vente et du marketing.'],
                    ['Insertion professionnelle', 'Partenariats avec des entreprises et stages intégrés pour faciliter votre entrée sur le marché du travail.'],
                ]),
            ],

            [
                'slug' => 'maintenance-industrielle',
                'title' => 'Maintenance Industrielle',
                'pole' => 'pole-technologie',
                'cycle' => 'cap',
                'excerpt' => 'Assurer l’entretien, la réparation et l’optimisation des équipements industriels.',
                'description' => 'La formation CAP en Maintenance Industrielle permet d’acquérir des compétences essentielles en mécanique, électricité, automatisme et diagnostic des pannes afin d’assurer le bon fonctionnement des machines industrielles.',
                'objectives' => $this->liste([
                    'Assurer la maintenance préventive et corrective des équipements industriels.',
                    'Diagnostiquer les pannes et proposer des solutions adaptées.',
                    'Maîtriser les principes de fonctionnement des systèmes mécaniques, électriques et hydrauliques.',
                    'Optimiser le fonctionnement des machines pour améliorer la productivité.',
                    'Utiliser les outils et logiciels de maintenance assistée par ordinateur (GMAO).',
                ]),
                'skills' => $this->liste([
                    'Lecture et interprétation des plans et schémas techniques.',
                    'Montage et démontage de composants industriels.',
                    'Programmation et réglage de machines automatisées.',
                    'Intervention sur des circuits pneumatiques et hydrauliques.',
                    'Optimisation des performances des équipements industriels.',
                ]),
                'outcomes' => $this->liste([
                    'Technicien de maintenance industrielle',
                    'Agent de maintenance en production',
                    'Électromécanicien',
                    'Technicien en maintenance ferroviaire',
                    'Opérateur en maintenance des systèmes énergétiques',
                    'Spécialiste en maintenance préventive',
                    'Mécanicien industriel',
                ]),
                'audience' => 'Ce CAP est destiné à toute personne souhaitant acquérir des compétences en maintenance et réparation d’équipements industriels.',
                'learning_blocks' => $this->atouts([
                    ['Formation pratique', 'Ateliers de formation technique alliant pratique et théorie.'],
                    ['Un secteur qui recrute', 'Un secteur clé avec une forte demande en main-d’œuvre qualifiée.'],
                    ['Insertion professionnelle', 'Partenariats avec des entreprises pour faciliter votre insertion professionnelle.'],
                ]),
            ],

            [
                'slug' => 'froid-et-climatisation',
                'title' => 'Froid et Climatisation',
                'pole' => 'pole-technologie',
                'cycle' => 'cap',
                'excerpt' => 'Installer, entretenir et réparer les équipements frigorifiques et de climatisation.',
                'description' => 'La formation CAP en Froid et Climatisation permet d’acquérir des compétences techniques indispensables pour évoluer dans un secteur en forte demande.',
                'objectives' => $this->liste([
                    'Installer, entretenir et réparer les équipements frigorifiques et de climatisation.',
                    'Comprendre les principes thermodynamiques et le fonctionnement des circuits frigorifiques.',
                    'Manipuler les fluides frigorigènes en respectant les normes environnementales et de sécurité.',
                    'Diagnostiquer les pannes et optimiser les performances énergétiques des installations.',
                    'Lire et interpréter les plans et schémas des systèmes frigorifiques.',
                ]),
                'skills' => $this->liste([
                    'Installation et mise en service de systèmes frigorifiques et de climatisation.',
                    'Entretien et maintenance des équipements thermiques et frigorifiques.',
                    'Manipulation des fluides frigorigènes en conformité avec la réglementation.',
                    'Respect des normes de sécurité et des réglementations environnementales.',
                    'Diagnostic et réparation des pannes.',
                ]),
                'outcomes' => $this->liste([
                    'Technicien en climatisation',
                    'Installateur en froid industriel',
                    'Technicien en ventilation et traitement de l’air',
                    'Monteur en installations thermiques et frigorifiques',
                    'Technicien dans le secteur agroalimentaire',
                ]),
                'audience' => 'Cette formation s’adresse à toute personne souhaitant se professionnaliser dans le domaine du froid et de la climatisation.',
                'learning_blocks' => $this->atouts([
                    ['Formation pratique', 'Des ateliers modernes et des laboratoires équipés pour des sessions pratiques intensives.'],
                    ['Projets réels', 'Réalisation de projets concrets en collaboration avec des professionnels du secteur.'],
                    ['Insertion professionnelle', 'Partenariats avec des entreprises leaders pour faciliter stages et embauches.'],
                ]),
            ],
        ];
    }
}
