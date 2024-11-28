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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->double('average_sales')->nullable();
            $table->double('market_size')->nullable();
            $table->double('market_share')->nullable();
            $table->integer('total_outlets')->nullable();
            $table->integer('own_outlets')->nullable();
            $table->string('competition_brand')->nullable();
            $table->double('coverage')->nullable();
            $table->string('location')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('distance')->nullable();
            $table->string('district')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
