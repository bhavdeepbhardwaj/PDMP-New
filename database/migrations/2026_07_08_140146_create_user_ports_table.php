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
        Schema::create('user_ports', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->unsignedBigInteger('port_id');

            $table->boolean('is_primary')->default(false);

            $table->boolean('status')->default(true);

            $table->boolean('is_deleted')->default(false);

            $table->unsignedBigInteger('created_by')->default(0);

            $table->unsignedBigInteger('updated_by')->default(0);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('port_id')
                ->references('id')
                ->on('ports')
                ->cascadeOnDelete();

            $table->unique([
                'user_id',
                'port_id'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ports');
    }
};
