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
            DB::table('schedule')->insert([ //,
                'week_opening' => date("h:i:s",strtotime("8:00")),#"8:00",
            	'week_closing' => date("h:i:s",strtotime("23:00")),#"23:00",
            	'saturday_opening' => date("h:i:s",strtotime("8:00")),#"8:00",
            	'saturday_closing' => date("h:i:s",strtotime("23:00")),#"23:00",
            	'sunday_opening' => date("h:i:s",strtotime("8:00")),#"8:00",
            	'sunday_closing' => date("h:i:s",strtotime("23:00")),#"23:00"
            ]);


        }
    }
}
