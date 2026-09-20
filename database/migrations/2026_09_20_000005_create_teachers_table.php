<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('teacher_id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('teacher_number', 20)->unique();
            $table->string('specialization', 150)->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('teachers'); }
};
