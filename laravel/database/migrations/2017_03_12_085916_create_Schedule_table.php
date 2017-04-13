<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->time('week_opening');
            $table->time('week_closing');
            $table->time('saturday_opening');
            $table->time('saturday_closing');
            $table->time('sunday_opening');
            $table->time('sunday_closing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Schedule');
    }
}
