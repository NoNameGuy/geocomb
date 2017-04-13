<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
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
    			$booleanArray[$j] = $this->generateRandomBoolean();
    		}

            DB::table('Services')->insert([ 
            'atm' => $booleanArray[0],
            'wc' => $booleanArray[1],
            'carwash' => $booleanArray[2],
            'shop' => $booleanArray[3],
            'disabled_person' => $booleanArray[4]
            ]);


        }
    }
    private generateRandomBoolean()
    {
    	rand(0,1) == 1? return true: return false;
    }
}
