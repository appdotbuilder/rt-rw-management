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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Event title');
            $table->text('description')->comment('Event description');
            $table->enum('type', ['meeting', 'social', 'religious', 'security', 'gotong_royong', 'celebration', 'other'])->comment('Type of event');
            $table->datetime('start_datetime')->comment('Event start date and time');
            $table->datetime('end_datetime')->comment('Event end date and time');
            $table->string('location')->comment('Event location');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->integer('max_participants')->nullable()->comment('Maximum number of participants');
            $table->decimal('participation_fee', 10, 2)->default(0)->comment('Fee to participate');
            $table->enum('status', ['planned', 'confirmed', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->text('agenda')->nullable()->comment('Event agenda');
            $table->json('required_items')->nullable()->comment('Items participants should bring');
            $table->json('target_participants')->nullable()->comment('Specific RT/RW or all');
            $table->boolean('requires_registration')->default(false)->comment('Whether registration is required');
            $table->datetime('registration_deadline')->nullable()->comment('Registration deadline');
            $table->text('notes')->nullable()->comment('Additional notes');
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('status');
            $table->index('start_datetime');
            $table->index(['status', 'start_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};