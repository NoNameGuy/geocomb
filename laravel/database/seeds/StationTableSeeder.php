<?php

use Illuminate\Database\Seeder;

class StationTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         for ($i = 0; $i < 10; $i++) {
        	for ($j=0; $j < 5; $j++) { 
    			$location = $this->generateRandomNumber(1,10);
    			$district = $this->generateRandomNumber(1,18);
    			$fuel_price = $this->generateRandomNumber(1,10);
    			$services = $this->generateRandomNumber(1,10);
    			$schedule = $this->generateRandomNumber(1,10);
    		}

            DB::table('station')->insert([ 
            	'name' => "a$i",
            	'brand' => "galp",
            	'location' => $location,
            	'district' => $district,
            	'fuel_price' => $fuel_price,
            	'services' => $services,
            	'last_update' => date("Y-m-d H:i:s", strtotime("12/04/2017 19:00")),
            	'schedule' => $schedule
            ]);


        }
    }

    private function generateRandomNumber($min, $max)
    {
    	return ($min + lcg_value()*(abs($max - $min)));
    }

}
