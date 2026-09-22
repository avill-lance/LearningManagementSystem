<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('schedules', function (Blueprint $table) {
  $table->increments('schedule_id'); $table->unsignedInteger('section_id'); $table->unsignedInteger('subject_id'); $table->unsignedInteger('teacher_id')->nullable(); $table->unsignedInteger('room_id'); $table->enum('day_of_week', ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']); $table->time('start_time'); $table->time('end_time'); $table->dateTime('created_at')->useCurrent();
  $table->foreign('section_id')->references('section_id')->on('class_sections')->cascadeOnUpdate(); $table->foreign('subject_id')->references('subject_id')->on('subjects')->cascadeOnUpdate(); $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->nullOnDelete()->cascadeOnUpdate(); $table->foreign('room_id')->references('room_id')->on('rooms')->cascadeOnUpdate();
 }); }
 public function down(): void { Schema::dropIfExists('schedules'); }
};
