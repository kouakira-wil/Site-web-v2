<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Coordonnées de l'école
|--------------------------------------------------------------------------
|
| Le téléphone et l'adresse e-mail étaient recopiés dans une dizaine de
| gabarits : les corriger demandait de tous les retrouver. Ils sont désormais
| lus ici, et surchargeables par l'environnement.
|
| `telephone_lien` est la forme composable d'un `tel:` — sans espaces ni
| séparateurs, que les téléphones n'interprètent pas tous de la même façon.
|
*/

return [
    'ville' => env('ECOLE_VILLE', 'Brazzaville'),
    'pays' => env('ECOLE_PAYS', 'République du Congo'),

    'telephone' => env('ECOLE_TELEPHONE', '+242 06 884 83 44'),
    'telephone_lien' => env('ECOLE_TELEPHONE_LIEN', '+242068848344'),

    'email' => env('ECOLE_EMAIL', 'infos@escat.org'),

    /*
     * WhatsApp. `wa.me` attend le numéro international collé, sans le signe
     * plus ni séparateurs.
     */
    'whatsapp' => env('ECOLE_WHATSAPP', '242068848344'),

    'jours' => env('ECOLE_JOURS', 'Du lundi au samedi'),
    'horaires' => env('ECOLE_HORAIRES', '8h – 16h'),

    'delai_reponse' => env('ECOLE_DELAI_REPONSE', '48 heures ouvrées'),

    /*
     * Fuseau de l'école, pour les dates affichées. L'application tourne en
     * UTC ; Brazzaville a une heure d'avance.
     */
    'fuseau' => env('ECOLE_FUSEAU', 'Africa/Brazzaville'),

    /*
     * Année de mise en ligne du site. Renseignée, le copyright affiche une
     * plage (2024–2026) ; vide, il n'affiche que l'année courante.
     */
    'annee_creation' => env('ECOLE_ANNEE_CREATION'),
];
