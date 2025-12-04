<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * membership_id
     * 
     */
    public function up(): void
    {
        Schema::create('member_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('member_id')->nullable();
            $table->bigInteger('membership_id')->nullable();
            $table->bigInteger('member_fee')->nullable();
            $table->bigInteger('paid_amount')->nullable();
            $table->string('start_date')->nullable();
            $table->string('duration')->nullable();
            $table->string('end_date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_orders');
    }
};
