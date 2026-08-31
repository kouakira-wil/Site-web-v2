<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Listes saisies une entrée par ligne dans le back-office.
            $table->text('objectives')->nullable()->after('description');
            $table->text('skills')->nullable()->after('objectives');
            $table->text('outcomes')->nullable()->after('skills');
            $table->text('audience')->nullable()->after('outcomes');

            // Bloc « Ce que vous y apprenez » : liste de {title, description, points[]}.
            $table->json('learning_blocks')->nullable()->after('audience');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'objectives',
                'skills',
                'outcomes',
                'audience',
                'learning_blocks',
            ]);
        });
    }
};
