<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request)
    {

      $input = $request->all();
      $validator = $this->validator($input);

      if($validator->passes()){
        $user = $this->create($input)->toArray();
        $user['link'] = str_random(30);

        DB::table('user_activations')->insert(['id_user'=>$user['id'], 'token'=>$user['link']]);

        Mail::send('emails.activation', $user, function($message) use ($user){
          $message->to($user['email']);
          $message->subject('GeoComb - Activation Code');
        });
        return redirect()->to('login')->with('success',"Foi enviado um email de confimação para a sua conta");
      }

      return back()->with('errors', $validator->errors());

    }

    public function userActivation($token)
    {
      $check = DB::table('user_activations')->where('token', $token)->first();
      if(!is_null($check)){
        $user = USER::find($check->id_user);
        if($user->is_activated == 1){
          return redirect()->to('login')->with('success', "Já logado.");
        }
        $user->update(['is_activated' => 1]);
        DB::table('user_activations')->where('token',$token)->delete();
        return redirect()->to('login')->with('success', "Utilizador Activado");
      }
      return redirect()->to('login')->with('warning', "Token Inválido");

    }

    public function index()
    {
        return View('register');
    }
}
