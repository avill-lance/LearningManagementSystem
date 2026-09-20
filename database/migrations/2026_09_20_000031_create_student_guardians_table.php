<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('student_guardians', function (Blueprint $table) { $table->increments('id'); $table->unsignedInteger('student_id'); $table->unsignedInteger('guardian_id'); $table->boolean('is_primary')->default(false); $table->unique(['student_id','guardian_id'], 'uq_student_guardian'); $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('guardian_id')->references('guardian_id')->on('guardians')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('student_guardians'); }
};
