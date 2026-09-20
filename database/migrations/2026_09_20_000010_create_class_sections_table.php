<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_sections', function (Blueprint $table) {
            $table->increments('section_id');
            $table->unsignedInteger('strand_id');
            $table->unsignedInteger('adviser_id')->nullable();
            $table->enum('grade_level', ['11', '12']);
            $table->string('section_name', 50);
            $table->string('school_year', 9);
            $table->integer('max_slots');
            $table->enum('status', ['Open', 'Closed', 'Cancelled'])->default('Open');
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('strand_id')->references('strand_id')->on('strands')->cascadeOnUpdate();
            $table->foreign('adviser_id')->references('teacher_id')->on('teachers')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('class_sections'); }
};
