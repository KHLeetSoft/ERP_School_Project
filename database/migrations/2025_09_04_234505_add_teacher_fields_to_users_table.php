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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('qualification')->nullable()->after('address');
            $table->integer('experience')->nullable()->after('qualification');
            $table->string('subject')->nullable()->after('experience');
            $table->date('joining_date')->nullable()->after('subject');
            $table->decimal('salary', 10, 2)->nullable()->after('joining_date');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('salary');
            $table->string('profile_image')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'address',
                'qualification',
                'experience',
                'subject',
                'joining_date',
                'salary',
                'status',
                'profile_image'
            ]);
        });
    }
};