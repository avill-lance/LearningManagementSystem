<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('quiz_attempts', function (Blueprint $table) { $table->increments('attempt_id'); $table->unsignedInteger('quiz_id'); $table->unsignedInteger('student_id'); $table->decimal('score', 6, 2)->nullable(); $table->dateTime('started_at')->useCurrent(); $table->dateTime('submitted_at')->nullable(); $table->unique(['quiz_id','student_id'], 'uq_attempt_once'); $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('quiz_attempts'); }
};
