<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ApplicantMail;
use App\Mail\HRDMail;
use App\Providers\RouteServiceProvider;
use App\Agama;
use App\Lowongan;
use App\Pelamar;
use App\Temp;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ApplicantRegisterController extends Controller
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
    public function showRegistrationFormStep1()
    {
        // Check session
        $url_form = Session::get('url');
        $email = Session::get('email');

        // Jika tidak ada session url
        if($url_form == null){
            abort(404);
        }

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_1', $array) ? $array['step_1'] : array();
        }

    	// Set variable
    	$step = 1;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-2';
    	$currentPath = 'step-1';

        // Data agama
        $agama = Agama::all();

    	// Delete session
    	if(!is_int(strpos($previousPath, $truePreviousPath)) && !is_int(strpos($previousPath, $currentPath))){
    		$this->removePhotoAndSession();
	    }

        return view('auth/applicant/register-step-1', [
            'agama' => $agama,
            'array' => $array,
        	'previousPath' => $previousPath,
        	'truePreviousPath' => $truePreviousPath,
            'step' => $step,
            'url_form' => $url_form,
        ]);
    }

    /**
     * Validate and submit registration form step 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep1(Request $request)
    {
        // Pesan Error
        $messages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute wajib dengan nomor atau angka.',
            'unique' => ':attribute sudah ada.',
            'email' => ':attribute wajib menggunakan format email.',
            'min' => ':attribute harus diisi minimal :min karakter.',
            'max' => ':attribute harus diisi maksimal :max karakter.',
        ];

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|min:3|max:255',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'email' => 'required|email|unique:users',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'pendidikan_terakhir' => 'required',
            'akun_sosmed' => 'required',
            'status_hubungan' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput()->with(['url' => $request->url]);
        }
        // Jika tidak ada error
        else{
            // Set array step 1
            $array = $request->all();
            $array['umur'] = generate_age($request->tanggal_lahir);
            unset($array['_token']);
            unset($array['url']);
            foreach($array as $key=>$value){
                $array[$key] = $value == null ? '' : $value;
            }

            // Simpan ke temp
            $temp = Temp::where('email','=',$request->email)->first();
            if(!$temp){
                $temp = new Temp;
                $array = array('step_1' => $array);
                $temp->json = json_encode($array);
            }
            else{
                $json = json_decode($temp->json, true);
                $json['step_1'] = $array;
                $temp->json = json_encode($json);
            }
            $temp->email = $request->email;
            $temp->save();

        	// Simpan ke session
            $request->session()->put('email', $request->email);
            $request->session()->put('url', $request->url);
        }

        // Redirect
        return redirect('applicant/register/step-3');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep2()
    {
    	// Set variable
        $email = Session::get('email');
    	$step = 2;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_2', $array) ? $array['step_2'] : array();
        }

    	// Delete session
    	if(!is_int(strpos($previousPath, 'step-'))){
    		$this->removePhotoAndSession();
    		return redirect('applicant/register/step-1');
		}
		elseif(is_int(strpos($previousPath, 'step-2'))){
    		$this->removePhotoAndSession();
    		return redirect('applicant/register/step-1');
		}

        return view('auth/applicant/register-step-2', [
            'array' => $array,
        	'previousPath' => $previousPath,
            'step' => $step,
        ]);
    }

    /**
     * Validate and submit registration form step 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep2(Request $request)
    {
        // ini_set('max_execution_time', 0);
        var_dump($request->all());
        return;
        
    	// Upload pas foto
        if($request->get('src_pas_foto') != '' && $request->get('src_pas_foto') != null){
            $image = $request->get('src_pas_foto');
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name = $request->pas_foto;
            file_put_contents('assets/images/pas-foto/'.$image_name, $image);
        }

    	// Upload foto ijazah
        if($request->get('src_foto_ijazah') != '' && $request->get('src_foto_ijazah') != null){
            $image = $request->get('src_foto_ijazah');
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name = $request->foto_ijazah;
            file_put_contents('assets/images/foto-ijazah/'.$image_name, $image);
        }

        // Simpan ke temp
        $temp = Temp::where('email','=',Session::get('email'))->first();
        $array = json_decode($temp->json, true);
        $array['step_2'] = array(
            'pas_foto' => $request->pas_foto,
            'foto_ijazah' => $request->foto_ijazah,
        );
        $temp->json = json_encode($array);
        $temp->save();

        // Redirect
        return redirect('applicant/register/step-3');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep3()
    {
    	// Set variable
        $email = Session::get('email');
    	$step = 3;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    // 	$truePreviousPath = 'step-2';
    	$truePreviousPath = 'step-1';
    	$currentPath = 'step-3';

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_3', $array) ? $array['step_3'] : array();
        }

    	// Delete session
    	if(!is_int(strpos($previousPath, $truePreviousPath)) && !is_int(strpos($previousPath, $currentPath))){
    		$this->removePhotoAndSession();
    		return redirect('applicant/register/step-1');
		}

        return view('auth/applicant/register-step-3', [
            'array' => $array,
        	'previousPath' => $previousPath,
            'step' => $step,
        ]);
    }

    /**
     * Validate and submit registration form step 3
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep3(Request $request)
    {
        // Pesan Error
        $messages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute wajib dengan nomor atau angka.',
            'unique' => ':attribute sudah ada.',
            'email' => ':attribute wajib menggunakan format email.',
            'min' => ':attribute harus diisi minimal :min karakter.',
            'max' => ':attribute harus diisi maksimal :max karakter.',
        ];

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_orang_tua' => 'required|min:3|max:255',
            'nomor_hp_orang_tua' => 'required',
            'alamat_orang_tua' => 'required',
            'pekerjaan_orang_tua' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Set array step 3
            $post = $request->all();
            unset($post['_token']);
            foreach($post as $key=>$value){
                $post[$key] = $value == null ? '' : $value;
            }

            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_3'] = $post;
            $temp->json = json_encode($array);
            $temp->save();

            // Mengambil data temp
            $temp_data = Temp::where('email','=',Session::get('email'))->first();
            $temp_array = json_decode($temp_data->json, true);

            // Menambah akun pelamar
            $shuffle_username = shuffleString(6);
            $shuffle_password = shuffleString(10);
            $applicant = new User;
            $applicant->nama_user = $temp_array['step_1']['nama_lengkap'];
            $applicant->email = $temp_array['step_1']['email'];
            $applicant->username = $shuffle_username;
            $applicant->password = bcrypt($shuffle_password);
            $applicant->password_str = $shuffle_password;
            $applicant->foto = '';
            $applicant->role = 3;
            $applicant->created_at = date("Y-m-d H:i:s");
            $applicant->save();

            // Ambil data akun pelamar
            $akun = User::where('username','=',$applicant->username)->first();
            
            // Ambil data lowongan
            $lowongan = Lowongan::where('url_lowongan','=',Session::get('url'))->first();

            // Menambah data pelamar
            $pelamar = new Pelamar;
            $pelamar->nama_lengkap = $temp_array['step_1']['nama_lengkap'];
            $pelamar->tempat_lahir = $temp_array['step_1']['tempat_lahir'];
            $pelamar->tanggal_lahir = $temp_array['step_1']['tanggal_lahir'];
            $pelamar->jenis_kelamin = $temp_array['step_1']['jenis_kelamin'];
            $pelamar->agama = $temp_array['step_1']['agama'];
            $pelamar->email = $temp_array['step_1']['email'];
            $pelamar->nomor_hp = $temp_array['step_1']['nomor_hp'];
            $pelamar->alamat = $temp_array['step_1']['alamat'];
            $pelamar->pendidikan_terakhir = $temp_array['step_1']['pendidikan_terakhir'];
            $pelamar->umur = $temp_array['step_1']['umur'];
            $pelamar->nomor_ktp = $temp_array['step_1']['nomor_ktp'];
            $pelamar->nomor_telepon = $temp_array['step_1']['nomor_telepon'];
            $pelamar->status_hubungan = $temp_array['step_1']['status_hubungan'];
            $pelamar->kode_pos = $temp_array['step_1']['kode_pos'];
            $pelamar->data_darurat = json_encode($temp_array['step_3']);
            $pelamar->akun_sosmed = json_encode(array($temp_array['step_1']['sosmed'] => $temp_array['step_1']['akun_sosmed']));
            $pelamar->pendidikan_formal = '';
            $pelamar->pendidikan_non_formal = '';
            $pelamar->pengalaman_kerja = '';
            $pelamar->keahlian = '';
            $pelamar->pertanyaan = '';
            $pelamar->pas_foto = array_key_exists('step_2', $temp_array) ? $temp_array['step_2']['pas_foto'] : '';
            $pelamar->foto_ijazah = array_key_exists('step_2', $temp_array) ? $temp_array['step_2']['foto_ijazah'] : '';
            $pelamar->id_user = $akun->id_user;
            $pelamar->posisi = $lowongan->id_lowongan;
            $pelamar->created_at = date("Y-m-d H:i:s");
            $pelamar->save();

            // Ambil data akun pelamar
            $akun_pelamar = Pelamar::where('email','=',$applicant->email)->first();

            // Send Mail to HRD
            $hrd = User::where('role','=',2)->get();
            foreach($hrd as $data){
                Mail::to($data->email)->send(new HRDMail($akun_pelamar->id_pelamar));
            }

            // Send Mail to Pelamar
            Mail::to($applicant->email)->send(new ApplicantMail($akun_pelamar->id_pelamar));

            // Remove session
            $this->removeSession();
        }

        // Redirect
        // return redirect('applicant/register/success');

        // View
        return view('auth/applicant/success');
    }

    /**
     * Mengirim email bahwa ada pelamar ke HRD
     *
     * @return void
     */
    public function sendMailToHRD()
    {
        Mail::to("ajifatur14@students.unnes.ac.id")->send(new HRDMail(6));
 
        return "Email telah dikirim";
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/applicant';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // Remove file
    public function removeFile($dir, $filename){
    	File::delete($dir.$filename);
    }

    // Remove session
    public function removeSession(){
        // Get data temp
        $temp = Temp::where('email','=',Session::get('email'))->first();

        // If temp is exist
        if($temp != null){
            // Delete data temp
            $temp->delete();
        }

        // Remove session
        Session::forget('url');
        Session::forget('email');
    }

    // Remove photo and session
    public function removePhotoAndSession(){
        // Get data temp
        $temp = Temp::where('email','=',Session::get('email'))->first();

        // If temp is exist
        if($temp != null){
            // Convert json to array
            $array = json_decode($temp->json, true);

        	// Remove file first before remove session
        	if(array_key_exists('step_2', $array)){
            	$this->removeFile('assets/images/pas-foto/', $array['step_2']['pas_foto']);
            	$this->removeFile('assets/images/foto-ijazah/', $array['step_2']['foto_ijazah']);
        	}

            // Delete data temp
            $temp->delete();
        }

    	// And then remove session
        Session::forget('url');
    	Session::forget('email');
    }
}
