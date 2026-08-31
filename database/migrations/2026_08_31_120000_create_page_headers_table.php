<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_headers', function (Blueprint $table): void {
            $table->id();
            // Clé technique de la page : fixée par le code, jamais saisie.
            $table->string('key')->unique();
            $table->string('label');
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->text('intro')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_headers');
    }
};
