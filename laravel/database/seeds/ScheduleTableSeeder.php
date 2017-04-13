<?php

use Illuminate\Database\Seeder;

class ScheduleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
        for ($i = 0; $i < 10; $i++) {
            DB::table('Schedule')->insert([ //,
                'week_opening' => "8:00",
            	'week_closing' => "23:00",
            	'saturday_opening' => "8:00",
            	'saturday_closing' => "23:00",
            	'sunday_opening' => "8:00",
            	'sunday_closing' => "23:00"
            ]);


        }
    }
}
