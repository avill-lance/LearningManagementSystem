<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('quizzes', function (Blueprint $table) { $table->increments('quiz_id'); $table->unsignedInteger('schedule_id'); $table->string('title', 255); $table->integer('time_limit_minutes')->nullable(); $table->dateTime('created_at')->useCurrent(); $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('quizzes'); }
};
