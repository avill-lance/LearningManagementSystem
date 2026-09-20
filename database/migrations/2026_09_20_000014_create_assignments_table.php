<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('assignments', function (Blueprint $table) {
  $table->increments('assignment_id'); $table->unsignedInteger('schedule_id'); $table->string('title', 255); $table->text('instructions')->nullable(); $table->dateTime('due_date'); $table->decimal('max_score', 6, 2); $table->dateTime('created_at')->useCurrent(); $table->foreign('schedule_id')->references('schedule_id')->on('schedules')->cascadeOnDelete()->cascadeOnUpdate();
 }); }
 public function down(): void { Schema::dropIfExists('assignments'); }
};
