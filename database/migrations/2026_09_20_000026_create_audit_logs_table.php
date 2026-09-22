<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('audit_logs', function (Blueprint $table) { $table->increments('log_id'); $table->unsignedInteger('user_id')->nullable(); $table->string('action', 100); $table->string('entity_type', 100); $table->unsignedInteger('entity_id'); $table->dateTime('timestamp')->useCurrent(); $table->index(['entity_type','entity_id'], 'idx_audit_entity'); $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('audit_logs'); }
};
