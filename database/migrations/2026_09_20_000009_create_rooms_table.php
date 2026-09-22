<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('room_id');
            $table->string('room_name', 50)->unique();
            $table->string('building', 50);
            $table->integer('capacity');
            $table->dateTime('created_at')->useCurrent();
        });
    }

    public function down(): void { Schema::dropIfExists('rooms'); }
};
