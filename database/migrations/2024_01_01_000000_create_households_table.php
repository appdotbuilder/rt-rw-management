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
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('house_number')->comment('House/unit number in the RT/RW');
            $table->string('rt_number')->comment('RT (Rukun Tetangga) number');
            $table->string('rw_number')->comment('RW (Rukun Warga) number');
            $table->string('head_name')->comment('Name of household head');
            $table->string('phone')->nullable()->comment('Primary contact phone');
            $table->string('email')->nullable()->comment('Primary contact email');
            $table->text('address')->comment('Full address');
            $table->enum('status', ['active', 'inactive', 'moved'])->default('active');
            $table->integer('resident_count')->default(1)->comment('Number of residents in household');
            $table->decimal('monthly_contribution', 10, 2)->default(0)->comment('Monthly contribution amount');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index(['rt_number', 'rw_number']);
            $table->index('house_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};