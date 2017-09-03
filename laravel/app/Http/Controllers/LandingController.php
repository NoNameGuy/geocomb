<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\District;
use App\Fuel;
use App\Station;
use File;
use \RecursiveIteratorIterator;
use \RecursiveArrayIterator;



class LandingController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $apiKey = 'AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s';
    private $allStations = array();
    private $fiveClosestStations = array();
    /**
     * Show a list of all of the application's districts.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $districts = array();
        $districtsName = array();

        $brands = array();
        $brandsName = array();

        $coordinates = new \stdClass();

        if(isset($request)){
            $coordinates->latitude = $request->latitude;
            $coordinates->longitude = $request->longitude;

        }else{
            $coordinates->latitude = 39.676944;
            $coordinates->longitude = -8.1425;
        }

        $districts = DB::table('district')->select('name')->distinct()->get();

        for ($i=0; $i < $districts->count(); $i++) {
          array_push($districtsName, $districts[$i]->name);
        }

        $brands = DB::table('station')->select('brand')->distinct()->get();

        for ($i=0; $i < $brands->count(); $i++) {
          array_push($brandsName, $brands[$i]->brand);
        }

        $stations = array();
        $stations = $this->getStations($request->district, $request->brand, $request->fuelType);

        $fuels = Fuel::select('name')
            ->get();

        return View('landing_page', ['district' => $districts, 'districtsName' => $districtsName, 'brandsName' => $brandsName, 'centerMapCoordinates' => $coordinates, 'stations' => $stations, 'fuels' => $fuels]);
    }

    public function combDetails()
    {
      # code...
      return View('details');
    }

    private function getStations($district, $brand, $fuelType)
    {
        $stations=null;
        if($district!=null && $brand!='all' && $fuelType!=null){
          $stations = Station::join('district', 'station.district', 'district.id')
              ->join('fuel_price', 'station.fuel_price', 'fuel_price.id')
              ->join('location', 'station.location', 'location.id')
              ->where('district.name','like', "%$district%")
              ->where('station.brand','like', "%$brand%")
              ->where("fuel_price.$fuelType",'!=', null)
              ->orderBy("fuel_price.$fuelType", "asc")
              ->select('station.name as stationName', "station.brand as stationBrand", "district.name as districtName", "fuel_price.$fuelType as fuelPrice", "latitude", "longitude", "$fuelType as fuelType")
              ->take(5)
              ->get();
        }
        if($district!=null && $brand=='all' && $fuelType!=null){
          $stations = Station::join('district', 'station.district', 'district.id')
              ->join('fuel_price', 'station.fuel_price', 'fuel_price.id')
              ->join('location', 'station.location', 'location.id')
              //->join('services', 'station.services', 'services.id')
              ->where('district.name','like', "%$district%")
              ->where("fuel_price.$fuelType",'!=', null)
              ->orderBy("fuel_price.$fuelType", "asc")
              ->select('station.name as stationName', "station.brand as stationBrand", "district.name as districtName", "fuel_price.$fuelType as fuelPrice", "latitude", "longitude", "$fuelType as fuelType")
              ->take(5)
              ->get();
        }
        return $stations;

    }

    private function getCoordinatesByPlace($address)
    {
        $address = str_replace(" ", "+", $address);#google maps api uses plus instead of spaces

        $link = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$this->apiKey";

        $client = new GuzzleHttp\Client();
        $json = $client->get($link)->getBody();

        $obj = json_decode($json); //converts json to object
        #echo $obj->results[0]->geometry->location->lat;

        $coordinates['latitude'] = $obj->results[0]->geometry->location->lat;
        $coordinates['longitude'] = $obj->results[0]->geometry->location->lng;
        return $coordinates;
    }

    private function fetchAllStations(){

        $resultArray = array();
        $client = new Client();

        for($i=1;$i<18;$i++)
        {
            if($i<10)
            {
                $result = $client->request('GET', 'http://www.precoscombustiveis.dgeg.pt/Mapas/postosPTD0'.$i.'.js', [
    //                'auth' => ['user', 'pass']
                ]);
            }else{
                $result = $client->request('GET', 'http://www.precoscombustiveis.dgeg.pt/Mapas/postosPTD'.$i.'.js', [
    //                'auth' => ['user', 'pass']
                ]);
            }

            $item = $result->getBody();

            array_push($resultArray, $item);
        }

        $stationsInCSV = $this->convertPageToCsv($resultArray);
        #print_r( $stationsInCSV);

        for($i=0; $i<count($stationsInCSV); $i++)
        {
            //echo "$stationsInCSV[$i][0]";
            #$resultArray[$i] = str_getcsv($stationsInCSV[$i], ";");
            /*$string=implode("",$resultArray[$i]);
            $newArray = explode(",", $string);
            print_r($newArray);*/

            #print_r($stationsInCSV[0]);
            $lines = explode(";", $stationsInCSV[$i]);
            #$lines = explode("\n", $stationsInCSV[$i]);
            $array = array();

            foreach ($lines as $line) {
                #$data = array_map("str_getcsv", preg_split('/\r\n+|\n+/', $line));
                #print_r($data);
                $array = str_getcsv($line, ",", "\n");
                #print_r( $array);

                if(isset($array[0]) && isset($array[2]) && isset($array[3])){
                    $station = array('id' => "$array[0]", 'latitude' => "$array[2]", 'longitude' => "$array[3]");
                    #echo "ID: ".$station['id']." STATION LATITUDE: ".$station['latitude']." STATION LONGITUDE: ".$station['longitude'];
                    array_push( $this->allStations, $station);

                }

            }

        }

    }


    private function searchStations($latitudeOrigin, $longitudeOrigin, $radius=5)
    {
 //para todos os postos calcular se a distancia é igual ou menor ao raio
        //se for guardar verificar se é menor que a que esta no array
        //se for menor que a que esta no array guardar

/*$tempStationsArray = $this->allStations;
foreach ($tempStationsArray as $key => $value) {

    $tempStationsArray[$key]["distance"] = $this->calculateDistance($latitudeOrigin, $longitudeOrigin, $value["latitude"], $value["longitude"]);
}*/

        /*foreach ($this->allStations as $key => $value) {
            #echo $value['latitude'].$value['longitude'];
            $latitudeDestination = $value['latitude'];
            $longitudeDestination = $value['longitude'];

            //$this->allStations[$key]["distance"] = $this->calculateDistance($latitudeOrigin, $longitudeOrigin, $latitudeDestination, $longitudeDestination);




            /*if ($stationDistance <= $radius) {
                echo "menor que o raio";
                if (count($this->fiveClosestStations)<5) {
                    echo "menor que 5";
                    array_push($this->fiveClosestStations, $value);
                    //var_dump($this->fiveClosestStations);
                } else {
                    echo "maior que zero";
                    foreach ($this->fiveClosestStations as $val) {
                        //var_dump($val);
                        if ($this->calculateDistance($latitudeOrigin, $longitudeOrigin, $val['latitude'], $val['longitude'])<=$val){
                            //array_splice($this->fiveClosestStations, $key, $key, $value);

                        }
                       //$distance = $this->calculateDistance($latitudeOrigin, $longitudeOrigin, $val['latitude'], $val['longitude']);
                    }
                }

            }else {
                echo "maior que o raio";
            }*/
            #echo "distance: ".$distance;
            #print_r($value);
        //}*/
        /*$tempAllStations = $this->allStations;
asort($tempAllStations);
var_dump($tempAllStations);*/

        //var_dump($this->allStations);

        /*foreach ($this->fiveClosestStations as $k) {
            echo "$k ";
        }*/
    }

    private function calculateDistance($latitudeOrigin, $longitudeOrigin, $latitudeDestination, $longitudeDestination)
    {
        $earthRadius = 6371;//km

        $latitudeDifference = $latitudeOrigin-$latitudeDestination;
        $longitudeDifference = $longitudeOrigin-$longitudeDestination;

        $a = pow(sin($latitudeDifference/2),2) + cos($latitudeOrigin) * cos($latitudeDestination) * pow(sin($longitudeDifference/2), 2);
        $c = 2 * $a * pow(tan(sqrt($a)*sqrt(1-$a)) ,2);
        return $earthRadius * $c;
    }

    private function retrieveData($csv)
    {
       // $array = str_getcsv($csv, ';');
        //var_dump($array);
        $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+|\n+|;/', $csv));
        array_pop($data);
       // print_r( $data[0]);
        foreach($data as $value){
           # $station['id'] = $value[0];
            #$station['latitude'] = $value[2];
            #$station['longitude'] = $value[3];
            printf("Posto: %s, Latitude: %s, Longitude: %s, Image: %s <br>", $value[0], $value[2], $value[3], $value[4] );
            #array_push($this->allStations, $station);
        }

    }

    private function convertPageToCsv($stationData)
    {

        $stationData = str_replace(";","\n",$stationData); #substitui o ; por paragrafo
        $stationData = preg_replace("/'/", "", $stationData);
        $stationData = preg_replace("/fAD[0-9][0-9]\(/","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("function","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("{","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("fAdicionaPontoGM(","",$stationData); #remove as funcoes javascript
        $stationData = str_replace(",urlImagens+",", ",$stationData); #remove as funcoes javascript
        $stationData = str_replace(")","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("}","",$stationData); #remove as funcoes javascript

        return $stationData;
    }

    public function fetchStationData()
    {
      $stationsIds = $this->fetchStationID();
      $number=0;
      $numberOfStations = sizeof($this->fetchStationID());

/*
      while ($number < $numberOfStations) {

        if ($number % 9 == 0) {
          # code...
          sleep(60);
        }
        foreach ($this->fetchStationID() as $value) {
*/
          $link = 'http://www.precoscombustiveis.dgeg.pt/wwwbase/raiz/mlkListagemCallback_v11.aspx?linha=181911&fi=7745&geradorid=5372&nivel=2&codigoms=0&codigono=62796281AAAAAAAAAAAAAAAA';
          #$link = 'http://www.precoscombustiveis.dgeg.pt/wwwbase/raiz/mlkListagemCallback_v11.aspx?linha='.$value.'&fi=7745&geradorid=5372&nivel=2&codigoms=0&codigono=62796281AAAAAAAAAAAAAAAA';
          $guzzle = new GuzzleHttp\Client();

          $request = $guzzle->request('GET', $link);

          $crawler = new Crawler((string) $request->getBody());

          $result = $crawler->filter('div .infoPrecos.none')->html();
          $result2 = $crawler->filter('div .infoPostos.invisivel')->html();
          $result3 = $crawler->filter('mlk')->html();
          $string = strip_tags($result);
          $diesel = substr( $string, 0, strlen($result)-mb_stripos($string, 9, 0) );

          echo $diesel;
/*

        }
        $number++;
      }
*/

    }



    public function mapsApi()
    {
        $apiKey = 'AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s';
        $latitude = '40.508489';
        $longitude = '-8.668739';
        $iframe = '<iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key='.$apiKey.' &q='.$latitude.', '.$longitude.'" allowfullscreen> </iframe>';
        echo $iframe;

        $link = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=39.7360689,-8.8473024&destinations=40.508489,-8.668739&key=$apiKey";

        $client = new GuzzleHttp\Client();
        $json = $client->get($link)->getBody();

        echo "$json<br><br>";


        $obj = json_decode($json); //converts json to object
        echo $obj->rows[0]->elements[0]->distance->text;
    }

    public function fetchStationID()
    {
      $html = array("../public/files/Gasoleo.html", "../public/files/Gasoleo98.html", "../public/files/GasoleoColorido.html", "../public/files/GasoleoEspecial.html",
        "/public/files/gasoleoSimples.html", "../public/files/Gasolina95.html", "../public/files/GasolinaEspecial95.html", "../public/files/GasolinaEspecial98.html",
        "../public/files/GasolinaSimples95.html", "../public/files/GNCkg.html", "../public/files/GNC.m3.html", "../public/files/GNL.html", "../public/files/GPLAuto.html");

$uniqueMatch1 = array();

        foreach ($html as $value) {

          $doc = new \DOMDocument();
          libxml_use_internal_errors(true);


          $doc->loadHTMLFile($value); // loads your HTML

          $xpath = new \DOMXPath($doc);

          $classname = "divIcon";
          $result = $xpath->query("//*[contains(@class, '$classname')]");


            foreach ($result as $i => $stationID) {
              $htmlString = $doc->saveHTML($result->item($i));
              $stringCode = htmlentities($htmlString);
              #echo $stringCode;
              $stationID = preg_match("/\d{6}/", $stringCode, $matches); #ENCONTRA TODOS OS NUMEROS DE ID COM 6 DIGITOS!

                            #print_r($uniqueMatch1);
                            array_push($uniqueMatch1, $matches[0]);
            }

          }
          $matches = array_unique($uniqueMatch1);
          #return $matches;
          asort($matches);

        #print_r($matches);
        return $matches;
    }

//API
    public function apiDistricts() {
        try{
            $statusCode = 200;
            $response['districts'] = array();

            $districts = District::all();
            foreach($districts as $district){

                array_push($response['districts'],

                    $district->name
                );
            }

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return Response::json($response, $statusCode);
        }
    }
    public function apiBrands() {
        try{
            $statusCode = 200;
            $response['brands'] = array();

            $brands = Station::select('brand')->distinct()->get();
            foreach($brands as $brand){

                array_push($response['brands'],

                    $brand->brand
                );
            }

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return Response::json($response, $statusCode);
        }
    }

    public function apiStations($district, $brand, $fuelType) {
        try{
            $statusCode = 200;
            $response['stations'] = array();
            $stations = $this->getStations($district, $brand, $fuelType);

            foreach($stations as $station){
              //echo "$station";
                $data =["stationName" => $station->stationName,
                "stationBrand" => $station->stationBrand,
                "districtName" => $station->districtName,
                "latitude" => $station->latitude,
                "longitude" => $station->longitude
              ];
                array_push($response["stations"], $data /*[
                    'id' => $district->id,*/
                    //'name' =>
                //]
                );
            }
            //dd($response);

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return Response::json($response, $statusCode);
        }
    }

    public function getJsonOpenMaps()
    {
      $file = File::get('../public/files/export.json');
      $contents = file_get_contents('../public/files/export.json',true);

      $json = json_decode($contents, true);

      print_r( $json['elements'][0]);

    }

}
