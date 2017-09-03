<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Station;

class DetailsController extends Controller
{
  public function index($id)
  {
      $stationData = Station::join('fuel_price', 'station.fuel_price', 'fuel_price.id')
        ->where('station.id', $id)
        ->first();
      return View('details', ['stationData'=>$stationData]);
  }

  public function apiStation($id){
      $station = Station::join('location', 'station.location', 'location.id')
      ->where('station.id', $id)
      ->select('latitude', 'longitude')
      ->first();
      return Response::json(["station"=> $station]);
  }
}
