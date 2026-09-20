<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('messages', function (Blueprint $table) { $table->increments('message_id'); $table->unsignedInteger('sender_id'); $table->unsignedInteger('receiver_id'); $table->text('body'); $table->dateTime('sent_at')->useCurrent(); $table->dateTime('read_at')->nullable(); $table->index(['receiver_id','read_at'], 'idx_message_receiver_unread'); $table->foreign('sender_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate(); $table->foreign('receiver_id')->references('user_id')->on('users')->cascadeOnDelete()->cascadeOnUpdate(); }); }
 public function down(): void { Schema::dropIfExists('messages'); }
};
