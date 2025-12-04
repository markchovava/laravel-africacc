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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('opportunity_id')->nullable();
            $table->bigInteger('status')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->mediumText('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('investments');
    }
};
