<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/user_page';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*public function login(Request $request)
    {
        $user = new User();
        $user = User::where('email', $request->email);
         #echo User::all();
        //$user = DB::table('users')->where('email', $request->email)->first();
        #if(Auth::attempt(['email' => $request->email, 'password' => $user->password])){
        #if( Hash::check($request->password, $user->password) ) {
        if(Auth::login($user)){
        //if ( Hash::check( $request->password, $user->password) && Auth::attempt(['email' => $request->email])) {
            // Authentication passed...
           return redirect()->intended('user_page');
        } else{
            echo "not logged in";
        }
    }*/

    public function logout(Request $request)
    {
        return view('landing_page');
    }

    public function index()
    {
        return View('login');
    }
}
