<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\PageHeader;

/**
 * Champs réellement utilisés par chaque bloc de texte.
 *
 * La table `page_headers` est partagée par des sections très différentes : un
 * bandeau de page, un compteur de chiffres, un mot du directeur, une carte à
 * pictogramme. Afficher les douze colonnes partout obligeait le rédacteur à
 * deviner lesquelles ont un effet.
 *
 * Ce schéma décrit, bloc par bloc, les seuls champs que sa vue lit — avec les
 * intitulés de ce bloc-là. Le formulaire ne rend, ne valide et n'enregistre
 * que ceux-ci.
 */
final class PageHeaderSchema
{
    /** Intitulés par défaut, repris quand un bloc ne les redéfinit pas. */
    private const DEFAUTS = [
        'label' => ['Libellé', 'Le nom du bloc dans la liste du back-office.'],
        'eyebrow' => ['Surtitre', 'Petite mention au-dessus du titre.'],
        'title' => ['Titre', null],
        'title_accent' => ['Titre — seconde ligne', 'Affichée en couleur d’accent. Vide : titre sur une seule ligne.'],
        'title_variants' => ['Variantes du titre', 'Une par ligne. La seconde ligne défile entre ces variantes. Vide ou une seule ligne : titre immobile.'],
        'intro' => ['Accroche', 'Paragraphe court sous le titre.'],
        'body' => ['Texte long', 'Laissez une ligne vide entre deux paragraphes.'],
        'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
        'cta_url' => ['Lien du bouton', 'Par exemple /contact.'],
        'tags' => ['Étiquettes', 'Une par ligne.'],
        'icon' => ['Pictogramme', null],
        'image' => ['Visuel de fond', 'JPG, PNG, WebP ou AVIF — 4 Mo maximum.'],
        'image_secondary' => ['Seconde photo', 'Superposée à la première. Facultative.'],
    ];

    /**
     * Types de saisie, pour que la vue choisisse le bon contrôle.
     */
    private const TYPES = [
        'label' => 'text',
        'eyebrow' => 'text',
        'title' => 'text',
        'title_accent' => 'text',
        'title_variants' => 'lignes',
        'intro' => 'zone',
        'body' => 'zone-longue',
        'cta_label' => 'text',
        'cta_url' => 'text',
        'tags' => 'lignes',
        'icon' => 'icone',
        'image' => 'fichier',
        'image_secondary' => 'fichier',
    ];

    /**
     * Bandeau d'une page interne : le jeu complet.
     *
     * @var array<int, string>
     */
    private const BANDEAU_DE_PAGE = [
        'label', 'eyebrow', 'title', 'title_accent', 'intro', 'cta_label', 'cta_url', 'tags', 'image',
    ];

