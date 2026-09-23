<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('submissions', function (Blueprint $table) { $table->increments('submission_id'); $table->unsignedInteger('assignment_id'); $table->unsignedInteger('student_id'); $table->dateTime('submitted_at')->nullable(); $table->string('file_url', 500)->nullable(); $table->decimal('score', 6, 2)->nullable(); $table->enum('status', ['Pending','Submitted','Late','Graded'])->default('Pending'); $table->unique(['assignment_id','student_id'], 'uq_submission_once'); $table->index('status', 'idx_submission_status'); $table->foreign('assignment_id')->references('assignment_id')->on('assignments')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('submissions'); }
};
