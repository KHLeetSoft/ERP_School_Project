<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_thoughts', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('thought_en');
            $table->string('thought_hi');
            $table->string('source')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_thoughts');
    }
};


