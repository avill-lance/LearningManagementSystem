<?php
// database/migrations/2026_01_01_000003_create_lms_accounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lms_accounts', function (Blueprint $table) {
            $table->bigIncrements('account_id');          // INT AUTO_INCREMENT PK
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('entity_id');
            $table->enum('entity_type', ['student', 'teacher', 'admin']);
            $table->string('username', 50)->unique();
            $table->string('password_hash', 255);
            $table->string('recovery_email', 100)->nullable();
            $table->enum('status', [
                'Active', 'Inactive', 'Locked', 'Suspended', 'Pending Verification',
            ])->default('Pending Verification');
            $table->boolean('is_active')->default(true);
            $table->boolean('must_change_password')->default(true);
            $table->timestamp('password_changed_at')->nullable();
            $table->unsignedInteger('failed_login_count')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token', 100)->nullable()->unique();
            $table->string('password_reset_token', 100)->nullable()->unique();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret', 255)->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();  // created_at, updated_at

            $table->index(['status', 'is_active'], 'idx_lms_accounts_status');
            $table->index('recovery_email', 'idx_lms_accounts_recovery_email');
            $table->index(['entity_type', 'entity_id'], 'idx_lms_accounts_entity');

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->nullOnDelete();
            $table->foreign('created_by')
                  ->references('user_id')->on('users')
                  ->nullOnDelete();
            $table->foreign('updated_by')
                  ->references('user_id')->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lms_accounts');
    }
};