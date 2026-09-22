<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('subject_grading_templates', function (Blueprint $table) { $table->increments('id'); $table->unsignedInteger('subject_id'); $table->unsignedInteger('template_id'); $table->unique('subject_id', 'uq_subject_template'); $table->foreign('subject_id')->references('subject_id')->on('subjects')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('template_id')->references('template_id')->on('grading_templates')->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('subject_grading_templates'); }
};
