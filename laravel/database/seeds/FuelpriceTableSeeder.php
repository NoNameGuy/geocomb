<?php

use Illuminate\Database\Seeder;

class FuelPriceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        for ($i = 0; $i < 10; $i++) {
            for($j=0;$j<13;$j++){
                $pricesArray[$j] = $this->randomPrice(1.12, 1.60);
            }
            DB::table('fuel_price')->insert([ //,
                'diesel' => $pricesArray[0],
                'diesel_colored' => $pricesArray[1],
                'diesel_special' => $pricesArray[2],
                'diesel_simple' => $pricesArray[3],
                'petrol_95' => $pricesArray[4],
                'petrol_98' => $pricesArray[5],
				'petrol_special_95' => $pricesArray[6],
                'petrol_special_98' => $pricesArray[7],
				'petrol_simple_95' => $pricesArray[8],
                'gas_natural_compressed_kg' => $pricesArray[9],
				'gas_natural_compressed_m3' => $pricesArray[10],
                'gas_natural_liquefied' => $pricesArray[11],
				'gpl' => $pricesArray[12]
            ]);


        }
    }

    private function randomPrice ($min,$max) {
        return ($min + lcg_value()*(abs($max - $min)));
    }
}
