<?php

use Illuminate\Support\Facades\DB;
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
         for ($i = 0; $i < DB::table('location')->count(); $i++) {
        	//for ($j=0; $j < 5; $j++) {
					$brand = $this->randomBrand();
    			$location = $i+1;//$this->generateRandomNumber(1,10);
    			$district = $this->associateDistrict($i+1);
    			$fuel_price = $this->generateRandomNumber(1,10);
    			$services = $this->generateRandomNumber(1,10);
    			$schedule = $this->generateRandomNumber(1,10);
    		//}

            DB::table('station')->insert([
            	'name' => "a$i",
            	'brand' => $brand,
            	'location' => $location,
            	'district' => $district,
            	'fuel_price' => $fuel_price,
            	'services' => $services,
            	'last_update' => date("Y-m-d H:i:s", strtotime("12/04/2017 19:00")),
            	'schedule' => $schedule
            ]);


        }
    }

    public function associateDistrict($id)
    {
      $location = DB::table('location')->where('id', $id)->first();
      $link = "http://maps.google.com/maps/api/geocode/json?address=$location->latitude,$location->longitude";
      $data = file_get_contents($link);
      $json = json_decode($data, true);
      if(isset($json['results'][0]['address_components'][1]['long_name'])){
          $districtString = $json['results'][0]['address_components'][1]['long_name'];
          $districtName = trim(str_replace('district', '', $districtString)); //Distrito em texto

          if($districtName=='Lisbon'){
            $district = DB::table('district')->where('name', 'like', "Lisboa")->first();
          }else{
            $district = DB::table('district')->where('name', 'like', "%$districtName%")->first();
          }
          if(isset($district)){
            return $district->id;
          }else{
            $districtId = DB::table('district')->insertGetId(['name'=>$districtName]);
            return $districtId;
          }
      }return 1;
    }

    private function generateRandomNumber($min, $max)
    {
    	return ($min + lcg_value()*(abs($max - $min)));
    }

		private function randomBrand()
		{
			# code...
			$var = file_get_contents('public/files/brand.txt'); //Take the contents from the file to the variable
			$result = explode(',',$var); //Split it by ','
			return $result[array_rand($result)]; //Return a random entry from the array.
		}


}
