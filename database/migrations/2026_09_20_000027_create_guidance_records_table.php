<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('guidance_records', function (Blueprint $table) { $table->increments('record_id'); $table->unsignedInteger('student_id'); $table->unsignedInteger('logged_by'); $table->string('category', 50); $table->text('notes'); $table->boolean('is_restricted')->default(true); $table->dateTime('created_at')->useCurrent(); $table->foreign('student_id')->references('student_id')->on('students')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('logged_by')->references('user_id')->on('users')->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('guidance_records'); }
};
