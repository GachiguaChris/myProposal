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
        Schema::table('templates', function (Blueprint $table) {
            if (!Schema::hasColumn('templates', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('content');
            }
            
            if (!Schema::hasColumn('templates', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('is_active');
            }
            
            if (!Schema::hasColumn('templates', 'project_category_id') && Schema::hasColumn('templates', 'category_id')) {
                // For MariaDB compatibility, use change instead of renameColumn
                $table->foreignId('project_category_id')->nullable()->after('content');
            }
            
            if (!Schema::hasColumn('templates', 'file_path')) {
                $table->string('file_path')->nullable()->after('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            // Don't remove columns in down method to prevent data loss
        });
    }
};