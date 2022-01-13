<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\Lowongan;
use App\Models\PaketSoal;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\Seleksi;
use App\Models\Soal;
use App\Models\Tes;
use App\Models\User;

class DashboardController extends Controller
{    
    /**
     * Menampilkan dashboard
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Default
        $seleksi = false;
        $check = null;
        $gambar = ['lightning-bolts.svg','arrows.svg','thoughts.svg','gears.svg','keys.svg','lightning-bolts.svg','arrows.svg','thoughts.svg','gears.svg','keys.svg'];

        // Jika role karyawan
        if(Auth::user()->role == 3){
            // Get akun
            $akun = Karyawan::where('id_user','=',Auth::user()->id_user)->first();
            
            // Tes
            $posisi = Posisi::find($akun->posisi);
            $posisi->tes = $posisi->tes != '' ? explode(",", $posisi->tes) : array();
            $tes = !empty($posisi->tes) ? Tes::whereIn('id_tes', $posisi->tes)->get() : array();
        }
        // Jika role pelamar
        elseif(Auth::user()->role == 4){
        	// Get akun
        	$akun = Pelamar::where('id_user','=',Auth::user()->id_user)->first();
            
            // Seleksi
            $seleksi = Seleksi::where('id_pelamar','=',$akun->id_pelamar)->first();
            
            // Hasil
            $ids = array();
            $hasil = Hasil::where('id_user','=',Auth::user()->id_user)->get();
            if(count($hasil) > 0){
                foreach($hasil as $h){
                    if(!in_array($h->id_tes, $ids)){
                        array_push($ids, $h->id_tes);
                    }
                }
            }
            
            // Tes
            $lowongan = Lowongan::find($akun->posisi);
            $posisi = Posisi::find($lowongan->posisi);
            $posisi->tes = $posisi->tes != '' ? explode(",", $posisi->tes) : array();
            $tes = !empty($posisi->tes) ? Tes::whereIn('id_tes', $posisi->tes)->whereNotIn('id_tes', $ids)->get() : array();
        }
        // Jika role bukan pelamar dan karyawan
        else{
        	// Get akun
        	$akun = User::find(Auth::user()->id_user);
            
            // Tes
            $tes = Tes::all();
			
			// Check jika role magang
			if(Auth::user()->role == 6){
				$check = Hasil::where('id_user','=',Auth::user()->id_user)->first();
			}
        }

        // View
        return view('dashboard/index', [
            'akun' => $akun,
            'check' => $check,
            'gambar' => $gambar,
            'seleksi' => $seleksi,
            'tes' => $tes,
        ]);
    }
}