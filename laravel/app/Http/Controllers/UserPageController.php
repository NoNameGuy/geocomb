<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Vehicle;
use App\Vehicles;
use App\User;
use App\Station;

use Validator;
use Hash;
use Illuminate\Support\Facades\Response;

class UserPageController extends Controller
{

    private $apiKey = 'AIzaSyDsZDCiU1k6mSuywRRL88xxXY-81RMEU7s';
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

    	return view('planRoute', ['name'=>$user->name, 'vehicles' => $vehicles, 'vehicleData' => $vehicleData]);
    }

    public function add(Request $request)
    {

    	$data = ['brand' => $request->brand, 'model' => $request->model, 'fuel' => $request->fuel, 'consumption' => $request->consumption];

        Vehicle::insert($data);

        $vehicle = Vehicle::orderBy('id', 'desc')->first();
        $vehicles = ['user_id'=>Auth::user()->id, 'vehicle_id'=>$vehicle->id];
        Vehicles::insert($vehicles);


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

      $selectedVehicle=Vehicle::where('id', $id)->first();

      $preferredVehicle = User::where('id', Auth::user()->id)->get();
      $fuelTypes = DB::select('DESCRIBE fuel_price');

      return view('manageVehicles', ['name'=>$user->name, 'selectedVehicle' => $selectedVehicle, 'vehicles' => $vehicles, 'fuelTypes'=>$fuelTypes]);

    }

    public function editInfo()
    {
      # code...
      $user = Auth::user();


      return view('manageInfo', 'user'->$user);
    }

    public function saveInfo(Request $request, $id)
    {
      # code...
      $data = ['name'=>$request->name, 'email'=>$request->email];
      User::where('id', $id)->update($data);
      return redirect(route('manageInfo'));

    }

    public function saveVehicle(Request $request, $id)
    {
      //$id2 = ['id'=>$request->id];
      $data = ['brand'=>$request->brand, 'model'=>$request->model, 'fuel'=>$request->fuel, 'consumption'=>$request->consumption];
      Vehicle::where('id', $id)->update($data);

      //dd($id2);
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
              return "ok";
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
                ->join('location', 'station.district', 'location.id');

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
                $earthRadius = 6.371;//km

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
                array_push($response["stations"], $array /*[
                    'id' => $district->id,*/
                    //'name' =>
                //]
                );
              }


          }catch (Exception $e){
              $statusCode = 400;
          }finally{
              return Response::json($response, $statusCode);
          }
      }

      public function getVehicles()
      {
        # code...
        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->get();

        $fuelTypes = DB::select('DESCRIBE fuel_price');

        return view('manageVehicles', ['name'=>$user->name, 'vehicles' => $vehicles,'fuelTypes' => $fuelTypes]);

      }

      public function getInfo()
      {
        # code...
        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->get();


          return view('manageInfo', ['name'=>$user->name, 'vehicles' => $vehicles]);

      }

}
