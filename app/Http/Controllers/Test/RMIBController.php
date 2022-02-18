<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\PaketSoal;
use App\Models\Pelamar;
use App\Models\Soal;
use App\Models\Tes;
use App\Models\User;

class RMIBController extends Controller
{    
    /**
     * Menampilkan halaman tes
     * 
     * string $path
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request, $path, $tes, $seleksi, $check)
    {
        // Tes
        $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->first();
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('soal.id_paket','=',$paket->id_paket)->orderBy('nomor','asc')->get();

        // View
        return view('tes/'.$path, [
            'check' => $check,
            'paket' => $paket,
            'path' => $path,
            'seleksi' => $seleksi,
            'soal' => $soal,
            'tes' => $tes,
        ]);
    }

    /**
     * Memproses dan menyimpan tes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        // Tes
        $paket = PaketSoal::where('id_paket','=',$request->id_paket)->where('status','=',1)->first();
        
        // Get data HRD
        if(Auth::user()->role_id == role('hrd')){
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();
        }
        elseif(Auth::user()->role_id == role('employee')){
            $karyawan = Karyawan::where('id_user','=',Auth::user()->id)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
        elseif(Auth::user()->role_id == role('applicant')){
            $pelamar = Pelamar::where('id_user','=',Auth::user()->id)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Answers
        $array = [];
        $array['answers'] = $request->score;
        $array['occupations'] = $request->occupations;

        // Sort array answers by key
        foreach($array['answers'] as $key=>$answer) {
            ksort($array['answers'][$key]);
        }

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();

        // Return
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes RMIB']);
    }
}