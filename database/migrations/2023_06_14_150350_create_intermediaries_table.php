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
        Schema::create('intermediaries', function (Blueprint $table) {
            $table->unsignedBigInteger('intermediary_id')->primary();
            $table->string('webhook', 2048)->nullable();
            $table->foreign('intermediary_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intermediaries');
    }
};
