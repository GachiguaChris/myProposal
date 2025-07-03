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
        // Check if the table already exists before trying to create it
        if (!Schema::hasTable('proposal_versions')) {
            Schema::create('proposal_versions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id');
                $table->longText('content');
                $table->integer('version_number');
                $table->foreignId('created_by');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in down method to prevent conflicts
    }
};