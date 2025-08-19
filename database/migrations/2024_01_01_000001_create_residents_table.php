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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('household_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('Full name of resident');
            $table->string('id_number')->unique()->comment('National ID number (KTP)');
            $table->date('birth_date')->comment('Date of birth');
            $table->enum('gender', ['male', 'female'])->comment('Gender');
            $table->enum('relationship', ['head', 'spouse', 'child', 'parent', 'other'])->comment('Relationship to household head');
            $table->string('occupation')->nullable()->comment('Job or occupation');
            $table->string('phone')->nullable()->comment('Personal phone number');
            $table->string('email')->nullable()->comment('Personal email');
            $table->enum('status', ['active', 'inactive', 'moved', 'deceased'])->default('active');
            $table->date('moved_in_date')->nullable()->comment('Date moved into household');
            $table->date('moved_out_date')->nullable()->comment('Date moved out');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('household_id');
            $table->index('name');
            $table->index('id_number');
            $table->index('status');
            $table->index(['household_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};