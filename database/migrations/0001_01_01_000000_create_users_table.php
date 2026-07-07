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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->string('employee_code', 50)->unique();

            $table->string('title')->nullable();

            $table->string('first_name');

            $table->string('middle_name')->nullable();

            $table->string('last_name');

            $table->string('name');

            $table->string('email', 191)->unique();

            $table->string('username')->nullable();

            $table->string('mobile_number')->nullable();

            $table->text('official_address')->nullable();

            $table->unsignedBigInteger('organization_id')->nullable();

            $table->unsignedBigInteger('state_id')->nullable();

            $table->unsignedBigInteger('state_board_id')->nullable();

            $table->unsignedBigInteger('port_id')->nullable();

            $table->unsignedBigInteger('role_id');

            $table->unsignedBigInteger('department_id')->nullable();

            $table->unsignedBigInteger('port_type_id')->nullable();

            $table->unsignedBigInteger('report_to_user_id')->nullable();

            $table->json('extra_module')->nullable();

            $table->unsignedBigInteger('created_by')->nullable()->comment('Create User ID');

            $table->unsignedBigInteger('updated_by')->nullable()->comment('Admin ID');

            $table->string('password');

            $table->boolean('status')->default(true);

            $table->boolean('force_password_change')->default(true);

            $table->timestamp('password_changed_at')->nullable();

            $table->boolean('is_deleted')->default(false);

            $table->rememberToken();

            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
