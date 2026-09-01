<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Certaines sections présentent des cartes à pictogramme. Le nom de l'icône
 * est stocké ici pour rester modifiable depuis le back-office.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->string('icon', 40)->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->dropColumn('icon');
        });
    }
};
