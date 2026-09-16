<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Seconde photo, pour les sections qui en superposent deux.
 *
 * Une colonne dédiée plutôt qu'une table de galerie : les deux visuels ont des
 * rôles fixes et distincts dans la mise en page, il n'y a ni ordre ni nombre
 * variable à gérer.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->string('image_secondary')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->dropColumn('image_secondary');
        });
    }
};
