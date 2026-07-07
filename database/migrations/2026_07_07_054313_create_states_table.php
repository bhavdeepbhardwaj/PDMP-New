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
        Schema::create('states', function (Blueprint $table) {

            $table->id();

            $table->string('state_code',30)->unique();

            $table->string('state_name',200);

            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

            $table->string('created_by')->default(0)->comment('Create User ID');

            $table->string('updated_by')->default(0)->comment('Admin ID');

            $table->boolean('is_deleted')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
