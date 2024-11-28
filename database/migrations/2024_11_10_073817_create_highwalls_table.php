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
            $table->enum('type',['high_raise','cold_store'])->nullable();
            $table->enum('brand',['fresh_super_cement','dhalai_special_cement','meghnacem_delux_cement'])->nullable();
            $table->text('location')->nullable();
            $table->text('district')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->string('image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
