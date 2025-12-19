<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            
            // Tracking sync
            $table->timestamp('synced_at');
            $table->integer('records_fetched')->default(0);
            $table->integer('records_created')->default(0);
            $table->integer('records_updated')->default(0);
            $table->integer('records_failed')->default(0)->comment('Failed records');
            
            // Status sync
            $table->enum('status', ['success', 'failed', 'partial'])->default('success');
            $table->text('message')->nullable();
            $table->text('error_details')->nullable()->comment('JSON error details');
            
            // Sync metadata
            $table->string('sync_type', 50)->default('manual')->comment('manual, scheduled, auto');
            $table->integer('duration')->nullable()->comment('Sync duration in seconds');
            
            $table->timestamps();
            
            // Index
            $table->index('synced_at');
            $table->index('status');
            $table->index('sync_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
