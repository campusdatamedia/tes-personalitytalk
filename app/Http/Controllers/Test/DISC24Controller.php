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

class DISC24Controller extends Controller
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
        if(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }
        elseif(Auth::user()->role == 3){
            $karyawan = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($karyawan->id_hrd);
        }
        elseif(Auth::user()->role == 4){
            $pelamar = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            $hrd = HRD::find($pelamar->id_hrd);
        }
        
        // Convert DISC score to JSON
        $array = array(
            'dm' => $request->Dm,
            'im' => $request->Im,
            'sm' => $request->Sm,
            'cm' => $request->Cm,
            'bm' => $request->Bm,
            'dl' => $request->Dl,
            'il' => $request->Il,
            'sl' => $request->Sl,
            'cl' => $request->Cl,
            'bl' => $request->Bl
        );
        $array['answers']['m'] = $request->y;
        $array['answers']['l'] = $request->n;

        // Menyimpan data
        $hasil = new Hasil;
        $hasil->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
        $hasil->id_user = Auth::user()->id_user;
        $hasil->id_tes = $request->id_tes;
        $hasil->id_paket = $request->id_paket;
        $hasil->hasil = json_encode($array);
        $hasil->test_at = date("Y-m-d H:i:s");
        $hasil->save();

        // Return
        return redirect('/dashboard')->with(['message' => 'Berhasil mengerjakan tes DISC 24 Soal']);
    }
}