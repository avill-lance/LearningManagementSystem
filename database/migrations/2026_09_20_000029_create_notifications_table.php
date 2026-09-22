<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('notifications', function (Blueprint $table) { $table->increments('notification_id'); $table->unsignedInteger('user_id'); $table->string('type', 50); $table->string('message', 500); $table->boolean('is_read')->default(false); $table->dateTime('created_at')->useCurrent(); $table->index(['user_id','is_read'], 'idx_notification_unread'); $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('notifications'); }
};
