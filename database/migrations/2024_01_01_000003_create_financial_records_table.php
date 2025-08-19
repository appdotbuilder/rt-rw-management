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
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_number')->unique()->comment('Unique record number');
            $table->enum('type', ['income', 'expense', 'contribution', 'fine', 'donation'])->comment('Type of financial record');
            $table->enum('category', ['monthly_fee', 'security', 'cleaning', 'utilities', 'events', 'maintenance', 'emergency', 'other'])->comment('Category of transaction');
            $table->string('description')->comment('Description of transaction');
            $table->decimal('amount', 15, 2)->comment('Transaction amount');
            $table->foreignId('household_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->date('transaction_date')->comment('Date of transaction');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'digital_wallet', 'other'])->nullable();
            $table->string('payment_reference')->nullable()->comment('Payment reference number');
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('completed');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->json('attachments')->nullable()->comment('Receipt/document attachments');
            $table->timestamps();
            
            // Indexes
            $table->index('record_number');
            $table->index('type');
            $table->index('category');
            $table->index('transaction_date');
            $table->index('status');
            $table->index(['type', 'transaction_date']);
            $table->index(['household_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};