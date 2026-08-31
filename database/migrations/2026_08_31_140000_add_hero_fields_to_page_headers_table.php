<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Passe les en-têtes au gabarit « services-details-3 » : titre sur deux lignes,
 * visuel de fond, bouton d'action et étiquettes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->string('title_accent')->nullable()->after('title');
            $table->string('image')->nullable()->after('intro');
            $table->string('cta_label')->nullable()->after('image');
            $table->string('cta_url')->nullable()->after('cta_label');
            // Une étiquette par ligne à la saisie, stockée en tableau.
            $table->json('tags')->nullable()->after('cta_url');
        });
    }

    public function down(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->dropColumn(['title_accent', 'image', 'cta_label', 'cta_url', 'tags']);
        });
    }
};
