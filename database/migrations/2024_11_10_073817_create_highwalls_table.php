<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('highwalls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type',['2 side','unipool','neon'])->nullable();
            $table->string('brand')->nullable();
            $table->text('location')->nullable();
            $table->text('district')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highwalls');
    }
};
