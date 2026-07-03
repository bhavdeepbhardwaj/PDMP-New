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
        Schema::create('login_logs', function (Blueprint $table) {

            $table->id();

            // User Reference
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Employee Code
            $table->string('employee_code', 50)->nullable();

            // Client Information
            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            // Login Status
            $table->string('status', 100);

            // Login / Logout Time
            $table->timestamp('login_at')->nullable();

            $table->timestamp('logout_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');
            $table->index('employee_code');
            $table->index('status');
            $table->index('login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
