<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

    }

    public function login(Request $request)
    {
        $user = DB::table('users')->where('email', $request->email)->first();
        if(Auth::attempt(['email' => $request->email, 'password' => $user->password])){
        #if( Hash::check($request->password, $user->password) ) {
            #dd(Auth::user());
            return Redirect('user_page');#, ['name' => Auth::user()->name]);
        }else{
            echo "not logged in";
        }
    }

    public function logout(Request $request)
    {
        return view('landing_page');
    }

    public function index()
    {
        return View('login');
    }
}
