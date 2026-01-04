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
        Schema::create('noticeboard_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('noticeboard_id')->constrained()->onDelete('cascade');
            $table->datetime('liked_at');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate likes
            $table->unique(['user_id', 'noticeboard_id']);
            
            // Indexes
            $table->index(['noticeboard_id', 'liked_at']);
            $table->index(['user_id', 'liked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticeboard_likes');
    }
};
