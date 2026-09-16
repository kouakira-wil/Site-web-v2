<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Réseaux sociaux de l'école
|--------------------------------------------------------------------------
|
| Les liens vides ne sont pas affichés sur le site. Renseignez-les ici ou
| via les variables d'environnement correspondantes.
|
*/

return [
    'facebook' => env('SOCIAL_FACEBOOK', 'https://www.facebook.com/escat18/'),
    'linkedin' => env('SOCIAL_LINKEDIN', ''),
    'instagram' => env('SOCIAL_INSTAGRAM', ''),
    'tiktok' => env('SOCIAL_TIKTOK', ''),
    'youtube' => env('SOCIAL_YOUTUBE', ''),
];
