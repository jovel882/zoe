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
        Schema::create('security_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('security_id')->unique();
            $table->float('last_price');
            $table->dateTime('as_of_date');
            $table->timestamps();

            $table->foreign('security_id')->references('id')->on('securities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_prices');
    }
};
