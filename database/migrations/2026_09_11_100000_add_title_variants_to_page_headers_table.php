<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Variantes de la seconde ligne du titre, pour la faire défiler.
 *
 * `tags` est déjà pris sur le bandeau d'accueil par la liste des filières :
 * il fallait une colonne distincte.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->json('title_variants')->nullable()->after('title_accent');
        });
    }

    public function down(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->dropColumn('title_variants');
        });
    }
};
