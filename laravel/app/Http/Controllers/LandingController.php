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


use App\District;

class LandingController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Show a list of all of the application's districts.
     *
     * @return Response
     */
    public function index()
    {
        $districts = array();
        
        $districts = DB::table('District')->orderBy('name')->get();
        
        return View('landing_page', ['districts' => $districts]);
    }

    public function fetchData()
    {
        $client = new Client(); //GuzzleHttp\Client
        $resultArray = array();

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
        foreach ($resultArray as $key => $value)
        {
            $data = $this->convertPageToCsv($value);
            echo "$data";
        }

    }

    private function convertPageToCsv($stationData)
    {
        
        $stationData = str_replace(";","\r\n",$stationData); #substitui o ; por paragrafo
        $stationData = str_replace("function","",$stationData); #remove as funcoes javascript
        $stationData = preg_replace("/fAD[0-9][0-9]\(/","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("{","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("fAdicionaPontoGM(","",$stationData); #remove as funcoes javascript
        $stationData = str_replace(",urlImagens+",", ",$stationData); #remove as funcoes javascript
        $stationData = str_replace(")","",$stationData); #remove as funcoes javascript
        $stationData = str_replace("}","",$stationData); #remove as funcoes javascript

        return $stationData;
    }

    public function fetchStationData($stationId=165954)
    {
        $link = "http://www.precoscombustiveis.dgeg.pt/paginaImprimir.aspx?tipo=HTML&nppostocombustivel=$stationId";
        $link2 = "http://www.precoscombustiveis.dgeg.pt/paginaImprimir.aspx?tipo=PDF&nppostocombustivel=177901&center=39.84721,-8.6033&zoom=15&maptype=roadmap";

       /* $client = new Client(); //GuzzleHttp\Client
        $resultArray = array();
        $result = $client->request('GET', $link, [
            //                'auth' => ['user', 'pass']
        ]);*/


        $page = htmlentities(file_get_contents($link));

        echo $page;
    }

    public function mapsApi()
    {
        $apiKey = 'AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s';
        $latitude = '40.508489';
        $longitude = '-8.668739';
        $iframe = '<iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key='.$apiKey.' &q='.$latitude.', '.$longitude.'" allowfullscreen> </iframe>';
        echo $iframe;
    }

}
