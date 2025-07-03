<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_feedbacks', function (Blueprint $table) {
            $table->text('feedback')->nullable()->change();
            $table->boolean('revision_requested')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('proposal_feedbacks', function (Blueprint $table) {
            $table->text('feedback')->nullable(false)->change();
            $table->dropColumn('revision_requested');
        });
    }
};