<?php

use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
    $minLatitude = 36.844;
    $maxLatitude = 42.196;
    $minLongitude = -9.756;
    $maxLongitude = -6.021;

   

        for ($i = 0; $i < 10; $i++) {
            $array = array(['latitude' => $this->randomCoordinate($minLatitude, $maxLatitude),
                'longitude' => $this->randomCoordinate($minLongitude, $maxLongitude)]);
            DB::table('location')->insert($array);
        }
    }

    private function randomCoordinate ($min,$max) {
        return ($min + lcg_value()*(abs($max - $min)));
    }
}
