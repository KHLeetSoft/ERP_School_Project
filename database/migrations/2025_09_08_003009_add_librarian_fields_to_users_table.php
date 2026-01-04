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
            // Add librarian-specific fields (only if they don't already exist)
            if (!Schema::hasColumn('users', 'employee_id')) {
            $table->string('employee_id')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'designation')) {
            $table->string('designation')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
            $table->date('date_of_birth')->nullable()->after('designation');
            }
            if (!Schema::hasColumn('users', 'gender')) {
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                // Avoid referencing a non-existent column position; no 'after' clause
                $table->timestamp('last_login_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable();
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('users', 'specializations')) {
                $table->json('specializations')->nullable();
            }
            if (!Schema::hasColumn('users', 'certifications')) {
                $table->json('certifications')->nullable();
            }
            if (!Schema::hasColumn('users', 'emergency_contact')) {
                $table->json('emergency_contact')->nullable();
            }

            // Skip adding indexes here to avoid duplicate key issues during partial runs
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'status']);
            $table->dropIndex('employee_id');
            $table->dropColumn([
                'employee_id',
                'designation',
                'date_of_birth',
                'gender',
                'last_login_at',
                'last_login_ip',
                'bio',
                'specializations',
                'certifications',
                'emergency_contact'
            ]);
        });
    }
};