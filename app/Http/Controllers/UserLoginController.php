<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use App\User;
use App\Verification;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class UserLoginController extends Controller
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
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        // Get URLs
        $urlPrevious = url()->previous();
        $urlBase = url()->to('/');
        $mainDomain = 'https://psikologanda.com';

        // If applicant came from login, remove session url.intended
        if(session()->get('url.intended') == '/logout'){
            session()->forget('url.intended');
        }
        // Set the previous url that we came from to redirect to after successful login but only if is internal
        elseif(($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) !== $urlBase) && ($urlBase == $mainDomain)) {
            session()->put('url.intended', $urlPrevious);

            // View
            return view('auth/login', ['message' => 'Anda harus login terlebih dahulu!']);
        }

        // View
        return view('auth/login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|min:4',
            'password' => 'required|string|min:4',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // $request->merge(['has_access' => 2]);

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // if role = 5
        if($user->role == 5){
            // Check verification data
            $verification = Verification::where('id_user','=',$user->id_user)->first();
            if($verification->status == 0){
                $this->guard()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return view('auth/login', ['message' => 'Anda belum melakukan verifikasi akun!']);
            }
            else{
                return redirect('/dashboard');
            }
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->put('url.intended', '/logout');

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