    /**
     * Champs par bloc, dans l'ordre d'affichage. Chaque entrée est soit un nom
     * de champ, soit un couple nom => [intitulé, aide] quand le bloc donne au
     * champ un sens particulier.
     *
     * @var array<string, array<int|string, mixed>>
     */
    private const BLOCS = [
        'home.hero' => [
            'eyebrow' => ['Surtitre', 'La ligne en italique au-dessus du titre.'],
            'title' => ['Titre — première ligne', 'Reste fixe pendant le défilement.'],
            'title_accent' => ['Titre — seconde ligne', 'Affichée quand aucune variante n’est saisie.'],
            'title_variants',
            'cta_label' => ['Libellé du premier bouton', 'Le second bouton, « Prendre une préinscription », est fixe.'],
            'label',
        ],
        'home.about' => [
            'eyebrow' => ['Surtitre', 'Affiché dans la pastille, au-dessus du titre.'],
            'icon' => ['Pictogramme du surtitre', 'Petite icône dans la pastille.'],
            'title' => ['Titre', null],
            'intro' => ['Accroche', 'Le paragraphe sous le titre.'],
            'body' => ['Citation', 'Mise en avant dans un encadré à filet. Une seule phrase suffit.'],
            'tags' => ['Mention sur la photo', 'La phrase de la carte posée sur l’image. Une par ligne, la première est reprise.'],
            'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
            'cta_url' => ['Lien du bouton', 'Par exemple /l-ecole.'],
            'image' => ['Photo', 'Le grand visuel à gauche.'],
            'label',
        ],
        'home.services' => [
            'eyebrow' => ['Surtitre', 'La mention au-dessus des cycles.'],
            'intro' => ['Mention soulignée', 'Le court texte souligné, à droite du surtitre.'],
            'tags' => ['Compteur', 'Une seule ligne, au format « valeur | libellé ». Exemple : 9 | Filières. Vide : pas de compteur.'],
            'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
            'cta_url' => ['Lien du bouton', 'Par exemple /contact.'],
            'label',
        ],
        'home.portfolio' => [
            'eyebrow' => ['Surtitre', 'La mention en majuscules au-dessus du titre.'],
            'icon' => ['Pictogramme du surtitre', 'Petite icône devant la mention.'],
            'title' => ['Titre — début', 'Reste en noir.'],
            'title_accent' => ['Titre — suite en couleur', 'La fin du titre, reprise en bleu.'],
            'intro' => ['Accroche', 'La phrase en gras sous le titre.'],
            'body' => ['Paragraphe', 'Le texte détaillé, présenté avec un filet à gauche.'],
            'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
            'cta_url' => ['Lien du bouton', 'Par exemple /filieres.'],
            'image' => ['Photo', 'Le grand visuel à droite, cadré en carré.'],
            'tags' => ['Médaillon tournant', 'Le texte qui tourne autour de la pastille. Une seule ligne ; vide, la pastille disparaît.'],
            'label',
        ],
        'home.marquee' => [
            'tags' => ['Chiffres', 'Un par ligne, au format « valeur | libellé ». Exemple : 4 | Cycles, du CAP au Master.'],
            'label',
        ],
        'home.testimonials' => [
            'eyebrow' => ['Surtitre', 'La mention au-dessus du titre.'],
            'title',
            'intro' => ['Accroche', 'Le paragraphe sous le titre.'],
            'icon' => ['Pictogramme du surtitre', 'Petite icône devant la mention.'],
            'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
            'cta_url' => ['Lien du bouton', 'Par exemple /contact.'],
            'image' => ['Photo de gauche', null],
            'image_secondary' => ['Photo de droite', 'Plus haute que celle de gauche.'],
            'label',
        ],
        'home.showcase' => [
            'eyebrow' => ['Surtitre', 'Le titre blanc au-dessus des témoignages.'],
            'title' => ['Sous-titre', 'La phrase sous le surtitre.'],
            'label',
        ],
        'home.process' => ['eyebrow', 'title' => ['Texte de présentation', null], 'label'],
        'home.faq' => [
            'eyebrow' => ['Surtitre', 'La mention au-dessus du titre.'],
            'title' => ['Question d’introduction', null],
            'image' => ['Photo', 'Le visuel en haut de la colonne de gauche.'],
            'label',
        ],
        'home.banner' => ['title', 'title_accent', 'cta_label', 'label'],
        'home.journal' => ['eyebrow', 'title', 'cta_label', 'label'],
        'ecole.directeur' => [
            'title' => ['Titre', 'Par exemple « Le mot du directeur ».'],
            'intro' => ['Accroche', 'La phrase mise en avant, avant les paragraphes.'],
            'body' => ['Texte du mot', 'Laissez une ligne vide entre deux paragraphes.'],
            'image' => ['Photo de fond', 'La carte du texte se pose par-dessus. Facultative.'],
            'label',
        ],
        'equipe.approche' => [
            'title' => ['Titre de la section', null],
            'intro' => ['Accroche', null],
            'label',
        ],
    ];

    /**
     * Champs d'un bloc, résolus.
     *
     * @return array<int, array{name: string, label: string, hint: string|null, type: string}>
     */
    public static function for(PageHeader $header): array
    {
        return array_map(
            static fn (array $champ): array => $champ,
            self::resoudre(self::definition($header)),
        );
    }

    /**
     * Noms des champs d'un bloc, pour la validation et l'enregistrement.
     *
     * @return array<int, string>
     */
    public static function fieldsFor(PageHeader $header): array
    {
        return array_column(self::for($header), 'name');
    }

