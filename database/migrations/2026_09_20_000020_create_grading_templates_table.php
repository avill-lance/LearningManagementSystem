<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('grading_templates', function (Blueprint $table) { $table->increments('template_id'); $table->string('name', 100); $table->decimal('written_work_weight', 4, 2); $table->decimal('performance_task_weight', 4, 2); $table->decimal('exam_weight', 4, 2); }); }
 public function down(): void { Schema::dropIfExists('grading_templates'); }
};
