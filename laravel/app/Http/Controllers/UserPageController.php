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

class UserPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')
            ->join('users', 'users.id', 'vehicles.user_id')
            ->where('users.email', $user->email)
            ->get();

        $planRoute = true;

    	return view('user_page', ['name'=>$user->name, 'vehicles' => $vehicles, 'planRoute' => $planRoute]);
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

        return redirect('userpage');

    }

    public function edit($id)
    {
      $user = Auth::user();
      $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')->join('users', 'users.id', 'vehicles.user_id')->where('users.email', $user->email)->get();
      $vehicle=Vehicle::where('id', $id)->get();
      $preferredVehicle = User::where('id', Auth::user()->id)->get();
      return view('user_page', ['name'=>$user->name, 'vehicle' => $vehicle, 'vehicles' => $vehicles]);

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

}
