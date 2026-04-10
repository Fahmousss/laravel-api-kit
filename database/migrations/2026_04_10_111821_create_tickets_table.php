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
       Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('reporter_id')->constrained('users');
            $table->foreignUuid('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('ticket_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type');
            $table->string('status')->default('open');
            $table->string('priority')->default('medium');
            $table->date('due_date')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'ticket_number']);
            $table->index(['project_id', 'status']);
            $table->index(['project_id', 'type']);
            $table->index(['assignee_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
