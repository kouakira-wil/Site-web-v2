<?php

declare(strict_types=1);

use App\Models\Cycle;
use App\Models\PageHeader;
use Illuminate\Database\Migrations\Migration;

/**
 * L'ESCAT n'ouvre plus de BTS.
 *
 * Le retrait porte sur des données, pas sur le schéma : le cycle lui-même, la
 * marche du parcours qui le décrivait, la question de la FAQ qui n'existait
 * que pour lui, et son étiquette sur le bandeau des filières.
 *
 * Les blocs du parcours sont renumérotés plutôt que supprimés puis recréés :
 * les textes de la Licence et du Master peuvent avoir été retouchés au
 * back-office depuis le premier remplissage, et les recréer les écraserait.
 */
return new class extends Migration
{
    public function up(): void
    {
        /*
         * Le pivot `cycle_project` est en cascade : les filières rattachées au
         * BTS perdent cette ligne, et gardent leurs autres cycles.
         */
        Cycle::query()->where('name', 'Cycle BTS')->delete();

        PageHeader::query()->where('key', 'ecole.parcours.2')->delete();
        PageHeader::query()->where('key', 'home.faq.4')->delete();

        // Licence et Master remontent d'un rang, contenu inchangé.
        PageHeader::query()->where('key', 'ecole.parcours.3')->update([
            'key' => 'ecole.parcours.2',
            'label' => 'Parcours — deuxième étape',
            'position' => 7,
        ]);

        PageHeader::query()->where('key', 'ecole.parcours.4')->update([
            'key' => 'ecole.parcours.3',
            'label' => 'Parcours — troisième étape',
            'position' => 8,
        ]);

        $filieres = PageHeader::query()->where('key', 'filieres')->first();

        if ($filieres !== null) {
            $filieres->forceFill([
                'tags' => array_values(array_diff($filieres->tagList(), ['BTS'])),
            ])->save();
        }
    }

    public function down(): void
    {
        /*
         * Pas de retour en arrière : réinsérer le cycle ne rendrait pas aux
         * filières les rattachements perdus, ni aux textes leur rédaction
         * d'origine. Rejouer les seeders est plus honnête qu'une restauration
         * partielle qui aurait l'air complète.
         */
    }
};
