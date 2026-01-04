<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parent_portal_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('parent_detail_id');
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password_hash');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('force_password_reset')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->enum('access_level', ['basic', 'standard', 'premium'])->default('basic');
            $table->json('permissions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('parent_detail_id')->references('id')->on('parent_details')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_portal_accesses');
    }
};
