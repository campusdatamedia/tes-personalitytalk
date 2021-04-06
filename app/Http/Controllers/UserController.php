<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;

class UserController extends Controller
{
    /**
     * Menampilkan data admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function admin()
    {
    	// Get data admin
    	$admin = User::where('role','=',1)->get();

    	// View
    	return view('admin/index', [
    		'admin' => $admin,
    	]);
    }

    /**
     * Menampilkan data HRD
     * 
     * @return \Illuminate\Http\Response
     */
    public function hrd()
    {
        // Get data HRD
        $hrd = User::where('role','=',2)->get();

        // View
        if(Auth::user()->role == 1){
            return view('hrd/admin/index', [
                'hrd' => $hrd,
            ]);
        }
        elseif(Auth::user()->role == 2){
            return view('hrd/hrd/index', [
                'hrd' => $hrd,
            ]);
        }
    }

    /**
     * Menampilkan form input admin
     *
     * @return \Illuminate\Http\Response
     */
    public function createAdmin()
    {
        // View
        return view('admin/create');
    }

    /**
     * Menampilkan form input HRD
     *
     * @return \Illuminate\Http\Response
     */
    public function createHRD()
    {
        // View
        if(Auth::user()->role == 1){
            return view('hrd/admin/create');
        }
        elseif(Auth::user()->role == 2){
            return view('hrd/hrd/create');
        }
    }

    /**
     * Menyimpan data admin...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin(Request $request)
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
            'nama' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|min:4',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $admin = new User;
            $admin->nama_user = $request->nama;
            $admin->email = $request->email;
            $admin->username = $request->username;
            $admin->password = bcrypt($request->password);
            $admin->foto = '';
            $admin->role = 1;
            $admin->created_at = date("Y-m-d H:i:s");
            $admin->save();
        }

        // Redirect
        return redirect('admin/list');
    }

    /**
     * Menyimpan data HRD...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeHRD(Request $request)
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
            'nama' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|min:4',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $hrd = new User;
            $hrd->nama_user = $request->nama;
            $hrd->email = $request->email;
            $hrd->username = $request->username;
            $hrd->password = bcrypt($request->password);
            $hrd->foto = '';
            $hrd->role = 2;
            $hrd->created_at = date("Y-m-d H:i:s");
            $hrd->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/hrd');
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/list');
        }
    }

    /**
     * Menampilkan form edit admin
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function editAdmin($id)
    {
    	// Get data admin
    	$admin = User::find($id);

    	// Jika tidak ada data
    	if(!$admin){
    		abort(404);
    	}

        // View
        return view('admin/edit', ['admin' => $admin]);
    }

    /**
     * Menampilkan form edit HRD
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function editHRD($id)
    {
        // Get data HRD
        $hrd = User::find($id);

        // Jika tidak ada data
        if(!$hrd){
            abort(404);
        }

        // View
        if(Auth::user()->role == 1){
            return view('hrd/admin/edit', ['hrd' => $hrd]);
        }
        elseif(Auth::user()->role == 2){
            return view('hrd/hrd/edit', ['hrd' => $hrd]);
        }
    }

    /**
     * Mengupdate data admin...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAdmin(Request $request)
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
            'nama' => 'required|min:3|max:255',
            'email' => 'required|email',
            'username' => 'required|string|min:4',
            'password' => $request->password != '' ? 'required|min:4' : '',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $admin = User::find($request->id);
            $admin->nama_user = $request->nama;
            $admin->email = $request->email;
            $admin->username = $request->username;
            $admin->password = $request->password != '' ? bcrypt($request->password) : $admin->password;
            $admin->save();
        }

        // Redirect
        return redirect('admin/list');
    }

    /**
     * Mengupdate data HRD...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateHRD(Request $request)
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
            'nama' => 'required|min:3|max:255',
            'email' => 'required|email',
            'username' => 'required|string|min:4',
            'password' => $request->password != '' ? 'required|min:4' : '',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $hrd = User::find($request->id);
            $hrd->nama_user = $request->nama;
            $hrd->email = $request->email;
            $hrd->username = $request->username;
            $hrd->password = $request->password != '' ? bcrypt($request->password) : $hrd->password;
            $hrd->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/hrd');
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/list');
        }
    }

    /**
     * Menghapus admin...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $admin = User::find($request->id);
        if($admin->delete()){
            echo "Berhasil menghapus data!";
        }
    }
}
