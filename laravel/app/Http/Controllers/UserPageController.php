<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $vehicles = DB::table('vehicle')->join('vehicles', 'vehicle.id', 'vehicles.vehicle_id')->join('users', 'users.id', 'vehicles.user_id')->where('users.email', $user->email)->get();

    	return view('user_page', ['name'=>$user->name, 'vehicles' => $vehicles]);
    }

    public function add(Request $request)
    {

    	$data = ['brand' => $request->brand, 'model' => $request->model, 'color' => $request->color, 'fuel' => $request->fuel, 'consumption' => $request->consumption];
        DB::table('vehicle')->insert($data);

        $vehicle = DB::table('vehicle')->orderBy('id', 'desc')->first();
        $data2 = ['user_id'=>Auth::user()->id, 'vehicle_id'=>$vehicle->id];
        DB::table('vehicles')->insert($data2);
        
        $vehiclesId = DB::table('vehicle')->orderBy('id', 'desc')->first();
        DB::table('users')->where('email', Auth::user()->email)->update(['vehiclesid' => $vehiclesId->id]);
        #var_dump($vehicle);
    }

}