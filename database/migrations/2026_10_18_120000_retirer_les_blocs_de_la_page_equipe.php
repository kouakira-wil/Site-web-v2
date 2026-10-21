<?php

declare(strict_types=1);

use App\Models\PageHeader;
use Illuminate\Database\Migrations\Migration;

/**
 * La page Équipe a cédé sa place à Admission.
 *
 * Son bandeau et les quatre cartes « Approche pédagogique » restaient dans le
 * back-office alors qu'aucune page ne les rendait plus : un rédacteur pouvait
 * les remplir sans jamais voir le résultat.
 *
 * Les membres de l'équipe, eux, ne sont pas touchés : leurs fiches restent en
 * ligne et s'administrent toujours depuis « Contenus → Équipe ».
 */
return new class extends Migration
{
    public function up(): void
    {
        PageHeader::query()->where('group', 'equipe')->delete();
        PageHeader::query()->where('key', 'equipe')->delete();

        /*
         * Le bouton « Rejoindre l'équipe » de l'accueil visait /equipe, qui
         * redirige désormais vers Admission — une destination qui ne répond
         * pas à son libellé. Il mène au formulaire de contact.
         */
        PageHeader::query()
            ->where('key', 'home.process')
            ->where('cta_url', '/equipe')
            ->update(['cta_url' => '/contact']);
    }

    public function down(): void
    {
        /*
         * Pas de retour en arrière : les textes supprimés ne sont pas
         * reconstituables ici. Rejouer les seeders est plus honnête qu'une
         * restauration partielle qui aurait l'air complète.
         */
    }
};
