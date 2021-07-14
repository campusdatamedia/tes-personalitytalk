<?php

namespace App\Http\Controllers\Test;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PaketSoal;
use App\Soal;
use App\Tes;
use App\User;

class ISTController extends Controller
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
        // Vars
        $first_soal = '';
        $last_soal = '';

        // Tes
        $part = $request->query('part') ?: 1;
        $paket = PaketSoal::where('id_tes','=',$tes->id_tes)->where('part','=',$part)->where('status','=',1)->firstOrFail();
        $soal = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('part','=',$part)->where('soal.id_paket','=',$paket->id_paket)->where('status','=',1)->orderBy('nomor','asc')->get();
        if(count($soal)>0){
            foreach($soal as $key=>$data){
                $data->soal = json_decode($data->soal, true);
                if($key == 0) $first_soal = $data->nomor;
                if($key == count($soal)-1) $last_soal = $data->nomor;
            }
        }
        $range_soal = count($soal) > 1 ? $first_soal.'-'.$last_soal : $first_soal; // Range soal
        $last_part = PaketSoal::where('id_tes','=',$tes->id_tes)->where('status','=',1)->latest('part')->first(); // Part paket soal terakhir

        // View
        return view('tes/'.$path, [
            'check' => $check,
            'paket' => $paket,
            'path' => $path,
            'part' => $part,
            'range_soal' => $range_soal,
            'seleksi' => $seleksi,
            'soal' => $soal,
            'tes' => $tes,
            'last_part' => $last_part,
        ]);
    }
}