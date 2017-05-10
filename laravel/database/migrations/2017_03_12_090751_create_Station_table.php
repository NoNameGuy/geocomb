<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('brand');
            $table->integer('location')->unsigned();
            $table->integer('district')->unsigned();
            $table->integer('fuel_price')->unsigned();
            $table->integer('services')->unsigned();
            $table->date('last_update');
            $table->integer('schedule')->unsigned();
        });

        Schema::table('users', function(Blueprint $table){
            $table->foreign('location')->references('id')->on('location');
            $table->foreign('district')->references('id')->on('district');
            $table->foreign('fuel_price')->references('id')->on('fuel_price');
            $table->foreign('services')->references('id')->on('services');
            $table->foreign('schedule')->references('id')->on('schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('station');
    }
}
