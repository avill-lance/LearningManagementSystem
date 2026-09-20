<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('password');
            $table->string('email', 100)->unique();
            $table->enum('role', ['Admin', 'Staff', 'Registrar', 'Accounting', 'Teacher', 'Student', 'Guardian'])->default('Student');
            $table->enum('status', ['Active', 'Inactive', 'Suspended', 'Locked'])->default('Active');
            $table->boolean('is_deleted')->default(false);
            $table->string('contact_number', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('users'); }
};
