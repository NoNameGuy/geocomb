<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPageController extends Controller
{
    public function index(Request $request)
    {
        
        $userId = DB::table('users')->where('email', Auth::user()->email)->select('id')->get();
        
        $userVehicles = DB::table('vehicles')->where('user_id', $userId[0]->id)->get();
        echo "user vehicles count".$userVehicles->count();
        for($i=1; $i<=$userVehicles->count();$i++){
            var_dump($userVehicles[$i]);
            $vehicles = DB::table('vehicle')->where('id', $userVehicles[$i]->vehicle_id)->get();
        }
    	return view('user_page', ['vehicles' => $vehicles]);
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