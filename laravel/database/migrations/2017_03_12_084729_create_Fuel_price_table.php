<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_price', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('petrol_95_simple', 4, 3);
            $table->decimal('petrol_95', 4, 3);
            $table->decimal('petrol_98_simple', 4, 3);
            $table->decimal('petrol_98', 4, 3);
            $table->decimal('diesel_simple', 4, 3);
            $table->decimal('diesel', 4, 3);
            $table->decimal('gpl', 4, 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fuel_price');
    }
}
