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
            $table->date('week_opening');
            $table->date('week_closing');
            $table->date('saturday_opening');
            $table->date('saturday_closing');
            $table->date('weekend_opening');
            $table->date('weekend_closing');
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
