<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Vehicle;
use App\Vehicles;
use App\User;

class UserPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $vehicles = Vehicle::join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')->join('users', 'users.id', 'vehicles.user_id')->where('users.email', $user->email)->get();
        /*$vehicles = DB::table('vehicle')->join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')->join('users', 'users.id', 'vehicles.user_id')->where('users.email', $user->email)->get();
*/
    	return view('user_page', ['name'=>$user->name, 'vehicles' => $vehicles]);
    }

    public function add(Request $request)
    {

    	$data = ['brand' => $request->brand, 'model' => $request->model, 'fuel' => $request->fuel, 'consumption' => $request->consumption];
        //DB::table('vehicle')->insert($data);
        Vehicle::insert($data);

        $vehicle = Vehicle::orderBy('id', 'desc')->first();
        $vehicles = ['user_id'=>Auth::user()->id, 'vehicle_id'=>$vehicle->id];
        Vehicles::insert($vehicles);

        /*$vehicle = DB::table('vehicle')->orderBy('id', 'desc')->first();
        $data2 = ['user_id'=>Auth::user()->id, 'vehicle_id'=>$vehicle->id];
        DB::table('vehicles')->insert($data2);*/
        $vehiclesId = Vehicle::orderBy('id', 'desc')->first();
        if ($request->favoriteVehicle) {
          User::where('id', Auth::user()->id)->update(['preferredVehicle' => $vehiclesId->id]);
        }
        //$vehiclesId = DB::table('vehicle')->orderBy('id', 'desc')->first();
        //DB::table('users')->where('email', Auth::user()->email)->update(['vehiclesid' => $vehiclesId->id]);
        return redirect('userpage');

    }

    public function remove()
    {



    }



}
