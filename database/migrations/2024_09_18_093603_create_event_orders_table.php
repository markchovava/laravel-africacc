<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     */
    public function up(): void
    {
        Schema::create('event_orders', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('user_id')->nullable();
            $table->mediumInteger('event_id')->nullable();
            $table->string('name')->nullable();
            $table->longText('address')->nullable();
            $table->string('company_name')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->nullable();
            $table->integer('is_agree')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('phone')->nullable();
            $table->string('profession')->nullable();
            $table->mediumInteger('joining_fee')->nullable();
            $table->mediumInteger('event_total')->nullable();
            $table->integer('number_of_people')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_orders');
    }
};
