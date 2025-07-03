<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('proposal_feedbacks')) {
            Schema::create('proposal_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
                $table->foreignId('reviewer_id')->constrained('users');
                $table->text('feedback');
                $table->string('type')->default('comment');
                $table->string('attachment')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Don't drop the table in down method to prevent conflicts
    }
};