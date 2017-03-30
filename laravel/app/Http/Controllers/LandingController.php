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

#use JonnyW\PhantomJs\Client;

use App\District;

class LandingController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $apiKey = 'AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s';
    private $allStations = array();
    private $fiveClosestStations = array();
    private $districts = ['Aveiro', 'Beja', 'Braga', 'Bragança', 'Castelo Branco', 'Coimbra', 'Évora', 'Faro', 'Guarda', 'Leiria', 'Lisboa', 'Portalegre', 'Porto', 'Santarém', 'Setúbal', 'Viana do Castelo', 'Vila Real', 'Viseu'];
    /**
     * Show a list of all of the application's districts.
     *
     * @return Response
     */
    public function index()
    {
        $districts = array();
        
        $districts = DB::table('District')->orderBy('name')->get();

        
        #$this->askLocation();

        
        if (array_key_exists('location', $_POST)) {
            $this->fetchAllStations();
            $coordinates = array();
            $coordinates = $this->getCoordinatesByPlace(addslashes($_POST['location']));
            if ( array_key_exists('radius', $_POST)) {
                $this->searchStations($coordinates["latitude"], $coordinates["longitude"], $_POST['radius']);
            } else {
                $this->searchStations($coordinates["latitude"], $coordinates["longitude"]);
            }
            
            #print_r($coordinates);
            
            
            #$this->searchStations($coordinates["latitude"], $coordinates["longitude"]);
        }

        $this->fetchStationData();

        return View('landing_page', ['districts' => $districts]);
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

            $lines = explode("\n", $stationsInCSV[$i]);
            $array = array();
            
            foreach ($lines as $line) {
                $array = str_getcsv($line, ",", "\n");
                #print_r( $array);

                if(isset($array[0]) && isset($array[2]) && isset($array[3])){
                    $station = array('id' => "$array[0]", 'latitude' => "$array[2]", 'longitude' => "$array[3]");
                    #echo "ID: ".$station['id']." STATION LATITUDE: ".$station['latitude']." STATION LONGITUDE: ".$station['longitude'];
                    array_push( $this->allStations, $station);

                }
                
            }

            
           
            
            /*foreach($this->allStations as $key=>$value){
                echo $key;
            }*/
            #print_r($this->allStations);
        }

    }

    private function searchStations($latitudeOrigin, $longitudeOrigin, $radius=5)
    {
        
        foreach ($this->allStations as $value) {
            #print_r($value);
        }
    }

    private function askLocation()
    {
        //echo var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR'])));

        $location ='<script>
                         window.onload = getLocation;
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
function showPosition(position) {
    document.getElementById("latitude").value =position.coords.latitude;
    document.getElementById("longitude").value =position.coords.longitude;
}
</script>
 <form id = "geolocation" action="/maps" method="POST" >
           <!-- {% csrf_token %}-->
            <meta name="csrf-token" content="{{ csrf_token() }}">
        <input type="text" id = "latitude" name="location" value="" />
        <input type="text" id = "longitude" name="location" value="" />
        <input type="submit" />

</form>';
        #echo "$location";
    }

    public function fetchData()
    {
        $client = new Client(); //GuzzleHttp\Client
        $resultArray = array();
/*
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
        }*/
        $result = $client->request('GET', 'http://www.precoscombustiveis.dgeg.pt/Mapas/postosPTD01.js', [
    //                'auth' => ['user', 'pass']
                ]);
        $item = $result->getBody();
        array_push($resultArray, $item);
        foreach ($resultArray as $key => $value)
        {
            $csv = $this->convertPageToCsv($value);
            $this->retrieveData($csv);
            //echo "$csv";
        }

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
        
        $stationData = preg_replace("/fAD[0-9][0-9]\(/","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("function","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("{","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("fAdicionaPontoGM(","",$stationData); #remove as funcoes javascript
        $stationData = str_replace(",urlImagens+",", ",$stationData); #remove as funcoes javascript
        $stationData = str_replace(")","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("}","",$stationData); #remove as funcoes javascript

        return $stationData;
    }

    public function fetchStationData($stationId=165954)
    {
        #$link = "http://www.precoscombustiveis.dgeg.pt/wwwbase/raiz/mlkListagemCallback_v11.aspx?linha=$stationId&fi=7745&geradorid=5372&nivel=2&codigoms=0&codigono=62796281AAAAAAAAAAAAAAAA"; 
        $link = "http://www.precoscombustiveis.dgeg.pt/wwwbase/raiz/mlkListagemCallback_v11.aspx?linha=$stationId&fi=7745&geradorid=5372&nivel=2&codigoms=0&codigono=62796281AAAAAAAAAAAAAAAA";
        
        #$page = $client->get($link)->getBody();
        #echo $page->getContents();
        $guzzle = new GuzzleHttp\Client();
        $request = $guzzle->request('GET', $link);
        $crawler = new Crawler((string) $request->getBody());
        $result = $crawler->filter('div .esq ')->html();
        $simpleDieselPrice = substr($result, 28, 28);
        $simpleDieselPrice = substr($simpleDieselPrice, 0, 5);

     echo "Diesel simples: ".$simpleDieselPrice;
    }

    public function receiveGPSCoordinates()
    {
        echo '<script> alert("Latitude: "+ latitude+" Longitude: "+longitude );</script>';
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

}
