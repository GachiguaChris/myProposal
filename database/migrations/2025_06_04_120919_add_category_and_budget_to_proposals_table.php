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
      Schema::table('proposals', function (Blueprint $table) {
    $table->foreignId('project_category_id')->nullable()->constrained('project_categories')->onDelete('set null');
    $table->decimal('budget_requested', 15, 2)->default(0);  // How much budget user requests for the project
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['project_category_id']);
            $table->dropColumn(['project_category_id', 'budget_requested']);
        });
    }
};
