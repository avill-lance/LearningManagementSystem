<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('quiz_questions', function (Blueprint $table) { $table->increments('question_id'); $table->unsignedInteger('quiz_id'); $table->text('question_text'); $table->enum('question_type', ['multiple_choice','true_false','short_answer']); $table->longText('options')->nullable(); $table->string('correct_answer', 500); $table->foreign('quiz_id')->references('quiz_id')->on('quizzes')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('quiz_questions'); }
};
