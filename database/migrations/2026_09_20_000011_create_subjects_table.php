<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('subjects', function (Blueprint $table) {
  $table->increments('subject_id'); $table->unsignedInteger('strand_id')->nullable(); $table->string('subject_code', 20)->unique(); $table->string('subject_name', 150); $table->enum('subject_type', ['Core','Applied','Specialized'])->default('Core'); $table->enum('grade_level', ['11','12']); $table->enum('semester', ['1st Semester','2nd Semester']); $table->decimal('units', 3, 1); $table->text('description')->nullable(); $table->enum('status', ['Active','Inactive'])->default('Active'); $table->dateTime('created_at')->useCurrent();
  $table->foreign('strand_id')->references('strand_id')->on('strands')->cascadeOnDelete();
 }); }
 public function down(): void { Schema::dropIfExists('subjects'); }
};
