<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('result_statistics', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('result_announcement_id')->nullable();
            $table->string('title');
            $table->json('filters')->nullable();
            $table->json('metrics')->nullable();
            $table->dateTime('generated_at')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['school_id']);
            $table->index(['result_announcement_id']);
            $table->index(['generated_at']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_statistics');
    }
};


