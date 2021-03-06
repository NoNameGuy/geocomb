<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

use App\Vehicle;
use App\Vehicles;
use App\User;
use App\Station;
use App\Fuel;
use App\Fuels;

use Illuminate\Support\Facades\Input;
use Redirect;
use Validator;
use Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class UserPageController extends Controller
{

    private $apiKey = 'AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s';
    private $address;
    private $coordinates;
    private $distanceY = 5;
    private $distanceX = 5;
    private $mainFuel;
    private $latitudeOrigin;
    private $longitudeOrigin;
    private $searchingFlag= true;

    public function index(Request $request)
    {

        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->select('vehicle.id as vehicleId', 'brand', 'model')
            ->get();

        $vehicleData = null;
        $vehicleData = Vehicle::where('id', $request->upSelectVehicle)
          ->first();
        Session::put('selectedVehicle', $request->upSelectVehicle);



      //$this->searchingFlag==true?$this->searchingFlag=false:$this->searchingFlag=true;
    	return view('planRoute', ['name'=>$user->name, 'vehicles' => $vehicles, 'vehicleData' => $vehicleData, 'searchingFlag'=>$this->searchingFlag]);
    }



    public function add(Request $request)
    {
    	$data = ['brand' => $request->brand, 'model' => $request->model, 'consumption' => $request->consumption];

        Vehicle::insert($data);

        $vehicle = Vehicle::orderBy('id', 'desc')->first();
        $vehicles = ['user_id'=>Auth::user()->id, 'vehicle_id'=>$vehicle->id];
        $vehicleId = Vehicles::insertGetId($vehicles);


      foreach($request->upFuelType as $fuel){
          $currentFuel = Fuel::where('name', 'like', "$fuel")->select('id')->first();
          Fuels::insert(['vehicle_id'=>$vehicleId, 'fuels_id'=>$currentFuel->id]);
        }


        $vehiclesId = Vehicle::orderBy('id', 'desc')->first();
        if ($request->favoriteVehicle) {
          User::where('id', Auth::user()->id)->update(['preferredVehicle' => $vehiclesId->id]);
        }

        return redirect(route('manageVehicles'));

    }

    public function editVehicle($id=null)
    {

      $user = Auth::user();

      $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
        ->join('users', 'users.id', 'vehicles.user_id')->where('users.email', $user->email)
        ->select('brand','model', 'vehicle.id as vehicle_id')->get();

      $selectedVehicle=Vehicle::where('vehicle.id', $id)
          ->first();
      $vehicleFuels = Fuel::join('fuels', 'fuels.fuels_id', 'fuel.id')
          ->where('fuels.vehicle_id', $id)
          ->select('fuel.name as name')
          ->get();

      $allFuels = Fuel::all();

      $preferredVehicle = User::where('id', Auth::user()->id)->get();
      $fuelTypes = DB::select('DESCRIBE fuel_price');

      return view('manageVehicles', ['name'=>$user->name, 'selectedVehicle' => $selectedVehicle, 'vehicleFuels' => $vehicleFuels, 'allFuels' => $allFuels,'vehicles' => $vehicles, 'fuelTypes'=>$fuelTypes]);

    }

    public function editInfo()
    {
      $user = Auth::user();


      return view('manageInfo', 'user'->$user);
    }

    public function saveInfo(Request $request, $id)
    {
      $data = ['name'=>$request->name, 'email'=>$request->email];
      User::where('id', $id)->update($data);
      return redirect(route('manageInfo'));

    }

    public function saveVehicle(Request $request, $id)
    {
      //$id2 = ['id'=>$request->id];
      $data = ['brand'=>$request->brand, 'model'=>$request->model, 'consumption'=>$request->consumption];
      Vehicle::where('id', $id)->update($data);
      Fuels::where('vehicle_id', $id)->delete();
      foreach($request->upFuelType as $fuel){
        $currentFuel = Fuel::where('name', 'like', "$fuel")->select('id')->first();
        Fuels::insert(['vehicle_id'=>$id, 'fuels_id'=>$currentFuel->id]);
      }
      return redirect(route('manageVehicles'));
    }

    public function removeVehicle($id){
      Vehicles::where('user_id', Auth::user()->id)
        ->where('vehicle_id', $id)
        ->delete();

      return redirect(route('manageVehicles'));
    }

      public function remove(Request $request)
      {
          Auth::user()->vehicles()->where('id', $request->$data)->delete();
      }

      public function admin_credential_rules(array $data)
      {
        $messages = [
          'current-password.required' => 'Please enter current password',
          'password.required' => 'Please enter password',
        ];

        $validator = Validator::make($data, [
          'current-password' => 'required',
          'password' => 'required|same:password',
          'password_confirmation' => 'required|same:password',
        ], $messages);

        return $validator;
      }

      public function postCredentials(Request $request)
      {
        if(Auth::Check())
        {
          $request_data = $request->All();
          $validator = $this->admin_credential_rules($request_data);
          if($validator->fails())
          {
            return response()->json(array('error' => $validator->getMessageBag()->toArray()), 400);
          }
          else
          {
            $current_password = Auth::User()->password;
            if(Hash::check($request_data['current-password'], $current_password))
            {
              $user_id = Auth::User()->id;
              $obj_user = User::find($user_id);
              $obj_user->password = Hash::make($request_data['password']);;
              $obj_user->save();
              return redirect(route('manageInfo'));
              //return "ok";
            }
            else
            {
              $error = array('current-password' => 'Please enter correct current password');
              return response()->json(array('error' => $error), 400);
            }
          }
        }
        else
        {
        return redirect()->to('/');
        }

      }

      public function postTripData(Request $request)
      {
        $autonomyKm = $request->autonomyKm;
        $origin = $request->upOrigin;
        $destination = $request->upDestination;



        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')->join('users', 'users.id', 'vehicles.user_id')->where('users.email', $user->email)->get();

        $this->searchingFlag = $request->searching;
        return view('user_page', ['name'=>$user->name, 'stations' => $data, 'vehicles' => $vehicles, 'searchingFlag'=>$this->searchingFlag]);
      }

      public function receiveStationCoordinates(Request $request) {
        /*Session::forget('autonomyKm');
        Session::forget('latitudeOrigin');
        Session::forget('longitudeOrigin');*/

        Session::put("autonomyKm", $request->distance);
        Session::put("latitudeOrigin",$request->latitudeOrigin);
        Session::put("longitudeOrigin", $request->longitudeOrigin);
        Session::put('latitudeDestination', $request->latitudeDestination);
        Session::put('longitudeDestination', $request->longitudeDestination);
        Session::put('pathPoints', $request->pathPoints);

        return Response::json(["latitudeOrigin"=> $request->latitudeOrigin,
                  "longitudeOrigin"=> $request->longitudeOrigin,
                  "latitudeDestination"=> $request->latitudeDestination,
                  "longitudeDestination"=> $request->longitudeDestination,
                  "autonomy"=> $request->distance,
                  "pathPoints" => $request->pathPoints
                ]);
      }

      public function apiStations(Request $request) {
        $data = Session::get("pathPoints");
        $autonomyKmData = Session::get("autonomyKm");
        $latitudeOrigin = Session::get('latitudeOrigin');
        $longitudeOrigin = Session::get('longitudeOrigin');
        $latitudeDestination = Session::get('latitudeDestination');
        $longitudeDestination = Session::get('longitudeDestination');
        $selectedVehicle = Session::get('selectedVehicle');

        $data = json_encode($data);
        $data = json_decode($data, true);

        $index=0;
        $station = null;
        $stationsArray = [];
        $outOfRange = false;

        foreach ($data as $key => $value) {
          $latitude = $data[$key]["latitude"];
          $longitude = $data[$key]["longitude"];

        //user's vehicle fuel types
            $vehicleFuels = Vehicles::join('vehicle', 'vehicles.vehicle_id', 'vehicle.id')
                  ->join('fuels', 'fuels.vehicle_id', 'vehicle.id')
                  ->join('fuel', 'fuels.fuels_id', 'fuel.id')
                  ->where('vehicles.user_id', '=', Auth::user()->id)
                  ->where('vehicle.id', '=', $selectedVehicle)
                  ->select('fuel.name as fuelName')
                  ->get();
            $stations = null;

            if(empty($stationsArray)){
            //iterate all stations
            //var_dump( $data);
              foreach ($data as $key => $value) {
                $latitude = $data[$key]["latitude"];
                $longitude = $data[$key]["longitude"];


                //formula to calculate near points
                $newLatitudePlus  = $latitude  + ($this->distanceY / 6371) * (180 / pi());
                $newLongitudePlus = $longitude + ($this->distanceX / 6371) * (180 / pi()) / cos($latitude * pi()/180);
                $newLatitudeMinus  = $latitude  + ((-$this->distanceY) / 6371) * (180 / pi());
                $newLongitudeMinus = $longitude + ((-$this->distanceX) / 6371) * (180 / pi()) / cos($latitude * pi()/180);


                //stations near
                $stations = Station::join('location', 'station.location', 'location.id')
                            ->join('fuel_price', 'station.fuel_price', 'fuel_price.id')
                            ->join('district', 'station.district', 'district.id')
                            ->where('location.latitude', '<', $newLatitudePlus)
                            ->where('location.latitude', '>', $newLatitudeMinus)
                            ->where('location.longitude', '<', $newLongitudePlus)
                            ->where('location.longitude', '>', $newLongitudeMinus);

                //filter the stations that have the vehicle's fuel
                foreach ($vehicleFuels as $key => $value) {
                    $stations->whereNotNull("fuel_price.$value->fuelName");
                }

                foreach ($vehicleFuels as $key => $value) {
                    $stations->orderBy("fuel_price.$value->fuelName");
                }
                $stations->select('station.name as stationName', 'station.brand as stationBrand', 'district.name as district', 'latitude', 'longitude', 'petrol_95_simple', 'petrol_95', 'petrol_98_simple', 'petrol_98', 'diesel_simple', 'diesel', 'gpl');
                $stationsResult = $stations->get();

                //Create a stations array
                foreach($stationsResult as $stationResult){
                  //echo $stationResult;
                  array_push($stationsArray, $stationResult);
                }
              }
            }
            }
            try{
                $statusCode = 200;
                $response['stations'] = $stationsArray;

            }catch (Exception $e){
                $statusCode = 400;
            }finally{
                return Response::json($response, $statusCode);
            }


      }

      public function getVehicles()
      {
        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->get();

        $fuelTypes = DB::select('DESCRIBE fuel_price');


        $allFuels = Fuel::all();

        return view('manageVehicles', ['name'=>$user->name, 'vehicles' => $vehicles, 'allFuels'=> $allFuels,'fuelTypes' => $fuelTypes]);

      }

      public function getInfo()
      {
        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->get();


          return view('manageInfo', ['name'=>$user->name, 'vehicles' => $vehicles]);

      }


      public function sendEmailRoute(Request $request){


        Mail::raw('Endereço: <a href="'.$request->link.'" style="background-color:#428bca;border:1px solid #EB7035;border-radius:5px;color:white;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Aceder</a> ', function ($message){
          $message->from('geo.comb.ipl@gmail.com')
            ->to(Auth::user()->email)
            ->subject("A minha rota Geocomb");
        });

        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->select('vehicle.id as vehicleId', 'brand', 'model')
            ->get();

        $vehicleData = null;
        $vehicleData = Vehicle::where('id', $request->upSelectVehicle)
          ->first();
        return Redirect::back()->withSuccess('Message sent!');
      }


      public function receiveCoordinates(Request $request){

        Session::forget('coordinates');
        Session::forget('vehicleId');
        Session::forget('autonomyKm');
        Session::forget('latitudeOrigin');
        Session::forget('longitudeOrigin');

        Session::put("coordinates", $request->points);
        Session::put("vehicleId", $request->vehicleId);
        Session::put("autonomyKm", $request->distance);
        Session::put("latitudeOrigin",$request->latitudeOrigin);
        Session::put("longitudeOrigin", $request->longitudeOrigin);
        Session::put('latitudeOrigin', $request->latitudeOrigin);
        Session::put('longitudeOrigin', $request->longitudeOrigin);

        return Response::json(["data"=> $data, "vehicleId"=> $request->vehicleId, "latitudeOrigin"=> $request->latitudeOrigin, "longitudeOrigin"=> $request->longitudeOrigin]);
      }

      public function receivedCoordinates(Request $request){
        $data = Session::get("coordinates");
        $vehicleData = Session::get("vehicleId");
        $autonomyKmData = Session::get("autonomyKm");
        $latitudeOrigin = Session::get('latitudeOrigin');
        $longitudeOrigin = Session::get('longitudeOrigin');
        Session::forget('latitudeOrigin');
        Session::forget('longitudeOrigin');

        $data = json_encode($data);
        $data = json_decode($data, true);
        $stationsArray = array();
      //  echo "latitude origin $latitudeOrigin";
      //  echo "longitude origin $longitudeOrigin";
        $index=0;
        $station = null;
        $outOfRange = false;
        //echo Auth::user()->id;

        //user's vehicle fuel types
            $vehicleFuels = Vehicles::join('vehicle', 'vehicles.vehicle_id', 'vehicle.id')
                  ->join('fuels', 'fuels.vehicle_id', 'vehicle.id')
                  ->join('fuel', 'fuels.fuels_id', 'fuel.id')
                  ->where('vehicles.user_id', '=', Auth::user()->id)
                  ->where('vehicle.id', '=', $vehicleData)
                  ->select('fuel.name as fuelName')
                  ->get();
    //echo $vehicleFuels;
            $stations = null;

            if(empty($stationsArray)){
            //iterate all stations
            //var_dump( $data);
              foreach ($data as $key => $value) {
                $latitude = $data[$key]["latitude"];
                $longitude = $data[$key]["longitude"];

                /*echo "latitude: ".$latitude."<br>";
                echo $longitude."<br><br>";*/

                //formula to calculate near points
                $newLatitudePlus  = $latitude  + ($this->distanceY / 6371) * (180 / pi());
                $newLongitudePlus = $longitude + ($this->distanceX / 6371) * (180 / pi()) / cos($latitude * pi()/180);
                $newLatitudeMinus  = $latitude  + ((-$this->distanceY) / 6371) * (180 / pi());
                $newLongitudeMinus = $longitude + ((-$this->distanceX) / 6371) * (180 / pi()) / cos($latitude * pi()/180);


                //stations near
                $stations = Station::join('location', 'station.location', 'location.id')
                            ->join('fuel_price', 'station.fuel_price', 'fuel_price.id')
                            ->join('district', 'station.district', 'district.id')
                            ->where('location.latitude', '<', $newLatitudePlus)
                            ->where('location.latitude', '>', $newLatitudeMinus)
                            ->where('location.longitude', '<', $newLongitudePlus)
                            ->where('location.longitude', '>', $newLongitudeMinus);

                //filter the stations that have the vehicle's fuel
                foreach ($vehicleFuels as $key => $value) {
                    $stations->whereNotNull("fuel_price.$value->fuelName");
                }

                foreach ($vehicleFuels as $key => $value) {
                    $stations->orderBy("fuel_price.$value->fuelName");
                }
                $stations->select('station.name as stationName', 'station.brand as stationBrand', 'district.name as district', 'latitude', 'longitude', 'petrol_95_simple', 'petrol_95', 'petrol_98_simple', 'petrol_98', 'diesel_simple', 'diesel', 'gpl');
                $stationsResult = $stations->get();

//echo $stationsResult;
                //Create a stations array
                foreach($stationsResult as $stationResult){
                //  echo $station;
                  array_push($stationsArray, $stationResult);
                }
              }
            }
            $this->mainFuel = $vehicleFuels[0];
            //order array by price
            usort($stationsArray, array($this, "sortByPriceLower"));

            do{
              $station = $stationsArray[$index];

              if ($station!=null) {

                  $latitudeStation = $station->latitude;
                  $longitudeStation = $station->longitude;
              /*    echo "latitude destination: ".$latitudeDestination;
                  echo "longitude destination: ".$longitudeDestination;*/
                  $distance = $this->checkStationDistance($latitudeOrigin, $longitudeOrigin, $latitudeStation, $longitudeStation);

                  if ($distance<$autonomyKmData) {

                    $outOfRange = false;

                    try{
                        $statusCode = 200;
                        $response['station'] = $station ;

                    }catch (Exception $e){
                        $statusCode = 400;
                    }finally{
                        return Response::json($response, $statusCode);
                    }



                  }else{
                    $outOfRange = true;
                    $index++;
                  }
            }
        }while($outOfRange==true && $index<count($stationsArray));

      }

      private function checkStationDistance( $latitudeOrigin, $longitudeOrigin, $latitudeDestination, $longitudeDestination){
        $origin = "$latitudeOrigin,$longitudeOrigin";
        $destination = "$latitudeDestination,$longitudeDestination";
        $link = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$this->apiKey";
        $array = json_decode(file_get_contents($link, true), true);
        $distance = $array['rows'][0]['elements'][0]['distance']['value'];
        $distance = $distance/1000;//m to km
        return $distance;
      }

      public function sortByPriceLower($station1, $station2){
          $fuel =$this->mainFuel->fuelName;
          $result = round($station2->$fuel, 3) - round($station1->$fuel, 3);
          $result<0?-1:1;

          return $result;
      }
}
