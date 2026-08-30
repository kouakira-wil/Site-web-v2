<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_label')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index('position');
        });

        // A filière runs across several cycles (BTS then Licence then Master),
        // and each cycle hosts several filières.
        Schema::create('cycle_project', function (Blueprint $table) {
            $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            $table->primary(['cycle_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycle_project');
        Schema::dropIfExists('cycles');
    }
};
