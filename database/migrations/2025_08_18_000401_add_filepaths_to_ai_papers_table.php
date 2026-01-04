<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_papers', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_papers', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('payload');
            }
            if (!Schema::hasColumn('ai_papers', 'doc_path')) {
                $table->string('doc_path')->nullable()->after('pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ai_papers', function (Blueprint $table) {
            if (Schema::hasColumn('ai_papers', 'doc_path')) {
                $table->dropColumn('doc_path');
            }
            if (Schema::hasColumn('ai_papers', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
        });
    }
};


