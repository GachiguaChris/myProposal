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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('title'); // Title of the proposal
            $table->string('submitted_by'); // Name of the submitter
            $table->string('email'); // Email of submitter
            $table->string('organization_name'); // Organization name

            // ➕ Proposal content
            $table->string('address'); // Address of organization
            $table->string('phone'); // Phone number
            $table->text('summary'); // Summary of the proposal
            $table->text('background'); // Background of the proposal
            $table->text('activities'); // Planned activities
            $table->text('budget'); // Budget breakdown

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // FK to users

            $table->string('status')->default('pending'); // Proposal status
            $table->string('stage')->nullable(); // 🔒 Stage (admin-controlled only)

            $table->timestamp('accepted_at')->nullable(); // Timestamp when approved
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
