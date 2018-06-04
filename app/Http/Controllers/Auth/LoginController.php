<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
use DB;

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
        $this->middleware('guest')->except('logout');
    }
    
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        /*$authuser = $this->findUser($user,$provider);
        if($authuser != false) {
            Auth::login($authuser,true);
        }

         $newUser = new User;
        $newUser->email = $user->getEmail();
        $newUser->name = $user->getName();
        $newUser->save();

        return $user->token;*/
        $authuser = $this->findUser($user,$provider);
        if($authuser != false) {
            Auth::login($authuser,true);
        }
        return $user->getEmail();
    }

    public function findUser($user,$provider)
    {
        //$authuser = DB::table('account')->where('AccEmail', $user->getEmail())->first();
        $authuser = User::where('AccEmail', $user->getEmail())->first();
        if($authuser) {
            return $authuser;
        }
        return false;
    }
}
