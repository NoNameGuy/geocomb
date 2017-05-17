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
        $minLatitudeNorth = 40.543;
        $maxLatitudeNorth = 41.939;
        $minLongitudeNorth = -8.641;
        $maxLongitudeNorth = -6.883;

        $minLatitudeCenter = 38.501;
        $maxLatitudeCenter = 40.543;
        $minLongitudeCenter = -8.789;
        $maxLongitudeCenter = -7.075;

        $minLatitudeSouth = 37.169;
        $maxLatitudeSouth = 38.501;
        $minLongitudeSouth = -8.756;
        $maxLongitudeSouth = -7.075;
   
        $minLatitudeWest = 38.501;
        $maxLatitudeWest = 39.419;
        $minLongitudeWest = -9.305;
        $maxLongitudeWest = -8.756;

        //North
        for ($i = 0; $i < 1000; $i++) {
            $array = array(['latitude' => $this->randomCoordinate($minLatitudeNorth, $maxLatitudeNorth),
                'longitude' => $this->randomCoordinate($minLongitudeNorth, $maxLongitudeNorth)]);
            DB::table('location')->insert($array);
        }

        //Center
        for ($i = 0; $i < 2000; $i++) {
            $array = array(['latitude' => $this->randomCoordinate($minLatitudeCenter, $maxLatitudeCenter),
                'longitude' => $this->randomCoordinate($minLongitudeCenter, $maxLongitudeCenter)]);
            DB::table('location')->insert($array);
        }

        //South
        for ($i = 0; $i < 2000; $i++) {
            $array = array(['latitude' => $this->randomCoordinate($minLatitudeSouth, $maxLatitudeSouth),
                'longitude' => $this->randomCoordinate($minLongitudeSouth, $maxLongitudeSouth)]);
            DB::table('location')->insert($array);
        }

        //West
        for ($i = 0; $i < 2000; $i++) {
            $array = array(['latitude' => $this->randomCoordinate($minLatitudeWest, $maxLatitudeWest),
                'longitude' => $this->randomCoordinate($minLongitudeWest, $maxLongitudeWest)]);
            DB::table('location')->insert($array);
        }
    }

    private function randomCoordinate ($min,$max) {
        return ($min + lcg_value()*(abs($max - $min)));
    }
}
