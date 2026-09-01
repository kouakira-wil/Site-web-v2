<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Certaines sections portent un texte long en plusieurs paragraphes, que
 * `intro` (une accroche courte) ne peut pas accueillir.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->text('body')->nullable()->after('intro');
        });
    }

    public function down(): void
    {
        Schema::table('page_headers', function (Blueprint $table): void {
            $table->dropColumn('body');
        });
    }
};
