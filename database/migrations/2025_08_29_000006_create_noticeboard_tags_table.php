<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('noticeboard_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->default('#6c757d');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot table for noticeboard-tag relationship
        Schema::create('noticeboard_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('noticeboard_id')->constrained()->onDelete('cascade');
            $table->foreignId('noticeboard_tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['noticeboard_id', 'noticeboard_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticeboard_tag');
        Schema::dropIfExists('noticeboard_tags');
    }
};
