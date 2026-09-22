<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('student_id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('lrn', 12)->unique();
            $table->string('student_number', 20)->unique();
            $table->enum('grade_level', ['11', '12']);
            $table->unsignedInteger('strand_id')->nullable();
            $table->unsignedInteger('guardian_id')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('strand_id')->references('strand_id')->on('strands')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('guardian_id')->references('guardian_id')->on('guardians')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('students'); }
};
