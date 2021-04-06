<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Agama;
use App\Lowongan;
use App\Pelamar;
use App\Seleksi;
use App\User;

class PelamarController extends Controller
{
    /**
     * Menampilkan data pelamar
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Get data
    	$pelamar = Pelamar::orderBy('created_at','desc')->get();
        foreach($pelamar as $data){
            $data->id_user = User::find($data->id_user);
            $data->posisi = Lowongan::find($data->posisi);
        }

    	// View
        if(Auth::user()->role == 1){
        	return view('pelamar/admin/index', [
        		'pelamar' => $pelamar,
        	]);
        }
        elseif(Auth::user()->role == 2){
            return view('pelamar/hrd/index', [
                'pelamar' => $pelamar,
            ]);
        }
    }

    /**
     * Menampilkan profil pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
    	// Get data
    	$pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->first();

    	// Jika tidak ada data
    	if(!$pelamar){
    		abort(404);
    	}
        // Jika ada data
        else{
            // Data lowongan
            $pelamar->posisi = Lowongan::where('id_lowongan','=',$pelamar->posisi)->first();

            // Data seleksi
            $seleksi = Seleksi::where('id_pelamar','=',$id)->first();

            // // Set data
            // $pelamar->data_darurat = json_decode($pelamar->data_darurat, true);
            // $pelamar->akun_sosmed = json_decode($pelamar->akun_sosmed, true);
            // $pelamar->pendidikan_formal = json_decode($pelamar->pendidikan_formal, true);
            // $pelamar->pendidikan_non_formal = json_decode($pelamar->pendidikan_non_formal, true);
            // $pelamar->pengalaman_kerja = json_decode($pelamar->pengalaman_kerja, true);
            // $pelamar->keahlian = json_decode($pelamar->keahlian, true);
            // $pelamar->pertanyaan = json_decode($pelamar->pertanyaan, true);

            // // Keahlian
            // $keahlian = array(
            //  'Ms. Word',
            //  'Ms. Excel',
            //  'Ms. PowerPoint',
            //  'Internet',
            // );

            // // Pertanyaan
            // $pertanyaan = array(
            //  'Dari sumber mana anda mendapatkan informasi mengenai pekerjaan ini ?',
            //  'Apa sumber media informasi yang sering anda baca ?',
            //  'Sebutkan yang menjadi kelebihan anda ',
            //  'Sebutkan hal yang masih perlu diperbaiki pada diri Anda',
            //  'Berapa gaji yang anda inginkan ?',
            //  'Apa gangguan kesehatan yang sering anda alami ?',
            //  'Uraikan keinginan anda dalam 5 tahun mendatang ',
            //  'Apakah anda bersedia ditempatkan di cabang mana saja ? (Ya/Tidak)',
            // );

            // // View
            // return view('pelamar/admin/profile-2', [
            //     'pelamar' => $pelamar,
            //     'keahlian' => $keahlian,
            //     'pertanyaan' => $pertanyaan,
            //     'ta' => $ta,
            //     'tw' => $tw
            // ]); 

            // Set data
            $pelamar->akun_sosmed = json_decode($pelamar->akun_sosmed, true);

            // View
            if(Auth::user()->role == 1){
                return view('pelamar/admin/profile-1', [
                    'pelamar' => $pelamar,
                    'seleksi' => $seleksi,
                ]);
            }
            elseif(Auth::user()->role == 2){
                return view('pelamar/hrd/profile-1', [
                    'pelamar' => $pelamar,
                    'seleksi' => $seleksi,
                ]);
            }
        }
    }

    /**
     * Menampilkan profil user pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function profileInApplicant()
    {
        // Get data
        $pelamar = Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('agama','pelamar.agama','=','agama.id_agama')->where('pelamar.id_user','=',Auth::user()->id_user)->orderBy('pelamar.created_at','desc')->first();

        // Jika tidak ada data
        if(!$pelamar){
            abort(404);
        }
        // Jika ada data
        else{
            // Set data
            $pelamar->akun_sosmed = json_decode($pelamar->akun_sosmed, true);

            // View
            return view('pelamar/applicant/profile', ['pelamar' => $pelamar]); 
        }
    }

    /**
     * Menampilkan form edit pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// Get data
        $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->first();

    	// Jika tidak ada data
    	if(!$pelamar){
    		abort(404);
    	}
        // Jika ada data
        else{
            $pelamar->posisi = Lowongan::where('id_lowongan','=',$pelamar->posisi)->first();
        	$pelamar->akun_sosmed = json_decode($pelamar->akun_sosmed, true);
        }

        // Data agama
        $agama = Agama::all();

        // View
        if(Auth::user()->role == 1){
            return view('pelamar/admin/edit', [
            	'pelamar' => $pelamar,
            	'agama' => $agama,
            ]);
        }
        elseif(Auth::user()->role == 2){
            return view('pelamar/hrd/edit', [
                'pelamar' => $pelamar,
                'agama' => $agama,
            ]);
        }
    }

    /**
     * Mengupdate data pelamar...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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
            'email' => [
                'required',
                Rule::unique('users')->ignore($request->id_user, 'id_user'),
            ],
            // 'email' => 'required|email|unique:users',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'pendidikan_terakhir' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
        	// Array akun sosmed
        	$akun_sosmed = array();
        	foreach($request->get('akun_sosmed') as $key=>$value){
        		$akun_sosmed[$key] = $value != null ? $value : '';
        	}

            // Mengupdate data
            $pelamar = Pelamar::find($request->id);
            $pelamar->nama_lengkap = $request->nama_lengkap;
            $pelamar->tempat_lahir = $request->tempat_lahir;
            $pelamar->tanggal_lahir = $request->tanggal_lahir;
            $pelamar->jenis_kelamin = $request->jenis_kelamin;
            $pelamar->agama = $request->agama;
            $pelamar->email = $request->email;
            $pelamar->nomor_hp = $request->nomor_hp;
            $pelamar->alamat = $request->alamat;
            $pelamar->pendidikan_terakhir = $request->pendidikan_terakhir;
        	$pelamar->akun_sosmed = json_encode($akun_sosmed);
            $pelamar->save();

            // Mengupdate data user
            $user = User::find($request->id_user);
            $user->nama_user = $request->nama_lengkap;
            $user->email = $request->email;
            $user->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/pelamar/profile/'.$request->id)->with(['message' => 'Berhasil memperbarui data.']);
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/pelamar/profile/'.$request->id)->with(['message' => 'Berhasil memperbarui data.']);
        }
    }

    /**
     * Menghapus data pelamar...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data dan gambar
        $pelamar = Pelamar::find($request->id);
        $user = User::find($pelamar->id_user);
        $seleksi = Seleksi::where('id_pelamar','=',$request->id)->first();
        // File::delete('assets/images/pas-foto/'.$pelamar->pas_foto);
        // File::delete('assets/images/foto-ijazah/'.$pelamar->foto_ijazah);
        
        if($seleksi != null){
            $seleksi->delete();
        }

        if($pelamar->delete() && $user->delete()){
            echo "Berhasil menghapus data!";
        }
    }
}
