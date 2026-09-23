<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('enrollments', function (Blueprint $table) {
  $table->increments('enrollment_id'); $table->unsignedInteger('student_id'); $table->unsignedInteger('section_id'); $table->string('school_year', 9); $table->unsignedInteger('school_year_id'); $table->enum('semester', ['1st Semester','2nd Semester']); $table->dateTime('date_enrolled')->useCurrent(); $table->enum('status', ['Enrolled','Dropped','Pending'])->default('Pending');
  $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnUpdate(); $table->foreign('section_id')->references('section_id')->on('class_sections')->cascadeOnUpdate(); $table->foreign('school_year_id')->references('school_year_id')->on('school_years')->cascadeOnUpdate();
 }); }
 public function down(): void { Schema::dropIfExists('enrollments'); }
};
