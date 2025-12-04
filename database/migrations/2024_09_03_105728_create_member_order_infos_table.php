<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 
     */
    public function up(): void
    {
        Schema::create('member_order_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('member_order_id')->nullable();
            $table->bigInteger('membership_id')->nullable();
            $table->string('name')->nullable();
            $table->string('who_join')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->longText('address')->nullable();
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_order_infos');
    }
};
