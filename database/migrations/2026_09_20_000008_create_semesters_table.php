<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->increments('semester_id');
            $table->unsignedInteger('school_year_id');
            $table->enum('semester_label', ['1st Semester', '2nd Semester']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_grading_locked')->default(false);
            $table->unique(['school_year_id', 'semester_label'], 'uq_semester_per_year');
            $table->foreign('school_year_id')->references('school_year_id')->on('school_years')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('semesters'); }
};
