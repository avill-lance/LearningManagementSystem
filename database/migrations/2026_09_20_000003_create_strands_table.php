<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strands', function (Blueprint $table) {
            $table->increments('strand_id');
            $table->unsignedInteger('track_id');
            $table->string('strand_code', 20)->unique();
            $table->string('strand_name', 150);
            $table->text('description')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('track_id')->references('track_id')->on('tracks')->cascadeOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('strands'); }
};
