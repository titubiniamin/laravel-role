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
        Schema::create('dealers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('owner_name');
                $table->string('zone')->nullable();
                $table->string('dealer_code')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('website')->unique()->nullable();
                $table->string('mobile')->unique()->nullable();
                $table->text('address')->nullable();
                $table->text('location')->nullable();
                $table->text('longitude')->nullable();
                $table->text('latitude')->nullable();
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
