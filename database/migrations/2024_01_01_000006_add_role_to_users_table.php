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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['system_admin', 'rt_head', 'rw_head', 'management', 'resident'])->default('resident')->after('email');
            $table->string('rt_number')->nullable()->after('role')->comment('RT number for RT heads');
            $table->string('rw_number')->nullable()->after('rt_number')->comment('RW number for RW heads');
            $table->boolean('is_active')->default(true)->after('rw_number');
            $table->text('profile_notes')->nullable()->after('is_active');
            
            // Indexes
            $table->index('role');
            $table->index(['rt_number', 'rw_number']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['rt_number', 'rw_number']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['role', 'rt_number', 'rw_number', 'is_active', 'profile_notes']);
        });
    }
};