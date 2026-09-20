<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('attendance_records', function (Blueprint $table) { $table->increments('attendance_id'); $table->unsignedInteger('schedule_id'); $table->unsignedInteger('student_id'); $table->date('attendance_date'); $table->enum('status', ['Present','Late','Absent','Excused']); $table->unsignedInteger('logged_by'); $table->dateTime('logged_at')->useCurrent(); $table->unique(['schedule_id','student_id','attendance_date'], 'uq_attendance_once'); $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('logged_by')->references('user_id')->on('users')->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('attendance_records'); }
};
