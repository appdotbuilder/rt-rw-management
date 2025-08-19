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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Announcement title');
            $table->text('content')->comment('Announcement content');
            $table->enum('type', ['general', 'urgent', 'event', 'meeting', 'maintenance', 'security'])->comment('Type of announcement');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->datetime('publish_at')->nullable()->comment('When to publish announcement');
            $table->datetime('expires_at')->nullable()->comment('When announcement expires');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('send_notification')->default(false)->comment('Send push notification');
            $table->json('target_audience')->nullable()->comment('Specific RT/RW or all');
            $table->json('attachments')->nullable()->comment('Attached files/images');
            $table->integer('view_count')->default(0)->comment('Number of views');
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('priority');
            $table->index('status');
            $table->index('publish_at');
            $table->index(['status', 'publish_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};