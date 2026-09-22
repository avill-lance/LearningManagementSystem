<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->increments('school_year_id');
            $table->string('year', 9)->unique();
            $table->enum('status', ['active', 'closed'])->default('closed');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void { Schema::dropIfExists('school_years'); }
};
