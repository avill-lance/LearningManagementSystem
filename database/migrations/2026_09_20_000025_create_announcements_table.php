<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('announcements', function (Blueprint $table) { $table->increments('announcement_id'); $table->unsignedInteger('posted_by'); $table->unsignedInteger('section_id')->nullable(); $table->string('title', 255); $table->text('body'); $table->dateTime('posted_at')->useCurrent(); $table->foreign('posted_by')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('section_id')->references('section_id')->on('class_sections')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('announcements'); }
};
