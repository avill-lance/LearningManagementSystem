<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('final_grades', function (Blueprint $table) { $table->increments('final_grade_id'); $table->unsignedInteger('enrollment_id'); $table->unsignedInteger('subject_id'); $table->decimal('final_rating', 5, 2); $table->enum('remarks', ['Passed','Failed','Incomplete'])->nullable(); $table->boolean('is_locked')->default(false); $table->dateTime('computed_at')->useCurrent(); $table->unique(['enrollment_id','subject_id'], 'uq_final_grade'); $table->index('is_locked', 'idx_final_grade_locked'); $table->foreign('enrollment_id')->references('enrollment_id')->on('enrollments')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('subject_id')->references('subject_id')->on('subjects')->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('final_grades'); }
};
