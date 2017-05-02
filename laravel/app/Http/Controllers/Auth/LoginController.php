<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        $user = DB::table('users')->where('email', $request->input('email'))->where('password', $request->input('password'))->first();
        if ($user) {
            var_dump($user);
            return view('user_page', ['name' => $user->name]);
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
