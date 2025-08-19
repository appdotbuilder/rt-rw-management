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
        Schema::create('administrative_letters', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->unique()->comment('Official letter number');
            $table->enum('type', ['permit', 'certificate', 'recommendation', 'notification', 'other'])->comment('Type of letter');
            $table->string('subject')->comment('Letter subject/title');
            $table->text('content')->comment('Letter content');
            $table->foreignId('requester_id')->nullable()->constrained('residents')->onDelete('set null');
            $table->foreignId('household_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'completed'])->default('draft');
            $table->date('request_date')->comment('Date of request');
            $table->date('approved_date')->nullable()->comment('Date approved');
            $table->date('completed_date')->nullable()->comment('Date completed/issued');
            $table->text('approval_notes')->nullable()->comment('Notes from approver');
            $table->text('rejection_reason')->nullable()->comment('Reason if rejected');
            $table->json('required_documents')->nullable()->comment('List of required documents');
            $table->json('attachments')->nullable()->comment('Uploaded attachments');
            $table->timestamps();
            
            // Indexes
            $table->index('letter_number');
            $table->index('type');
            $table->index('status');
            $table->index('request_date');
            $table->index(['status', 'request_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrative_letters');
    }
};