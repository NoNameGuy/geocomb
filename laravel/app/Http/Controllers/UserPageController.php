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

        if ($request->points) {
         var_dump($request->points);
        }

    	return view('planRoute', ['name'=>$user->name, 'vehicles' => $vehicles, 'vehicleData' => $vehicleData]);
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
          Fuels::insert(['vehicle_id'=>$vehicleId, 'fuel_id'=>$currentFuel->id]);
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
      $vehicleFuels = Fuel::join('fuels', 'fuels.fuel_id', 'fuel.id')
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
        Fuels::insert(['vehicle_id'=>$id, 'fuel_id'=>$currentFuel->id]);
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


        return view('user_page', ['name'=>$user->name, 'stations' => $data, 'vehicles' => $vehicles]);
      }

      public function apiStations($origin, $destination, $autonomyKm) {

          try{
              $statusCode = 200;
              $response['stations'] = array();


              $linkOrigin = "https://maps.googleapis.com/maps/api/geocode/json?address=$origin&key=$this->apiKey";
              $linkDestination = "https://maps.googleapis.com/maps/api/geocode/json?address=$destination&key=$this->apiKey";
              $arrayOrigin = json_decode(file_get_contents($linkOrigin, true), true);
              $arrayDestination = json_decode(file_get_contents($linkDestination, true), true);

              $originDistrict = $arrayOrigin['results'][0]['address_components'][1]['long_name'];
              $destinationDistrict = $arrayDestination['results'][0]['address_components'][1]['long_name'];

              $latitudeOrigin = $arrayOrigin['results'][0]['geometry']['location']['lat'];
              $longitudeOrigin = $arrayOrigin['results'][0]['geometry']['location']['lng'];

              $originDistrict = trim(str_replace('District', '', $originDistrict));
              $destinationDistrict = trim(str_replace('District', '', $destinationDistrict));

              $query = Station::join('district', 'station.district', 'district.id')
                ->join('location', 'station.location', 'location.id');

                if(strcmp($originDistrict,"Lisbon")==0 && strcmp($destinationDistrict,"Lisbon")!=0){
                  $query->where('district.name', 'like', "%Lisboa%");
                  $query->orWhere('district.name', 'like', "%$destinationDistrict%");
                }else{
                  if (strcmp($originDistrict, "Lisbon")!=0 && strcmp($destinationDistrict, "Lisbon")==0) {
                    $query->where('district.name', 'like', "%$originDistrict%");
                    $query->orWhere('district.name', 'like', "%Lisboa%");
                  }else{
                    if (strcmp($originDistrict, "Lisbon")==0 && strcmp($destinationDistrict, "Lisbon")==0) {
                      $query->where('district.name', 'like', "%Lisboa%");
                    }else{
                      $query->where('district.name', 'like', "%$originDistrict%");
                      $query->orWhere('district.name', 'like', "%$destinationDistrict%");
                    }
                  }
                }
              $query->select("station.id as stationId", "station.name as stationName", "district.name as districtName", "station.brand as brand", "latitude", "longitude");
              $data = $query->get();
              foreach ($data as $key => $value) {
                //echo "$key -> $value->latitude";
                $latitudeDestination = $value->latitude;
                $longitudeDestination = $value->longitude;
                $earthRadius = 6371;//km

                $latitudeDifference = $latitudeOrigin-$latitudeDestination;
                $longitudeDifference = $longitudeOrigin-$longitudeDestination;

                $a = pow(sin($latitudeDifference/2),2) + cos($latitudeOrigin) * cos($latitudeDestination) * pow(sin($longitudeDifference/2), 2);
                $c = 2 * $a * pow(tan(sqrt($a)*sqrt(1-$a)) ,2);
                $result = $earthRadius * $c;
                $data[$key]['distance'] = $result;



                $array =["stationName" => $value->stationName,
                  "stationBrand" => $value->brand,
                  "districtName" => $value->districtName,
                  "latitude" => $value->latitude,
                  "longitude" => $value->longitude
                ];
                array_push($response["stations"], $array );
              }


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
      //  Cache::forget('latitudeOrigin');
      //  Cache::forget('longitudeOrigin');

        Session::put("coordinates", $request->points);
        Session::put("vehicleId", $request->vehicleId);
        Session::put("autonomyKm", $request->distance);
        Session::put("latitudeOrigin",$request->latitudeOrigin);
        Session::put("longitudeOrigin", $request->longitudeOrigin);
        Cache::put('latitudeOrigin', $request->latitudeOrigin, 4);//2 minutes
        Cache::put('longitudeOrigin', $request->longitudeOrigin, 4);
        /*$request->latitude = null;
        $request->longitude = null;*/

        return Response::json(["data"=> $data, "vehicleId"=> vehicleId, $request->vehicleId=> distance, "latitudeOrigin"=> $request->latitudeOrigin, "longitudeOrigin"=> $request->longitudeOrigin]);
      }

      public function receivedCoordinates(Request $request){
        $data = Session::get("coordinates");
        $vehicleData = Session::get("vehicleId");
        $autonomyKmData = Session::get("autonomyKm");
        $latitudeOrigin = Cache::get('latitudeOrigin');//Session::get("latitudeOrigin");
        $longitudeOrigin = Cache::get('longitudeOrigin');//Session::get("longitudeOrigin");

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
                  ->join('fuel', 'fuels.fuel_id', 'fuel.id')
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
              /*    echo "distance: $distance<br>";
                  echo "autonomy data: $autonomyKmData";*/
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
          //  }
        }while($outOfRange==true && $index<count($stationsArray));

          //}
      //  }
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
        //echo round($station2[0]->petrol_95_simple, 3) - round($station1[0]->petrol_95_simple, 3);
          //if (round($station2[0]->petrol_95_simple, 3) < round($station1[0]->petrol_95_simple, 3)) {
          $result = round($station2->petrol_95_simple, 3) - round($station1->petrol_95_simple, 3);
          $result<0?-1:1;

          return $result;
      }
}
