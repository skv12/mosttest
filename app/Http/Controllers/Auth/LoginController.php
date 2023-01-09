<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\UserStatus;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    public function username()
    {
        return 'name';
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $name = $request->name;
        $user = User::where('name', $name)->first();
        if (!$user) {
            return redirect()->back()->withInput($request->only('name'))->withErrors([
                'name' => 'We could not find you in our database, if you think this is a mistake kindly contact the site administrators',
            ]);
        }
        Auth::login($user);
        broadcast(new UserStatus($user, 'login'));
        return redirect('/');
    }

    public function logout(Request $request) {
        $user = Auth::user();
        Auth::logout();
        broadcast(new UserStatus($user, 'logout'));
        return redirect('/login');
      }
}
