<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('grade_components', function (Blueprint $table) { $table->increments('component_id'); $table->unsignedInteger('enrollment_id'); $table->unsignedInteger('subject_id'); $table->enum('component_type', ['written_work','performance_task','exam']); $table->enum('source_type', ['submission','quiz_attempt','manual'])->nullable(); $table->unsignedInteger('source_id')->nullable(); $table->decimal('raw_score', 6, 2); $table->decimal('max_score', 6, 2); $table->foreign('enrollment_id')->references('enrollment_id')->on('enrollments')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('subject_id')->references('subject_id')->on('subjects')->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('grade_components'); }
};
