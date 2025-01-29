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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('clientId');
            $table->unsignedBigInteger('rewardId');

            $table->foreign('clientId')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('rewardId')->references('id')->on('rewards')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
