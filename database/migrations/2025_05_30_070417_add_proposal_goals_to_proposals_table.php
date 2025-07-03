<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::table('proposals', function (Blueprint $table) {
        // Add only missing columns:
        if (!Schema::hasColumn('proposals', 'proposal_goals')) {
            $table->string('proposal_goals', 255)->after('budget');
        }
        if (!Schema::hasColumn('proposals', 'duration')) {
            $table->string('duration')->after('proposal_goals');
        }
        if (!Schema::hasColumn('proposals', 'organization_type')) {
            $table->string('organization_type')->after('duration');
        }
        // Skip 'document' because it exists
    });
}

public function down()
{
    Schema::table('proposals', function (Blueprint $table) {
        if (Schema::hasColumn('proposals', 'proposal_goals')) {
            $table->dropColumn('proposal_goals');
        }
        if (Schema::hasColumn('proposals', 'duration')) {
            $table->dropColumn('duration');
        }
        if (Schema::hasColumn('proposals', 'organization_type')) {
            $table->dropColumn('organization_type');
        }
        // Skip dropping 'document'
    });
}


};
