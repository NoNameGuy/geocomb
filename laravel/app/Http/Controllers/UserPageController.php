<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserPageController extends Controller
{
    public function index()
    {
    	return view('user_page');
    }

    public function add(Request $request)
    {

    	$data = ['brand' => $request->brand, 'model' => $request->model, 'color' => $request->color, 'fuel' => $request->fuel, 'consumption' => $request->consumption];
        DB::table('vehicle')->insert($data);
        $vehicle = DB::table('vehicle')->orderBy('id', 'desc')->first();
#var_dump($vehicle);
        DB::table('users')->where('email', 'goncalo@gmail.com')->update(['vehicleid'=>$vehicle->id]);
        var_dump($vehicle);
    }
}
