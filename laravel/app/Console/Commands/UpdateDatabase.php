<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use Storage;
use SimpleXMLElement;
use App\Station;
use App\Location;
use App\FuelPrice;
use App\District;
use App\User;
use DateTime;

class UpdateDatabase extends Command
{
   private $keys = array("AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s", "AIzaSyAHkz73a9x-GmDi6-TrRKdmnrPEHZE2SFs", "AIzaSyAlJB4WvHqzbe2lmydazxqQsho3aqPSVQw", "AIzaSyDs4W6IghtD3Wom6EZ_nxM934CR3vLqtTs");
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateDatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update database with cron job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getAndUnzipFile();
    }

    private function getAndUnzipFile(){
      //Get and save file
      $earthKmz = file_get_contents("http://static.maisgasolina.com/kml/earth-all.kmz");
      Storage::disk('public')->put('earth-all.kmz', $earthKmz);

      $zip = new ZipArchive;
      $res = $zip->open(public_path().'/files/earth-all.kmz');
      if ($res === TRUE) {
        $zip->extractTo(public_path().'/files/');
        $zip->close();
        echo 'zip extraction successful';
        $kml = file_get_contents(public_path().'/files/earth-all.kml');
        $data = $this->removeCdata($kml);

        //$data = $this->xmlToJSON($data);

        /*$fp = fopen(public_path().'/files/asdf.txt', 'w+');
        fwrite($fp, print_r($data, TRUE));
        fclose($fp);*/
        $this->insertIntoDb($data);

      } else {
        echo 'failed, code:' . $res;
      }
    }

    private function removeCdata($data)
    {
      $xml_output = preg_replace('/<!\[CDATA\[/', '', $data);
      $xml_output = preg_replace('/\]\]>/', '', $xml_output);
      $xml_output = preg_replace('/;>"/', ';">', $xml_output);
      $xml_output = preg_replace('/&/', '&amp;', $xml_output);
      return $xml_output;
    }

    private function xmlToJSON($xml_string){
      $xml = simplexml_load_string($xml_string);
      $json = json_encode($xml);
      //return $json;
      return json_decode($json,TRUE);
    }

    private function insertIntoDb($data){
    /*  $fp = fopen(public_path().'/files/json.txt', 'w');
      fwrite($fp, print_r($data, TRUE));
      fclose($fp);*/


      $stations = new SimpleXMLElement($data);
      
      for($i=0; $i<$stations->Document->Folder->count(); $i++){

        if (isset($stations->Document->Folder[$i]->Placemark)) {
            $fuelPrices = array("petrol_95_simple" => null, "petrol_95" => null, "petrol_98_simple" => null, "petrol_98" => null,"diesel_simple" => null, "diesel" => null, "gpl" => null);
          for($j=0; $j<$stations->Document->Folder[$i]->Placemark->count(); $j++){

            //Name and brand
            $nameBrand = $stations->Document->Folder[$i]->Placemark[$j]->name;
            $brand = trim(substr($nameBrand, 0, strpos($nameBrand, '-')));
            $name = trim(substr($nameBrand, strpos($nameBrand, '-')+1, strlen($nameBrand)));
          


            //Insert Coordinates
            $coordinates = $stations->Document->Folder[$i]->Placemark[$j]->Point->coordinates;
            $latitude = substr( $coordinates, strpos( $coordinates, ',')+1 ,strlen($coordinates));
            $longitude = substr( $coordinates, 0, strpos( $coordinates, ','));
            $locationId = Location::insertGetId(['latitude' => $latitude, 'longitude' => $longitude]);
            
            $districtId = $this->associateDistrict($locationId);

            for($k=0; $k<$stations->Document->Folder[$i]->Placemark[$j]->description->div->div->count(); $k++){
              $priceString = $stations->Document->Folder[$i]->Placemark[$j]->description->div->div[$k];
              if(!is_null($priceString)){
                $descriptionString = $stations->Document->Folder[$i]->Placemark[$j]->description->div->div[$k]->img["title"];
                echo "$descriptionString - $priceString;\n";

                preg_match_all('/\d.\d\d\d+/', $priceString, $price);



                  if($descriptionString == "Gasolina 95 Simples")
                    $fuelPrices["petrol_95_simple"] = $price[0][0];
                  if($descriptionString == "Gasolina 95 +")
                    $fuelPrices["petrol_95"] = $price[0][0];
                  if($descriptionString == "Gasolina 98 Simples")
                    $fuelPrices["petrol_98_simple"] = $price[0][0];
                  if($descriptionString == "Gasolina 98 +")
                    $fuelPrices["petrol_98"] = $price[0][0];
                  if($descriptionString == "Gasóleo Simples")
                    $fuelPrices["diesel_simple"] = $price[0][0];
                  if($descriptionString == "Gasóleo +")
                    $fuelPrices["diesel"] = $price[0][0];
                  if($descriptionString == "GPL Auto")
                    $fuelPrices["gpl"] = $price[0][0];

                  
               }
              //print_r($fuelPrices);
             }

            $priceId = FuelPrice::insertGetId($fuelPrices);
            Station::insert(['name' => $name, 'brand' => $brand, 'location' => $locationId, 'district' => $districtId,'fuel_price' => $priceId, 'services' => 0, 'last_update' => new DateTime("now"),'schedule' => 0]);
            }
            
          }
        }
    }

    private function array_combine2($arr1, $arr2) {
    $count = min(count($arr1), count($arr2));
    return array_combine(array_slice($arr1, 0, $count), array_slice($arr2, 0, $count));
    }

    public function associateDistrict($id)
    {
    $temporaryVar = array_shift($this->keys);
    $key=$temporaryVar;
    array_push($this->keys, $temporaryVar);
    $districts = array("Aveiro", "Beja", "Braga", "Bragança", "Castelo Branco", "Coimbra", "Évora", "Faro", "Guarda", "Leiria", "Lisboa", "Portalegre", "Porto", "Santarém", "Setúbal", "Viana do Castelo", "Vila Real", "Viseu");
    $location = Location::where('id', $id)->first();
    for($j=0;$j<2500;$j++){
      if ($j==2499) {
        $temp = array_shift($this->keys);
        $key=$temp;
        array_push($this->keys, $temp);
        $j=0;
      }
      $link = "https://maps.google.com/maps/api/geocode/json?address=$location->latitude,$location->longitude&key=$key";


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
        echo $link;
        if(isset($district)){
          return $district->id;
        }else{
          $districtId = District::insertGetId(['name'=>$districtName]);
          return $districtId;
        }
    //  return 1;
    }}
}
