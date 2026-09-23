<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->increments('guardian_id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('full_name', 100);
            $table->string('relationship', 50)->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('guardians'); }
};
