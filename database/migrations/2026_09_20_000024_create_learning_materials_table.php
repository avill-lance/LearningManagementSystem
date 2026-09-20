<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('learning_materials', function (Blueprint $table) { $table->increments('material_id'); $table->unsignedInteger('schedule_id'); $table->string('title', 255); $table->string('file_url', 500); $table->enum('status', ['Draft','Published','Archived'])->default('Draft'); $table->unsignedInteger('uploaded_by'); $table->dateTime('uploaded_at')->useCurrent(); $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('uploaded_by')->references('user_id')->on('users')->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('learning_materials'); }
};
