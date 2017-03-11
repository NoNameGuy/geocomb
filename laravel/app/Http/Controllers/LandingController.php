<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
        #$districts = DB::select('select name from District;');
        $districts = DB::table('District')->orderBy('name')->get();

        return View('landing_page', ['districts' => $districts]);#.index', ['districts' => $districts]);
    }
}
