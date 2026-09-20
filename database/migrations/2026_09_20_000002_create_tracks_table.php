<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->increments('track_id');
            $table->string('track_code', 10)->unique();
            $table->string('track_name', 100);
            $table->text('description')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    public function down(): void { Schema::dropIfExists('tracks'); }
};
