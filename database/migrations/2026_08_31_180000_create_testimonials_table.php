<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            // Le nom reste facultatif : les témoignages en ligne sont anonymes,
            // signés par la promotion plutôt que par la personne.
            $table->string('author')->nullable();
            $table->text('quote');
            $table->string('program')->nullable();
            $table->unsignedSmallInteger('graduation_year')->nullable();
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
