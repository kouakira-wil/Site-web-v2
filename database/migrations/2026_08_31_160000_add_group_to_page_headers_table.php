<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Les sections de la page d'accueil rejoignent la table des en-têtes : elles
 * ont exactement les mêmes champs (surtitre, titre, accroche, visuel, bouton,
 * liste). `group` les sépare dans le back-office, `position` fixe leur ordre.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->string('group')->default('pages')->after('key');
            $table->unsignedSmallInteger('position')->default(0)->after('group');
            $table->index(['group', 'position']);
        });
    }

    public function down(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->dropIndex(['group', 'position']);
            $table->dropColumn(['group', 'position']);
        });
    }
};