    /**
     * @return array<int|string, mixed>
     */
    private static function definition(PageHeader $header): array
    {
        if (isset(self::BLOCS[$header->key])) {
            return self::BLOCS[$header->key];
        }

        // Bande de repères chiffrés, sous la présentation.
        if ($header->key === 'ecole.reperes') {
            return [
                'title' => ['Titre', 'Lu par les lecteurs d’écran ; masqué à l’affichage.'],
                'tags' => ['Libellés des chiffres', 'Trois lignes, dans l’ordre : cycles, filières, pôles. Les nombres sont comptés automatiquement.'],
                'cta_label' => ['Texte de la pastille', 'Le bouton rond à droite. Vide : pas de pastille.'],
                'cta_url' => ['Lien de la pastille', 'Par exemple /contact.'],
                'image' => ['Grande photo', 'Le visuel principal de la bande.'],
                'image_secondary' => ['Petite photo', 'Décalée au-dessus de la grande. Facultative.'],
                'label',
            ];
        }

        // Argument de la section « à propos » : home.about.1
        if (str_starts_with($header->key, 'home.about.')) {
            return [
                'title' => ['Intitulé', null],
                'intro' => ['Texte', 'Une phrase.'],
                'icon',
                'label',
            ];
        }

        // Carte de contact, à gauche des questions fréquentes.
        if ($header->key === 'home.faq.contact') {
            return [
                'title' => ['Titre de la carte', null],
                'intro' => ['Texte sous le titre', 'Une ligne.'],
                'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
                'cta_url' => ['Lien du bouton', 'Par exemple /contact.'],
                'label',
            ];
        }

        // Questions fréquentes : home.faq.1, .2, ...
        if (str_starts_with($header->key, 'home.faq.')) {
            return [
                'title' => ['Question', 'Le libellé du bouton qui déplie la réponse.'],
                'body' => ['Réponse', 'Le texte affiché une fois la question dépliée.'],
                'label',
            ];
        }

        // Cartes des cycles, dans la section des formations : home.services.1 à .3
        if (str_starts_with($header->key, 'home.services.')) {
            return [
                'title' => ['Intitulé du cycle', 'Par exemple « Cycle Licence (BAC +3) ».'],
                'intro' => ['Texte de la carte', 'Une ou deux phrases.'],
                'cta_url' => ['Lien de la carte', 'Par exemple /filieres.'],
                'image' => ['Vignette', 'La petite photo qui apparaît au survol.'],
                'label',
            ];
        }

        // Cartes de « Pourquoi l'ESCAT » : home.testimonials.1, .2
        if (str_starts_with($header->key, 'home.testimonials.')) {
            return [
                'title' => ['Intitulé de la carte', null],
                'intro' => ['Texte de la carte', 'Une ou deux phrases.'],
                'icon',
                'label',
            ];
        }

        // Arguments de la première section de L'école.
        if (str_starts_with($header->key, 'ecole.presentation.')) {
            return [
                'title' => ['Intitulé de l’argument', null],
                'intro' => ['Texte de l’argument', 'Une ou deux phrases.'],
                'icon',
                'label',
            ];
        }

        // Bloc parent de cette même section.
        if ($header->key === 'ecole.presentation') {
            return [
                'eyebrow' => ['Surtitre', 'La petite mention au-dessus du titre.'],
                'title' => ['Titre', null],
                'intro' => ['Accroche', 'Le paragraphe sous le titre.'],
                'tags' => ['Points clés', 'Un par ligne, affichés en liste à puces.'],
                'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
                'cta_url' => ['Lien du bouton', 'Par exemple /filieres.'],
                'image' => ['Photo principale', 'Le grand visuel, à gauche de la section.'],
                'image_secondary' => ['Photo superposée', 'La petite photo par-dessus la grande. Facultative.'],
                'label',
            ];
        }

        // Cartes de la page Équipe : equipe.approche.1, .2, …
        if (str_starts_with($header->key, 'equipe.approche.')) {
            return [
                'title' => ['Intitulé de la carte', null],
                'intro' => ['Texte de la carte', null],
                'icon',
                'label',
            ];
        }

        // Étapes de la vision : ecole.vision.1, .2, .3
        if (str_starts_with($header->key, 'ecole.vision.')) {
            return [
                'eyebrow' => ['Étiquette', 'La mention au-dessus du titre, par exemple « Formation théorique ».'],
                'title' => ['Titre de l’étape', null],
                'intro' => ['Texte de l’étape', 'Une ou deux phrases.'],
                'icon',
                'label',
            ];
        }

        // La vision a sa propre section, avec deux photos rondes.
        if ($header->key === 'ecole.vision') {
            return [
                'eyebrow' => ['Surtitre', 'La petite mention colorée au-dessus du titre.'],
                'title' => ['Titre', null],
                'intro' => ['Texte de la vision', 'Le paragraphe présenté à côté du pictogramme.'],
                'icon' => ['Pictogramme', 'Affiché dans la carte, à gauche du texte.'],
                'tags' => ['Points clés', 'Un par ligne. Facultatif.'],
                'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
                'cta_url' => ['Lien du bouton', 'Par exemple /filieres.'],
                'image' => ['Première photo', 'La grande, en haut.'],
                'image_secondary' => ['Seconde photo', 'La ronde, décalée en dessous.'],
                'label',
            ];
        }

        // Carte illustrée des pôles, dans la section du parcours.
        if ($header->key === 'ecole.parcours.pole') {
            return [
                'eyebrow' => ['Pastille', 'La mention encadrée sur la photo, par exemple « Deux pôles ».'],
                'title' => ['Titre sur la photo', null],
                'intro' => ['Texte sur la photo', 'Une phrase ; au-delà, elle est tronquée.'],
                'cta_url' => ['Lien de la carte', 'Par exemple /filieres.'],
                'image' => ['Photo', 'Le visuel de la carte, à gauche de la liste.'],
                'label',
            ];
        }

        // Étapes du cursus : ecole.parcours.1 à .4
        if (str_starts_with($header->key, 'ecole.parcours.')) {
            return [
                'eyebrow' => ['Intitulé du cycle', 'La ligne au-dessus du titre, par exemple « BTS — BAC +2 ».'],
                'title' => ['Titre de l’étape', null],
                'intro' => ['Texte de l’étape', 'Une ou deux phrases.'],
                'cta_url' => ['Lien de la flèche', 'Par exemple /filieres.'],
                'label',
            ];
        }

        // En-tête de la section du parcours.
        if ($header->key === 'ecole.parcours') {
            return [
                'eyebrow' => ['Surtitre', 'La mention animée au-dessus du titre.'],
                'title' => ['Titre', null],
                'intro' => ['Accroche', 'Le paragraphe sous le titre.'],
                'cta_label' => ['Libellé du bouton', 'Vide : pas de bouton.'],
                'cta_url' => ['Lien du bouton', 'Par exemple /contact.'],
                'label',
            ];
        }

        // La mission a sa propre section, en deux moitiés avec une photo.
        if ($header->key === 'ecole.mission') {
            return [
                'title' => ['Titre', null],
                'intro' => ['Accroche', 'Le paragraphe sous le titre.'],
                'tags' => ['Missions', 'Une par ligne, affichées avec une coche.'],
                'image' => ['Photo', 'Occupe la moitié gauche de la section.'],
                'label',
            ];
        }

        // Vision, objectif, valeurs.
        if (str_starts_with($header->key, 'ecole.')) {
            return [
                'title' => ['Titre', 'Par exemple « Notre vision ».'],
                'intro' => ['Texte', 'Le paragraphe du bloc.'],
                'tags' => ['Puces', 'Une par ligne. Facultatif.'],
                'label',
            ];
        }

        return self::BANDEAU_DE_PAGE;
    }

    /**
     * @param  array<int|string, mixed>  $definition
     * @return array<int, array{name: string, label: string, hint: string|null, type: string}>
     */
    private static function resoudre(array $definition): array
    {
        $champs = [];

        foreach ($definition as $cle => $valeur) {
            // Entrée simple : la clé est numérique, la valeur est le nom du champ.
            $nom = is_int($cle) ? $valeur : $cle;
            [$intitule, $aide] = is_int($cle) ? self::DEFAUTS[$nom] : $valeur;

            $champs[] = [
                'name' => $nom,
                'label' => $intitule,
                'hint' => $aide,
                'type' => self::TYPES[$nom],
            ];
        }

        return $champs;
    }
}
