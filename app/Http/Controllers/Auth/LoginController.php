<?php
namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
use DB;

if(session_id() == '' || !isset($_SESSION)) {
    session_start();
}

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
            $_SESSION['user'] = [
                'id' => $authuser,
                'avatar' => $user->getAvatar(),
            ];
            return redirect('/');
        } else {
            Auth::logout();
            return redirect('/login')->withErrors(['Unauthorized access. Please log in using a different account.']);
        }
    }

    public function findUser($user,$provider)
    {
        //$authuser = DB::table('account')->where('accEmail', $user->getEmail())->first();
        $authuser = User::where('accEmail', $user->getEmail())->first();
        return $authuser;
    }

    public function logout(Request $request) {
        Auth::logout();
        unset($_SESSION['user']);
        return redirect('/login');
    }
}
