<?php

use App\Models\Request;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intermediary_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->unsignedBigInteger('delivery_id')->nullable()->default(null);
            $table->enum('status', [
                Request::STATUS_REGISTERED,
                Request::STATUS_CANCELED,
                Request::STATUS_ACCEPTED,
                Request::STATUS_SENT,
                Request::STATUS_DELIVERED
            ])->default('registered');
            $table->timestamps();

            // foreign keys
            $table->foreign('intermediary_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('delivery_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
