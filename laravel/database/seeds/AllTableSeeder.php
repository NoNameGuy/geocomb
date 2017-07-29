<?php

use Illuminate\Database\Seeder;
use App\Station;
use App\Location;
use App\FuelPrice;
use App\District;

class AllTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $all = array();
      array_push($all, file_get_contents('public/files/Postos-Alves-Bandeira.geojson'));
      array_push($all, file_get_contents('public/files/Postos-BP.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Cepsa.geojson'));
      array_push($all, file_get_contents('public/files/Postos-E.Leclerc.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Ecobrent.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Galp.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Intermarche.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Jumbo.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Outras-Marcas.geojson'));
      array_push($all, file_get_contents('public/files/Postos-OZ-Energia.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Pingo-Doce.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Prio.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Rede-Energia.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Repsol.geojson'));
      array_push($all, file_get_contents('public/files/Postos-Total.geojson'));


$counter = 0;
      foreach ($all as $station) {
        $object = json_decode($station);


        foreach($object->features as $feature){
          $counter++;
          $nameBrand = $feature->properties->Name;
          $description = $feature->properties->description;



          $descriptionString = html_entity_decode($description, ENT_QUOTES);
          $descriptionString = preg_replace("/(<div[^>]*>)(.*?)(<\/div>)/i", '$2', $descriptionString);

          // Convert HTML entities to characters
          // Remove characters other than the specified list.
          /*FUEL TYPES*/
          preg_match_all('/(title=")(.*?)(")/', $descriptionString, $fuel);
          //print_r($fuel[2]);

          /*PRICES*/
          $fuelPriceArray = array();//main array
          $fuelKeys = array();//fuel => price array
          /*foreach($fuel[2] as $key => $value){

            echo $value;
          }
          array_push($fuelPriceArray, $fuelKeys);

*/
          preg_match_all('/\d.\d\d\d+/', $descriptionString, $priceMatches);

          $fuelPriceArray = $this->array_combine2($fuel[2], $priceMatches[0]);

          $fuelPrices = array("petrol_95_simple" => 0, "petrol_95" => 0, "petrol_98_simple" => 0, "petrol_98" => 0,"diesel_simple" => 0, "diesel" => 0, "gpl" => 0);
          foreach($fuelPriceArray as $key => $value){
            //echo "$key - $value\n";
            if($key == "Gasolina 95 Simples")
              $fuelPrices["petrol_95_simple"] = $value;
            if($key == "Gasolina 95 +")
              $fuelPrices["petrol_95"] = $value;
            if($key == "Gasolina 98 Simples")
              $fuelPrices["petrol_98_simple"] = $value;
            if($key == "Gasolina 98 +")
              $fuelPrices["petrol_98"] = $value;
            if($key == "Gasóleo Simples")
              $fuelPrices["diesel_simple"] = $value;
            if($key == "Gasóleo +")
              $fuelPrices["diesel"] = $value;
            if($key == "GPL Auto")
              $fuelPrices["gpl"] = $value;
          }

          $priceId = FuelPrice::insertGetId($fuelPrices);


/*
          foreach($priceMatches[0] as $key => $value){

            echo $value;
          }*/

          /*Coordinates and Insert Coordinates*/
          $latitude = $feature->geometry->coordinates[1];
          $longitude = $feature->geometry->coordinates[0];
          $locationId = Location::insertGetId(['latitude' => $latitude, 'longitude' => $longitude]);

          //insert district
          $districtId = $this->associateDistrict($locationId);

          //Name and Brand
          preg_match_all('/()(.*?)( - )/', $nameBrand, $brand);
          preg_match_all('/( - )(.*?)($)/', $nameBrand, $name);

          //Insert Station
          if(isset($name[2][0]) && isset($brand[2][0]))
            Station::insert(['name' => $name[2][0], 'brand' => $brand[2][0], 'location' => $locationId, 'district' => $districtId,'fuel_price' => $priceId, 'services' => 0, 'last_update' => new DateTime("now"),'schedule' => 0]);










        }
      }
      //echo $obj->features;
      /*for($i = 0; $i < 15; $i++){

      }*/
      //for ($i = 0; $i < DB::table('location')->count(); $i++) {


       /*$brand = $this->randomBrand();
       $location = $i+1;//$this->generateRandomNumber(1,10);
       $district = $this->associateDistrict($i+1);
       $fuel_price = $this->generateRandomNumber(1,10);
       $services = $this->generateRandomNumber(1,10);
       $schedule = $this->generateRandomNumber(1,10);


         DB::table('station')->insert([
           'name' => "a$i",
           'brand' => $brand,
           'location' => $location,
           'district' => $district,
           'fuel_price' => $fuel_price,
           'services' => $services,
           'last_update' => date("Y-m-d H:i:s", strtotime("12/04/2017 19:00")),
           'schedule' => $schedule
         ]);*/

      //}
    }

    private function array_combine2($arr1, $arr2) {
      $count = min(count($arr1), count($arr2));
      return array_combine(array_slice($arr1, 0, $count), array_slice($arr2, 0, $count));
    }

    public function associateDistrict($id)
    {
      $districts = array("Aveiro", "Beja", "Braga", "Bragança", "Castelo Branco", "Coimbra", "Évora", "Faro", "Guarda", "Leiria", "Lisboa", "Portalegre", "Porto", "Santarém", "Setúbal", "Viana do Castelo", "Vila Real", "Viseu");
      $location = Location::where('id', $id)->first();
      $link = "http://maps.google.com/maps/api/geocode/json?address=$location->latitude,$location->longitude";
      $data = file_get_contents($link);
      $json = json_decode($data, true);
      $districtString = "";
        foreach ($districts as $district) {

          for($i=0; $i<7;$i++){//6 address components from google api
            
            if(isset($json['results'][0]['address_components'][$i]['long_name'])){
              if(strcmp($json['results'][0]['address_components'][$i]['long_name'], $district)!==0){
                continue;
              }else{
                $districtString = $json['results'][0]['address_components'][$i]['long_name'];
              }
            }
          }
        }
          $districtName = trim(str_replace('district', '', $districtString)); //Distrito em texto

          if($districtName=='Lisbon'){
            $district = District::where('name', 'like', "Lisboa")->first();
          }else{
            $district = District::where('name', 'like', "%$districtName%")->first();
          }
          if(isset($district)){
            return $district->id;
          }else{
            $districtId = District::insertGetId(['name'=>$districtName]);
            return $districtId;
          }
    //  return 1;
    }
}
