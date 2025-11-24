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
        // Check if old table exists and rename it
        if (Schema::hasTable('password_resets') && !Schema::hasTable('password_reset_tokens')) {
            Schema::rename('password_resets', 'password_reset_tokens');
        } elseif (!Schema::hasTable('password_reset_tokens')) {
            // Create new table if neither exists
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email', 191)->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('password_reset_tokens')) {
            Schema::rename('password_reset_tokens', 'password_resets');
        }
    }
};

