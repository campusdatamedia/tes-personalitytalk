<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth/register');
    }

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Verification.
     *
     */
    protected function verification($token)
    {
        // Get data verification
        $verification = Verification::where('token','=',$token)->first();
        
        // Jika tidak ada
        if(!$verification){
            abort(404);
        }
        // Jika ada
        else{
            $verification->status = 1;
            $verification->save();
            
            $user = User::find($verification->id_user);
            $this->guard()->login($user);
        }
        
        return redirect('/dashboard');
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
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6',],
            'nomor_hp' => ['required', 'numeric'],
            'alamat' => ['required', 'string', 'max:255'],
            'posisi_magang' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'nama_user' => $data['nama_lengkap'],
            'tanggal_lahir' => null,
            'jenis_kelamin' => $data['posisi_magang'],
            'username' => $data['email'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'password_str' => $data['nomor_hp'],
            'foto' => $data['alamat'],
            'role' => 6, //magang
            'has_access' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
